<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class LoaExtractionService
{
    private Parser $parser;

    private string $pdftoppmPath;

    private string $tesseractPath;

    public function __construct()
    {
        $this->parser = new Parser;
        $this->pdftoppmPath = base_path(env('OCR_PDFTOPPM_PATH', 'storage/bin/pdftoppm.exe'));
        $this->tesseractPath = env('OCR_TESSERACT_PATH', 'C:/Program Files/Tesseract-OCR/tesseract.exe');
    }

    /**
     * Extract all data from an LOA PDF file.
     *
     * @return array<string, mixed>
     */
    public function extract(string $filePath): array
    {
        // Try native text extraction first (digital PDFs)
        $text = $this->extractTextNative($filePath);

        // Fall back to OCR for scanned PDFs
        if (empty(trim($text))) {
            $text = $this->extractTextOcr($filePath);
        }

        if (empty(trim($text))) {
            return $this->emptyResult();
        }

        $cleanText = preg_replace('/\s+/', ' ', $text);

        return [
            'railway_zone' => $this->extractRailwayZone($cleanText),
            'division' => $this->extractDivision($cleanText),
            'office' => $this->extractOffice($cleanText),
            'letter_number' => $this->extractLetterNumber($cleanText),
            'letter_date' => $this->extractLetterDate($cleanText),
            'contractor_name' => $this->extractContractorName($cleanText),
            'contractor_address' => $this->extractContractorAddress($text),
            'tender_number' => $this->extractTenderNumber($cleanText),
            'tender_closing_date' => $this->extractTenderClosingDate($cleanText),
            'work_description' => $this->extractWorkDescription($cleanText),
            'bid_id' => $this->extractBidId($cleanText),
            'bid_date' => $this->extractBidDate($cleanText),
            'negotiation_bid_ids' => $this->extractNegotiationBidIds($cleanText),
            'contract_value' => $this->extractContractValue($cleanText),
            'contract_value_words' => $this->extractContractValueWords($cleanText),
            'earnest_money' => $this->extractEarnestMoney($cleanText),
            'ireps_reference_id' => $this->extractIrepsReferenceId($cleanText),
            'performance_guarantee' => $this->extractPerformanceGuarantee($cleanText),
            'net_bid_value' => $this->extractNetBidValue($cleanText),
            'bid_rate_percentage' => $this->extractBidRatePercentage($cleanText),
            'rebate_on_total_value' => $this->extractRebateOnTotalValue($cleanText),
            'completion_period' => $this->extractCompletionPeriod($cleanText),
            'signed_by' => $this->extractSignedBy($cleanText),
            'work_items' => $this->extractWorkItems($text),
        ];
    }

    /**
     * Extract text using smalot/pdfparser (works on digital/text-layer PDFs).
     */
    private function extractTextNative(string $filePath): string
    {
        try {
            $pdf = $this->parser->parseFile($filePath);

            return $pdf->getText();
        } catch (\Exception) {
            return '';
        }
    }

    /**
     * Extract text using Tesseract OCR (works on scanned/image PDFs).
     * Converts each page to PNG via pdftoppm then runs tesseract.
     */
    private function extractTextOcr(string $filePath): string
    {
        if (! file_exists($this->pdftoppmPath) || ! file_exists($this->tesseractPath)) {
            return '';
        }

        $tmpDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'loa_ocr_'.uniqid();
        if (! mkdir($tmpDir, 0777, true)) {
            return '';
        }

        try {
            // Convert PDF pages to PNG images (200 DPI for good OCR accuracy)
            $pagePrefix = $tmpDir.DIRECTORY_SEPARATOR.'page';
            $cmd = sprintf(
                '"%s" -r 200 -png %s %s 2>&1',
                $this->pdftoppmPath,
                escapeshellarg($filePath),
                escapeshellarg($pagePrefix)
            );
            exec($cmd, $pdftoppmOut, $pdftoppmCode);

            $pages = glob($tmpDir.DIRECTORY_SEPARATOR.'*.png');
            if (empty($pages)) {
                return '';
            }

            sort($pages); // Ensure page order

            $allText = '';
            foreach ($pages as $pageImage) {
                $outBase = $pageImage.'.ocr';
                $cmd = sprintf(
                    '"%s" %s %s -l eng 2>&1',
                    $this->tesseractPath,
                    escapeshellarg($pageImage),
                    escapeshellarg($outBase)
                );
                exec($cmd, $tessOut, $tessCode);

                $txtFile = $outBase.'.txt';
                if (file_exists($txtFile)) {
                    $allText .= file_get_contents($txtFile)."\n";
                    unlink($txtFile);
                }
            }

            return $allText;
        } finally {
            // Cleanup temp images
            $files = glob($tmpDir.DIRECTORY_SEPARATOR.'*');
            if ($files) {
                foreach ($files as $f) {
                    @unlink($f);
                }
            }
            @rmdir($tmpDir);
        }
    }

    private function extractRailwayZone(string $text): ?string
    {
        $zones = [
            'EASTERN' => 'Eastern Railway',
            'NORTHERN' => 'Northern Railway',
            'SOUTHERN' => 'Southern Railway',
            'WESTERN' => 'Western Railway',
            'CENTRAL' => 'Central Railway',
            'NORTH EASTERN' => 'North Eastern Railway',
            'NORTH CENTRAL' => 'North Central Railway',
            'SOUTH CENTRAL' => 'South Central Railway',
            'SOUTH EASTERN' => 'South Eastern Railway',
            'SOUTH WESTERN' => 'South Western Railway',
            'EAST CENTRAL' => 'East Central Railway',
            'EAST COAST' => 'East Coast Railway',
            'WEST CENTRAL' => 'West Central Railway',
            'METRO' => 'Metro Railway',
        ];

        foreach ($zones as $abbr => $full) {
            if (preg_match('/\b'.preg_quote($abbr, '/').'\s+RLY\b/i', $text)) {
                return $full;
            }
        }

        if (preg_match('/([A-Z][A-Z\s]+?)\s+RLY\b/i', $text, $m)) {
            return ucwords(strtolower(trim($m[1]))).' Railway';
        }

        return null;
    }

    private function extractDivision(string $text): ?string
    {
        // "HOWRAH DIVISION-ENGG" — must be preceded by a single city/place name word
        if (preg_match('/\b([A-Z]{3,})\s+DIVISION[-\s]*([A-Z]{2,10})\b/i', $text, $m)) {
            $name = ucwords(strtolower(trim($m[1])));
            $dept = strtoupper(trim($m[2]));

            return "$name Division - $dept";
        }
        if (preg_match('/\b([A-Z]{3,})\s+DIVISION\b/i', $text, $m)) {
            return ucwords(strtolower(trim($m[0])));
        }

        return null;
    }

    private function extractOffice(string $text): ?string
    {
        // "Office of the Sr. Divi. Engineer/Co-ordn/HWH, Eastern Railway Howrah"
        // Stop before "Letter No:" or a new-line section
        if (preg_match('/Office\s+of\s+the\s+Sr\.?\s+Div[il]?\.?\s+Engineer.+?(?=Letter\s+No|M\/s|Sub:|$)/is', $text, $m)) {
            $office = preg_replace('/\s+/', ' ', trim($m[0]));
            // Remove city/postal code that may bleed in (stop at postal code line)
            $office = preg_replace('/,?\s+\d{6}\s+.*$/', '', $office);
            // Clean OCR hyphenation
            $office = preg_replace('/(\w)-\s+(\w)/', '$1-$2', $office);

            return trim($office);
        }

        return null;
    }

    private function extractLetterNumber(string $text): ?string
    {
        // "Letter No: HOWRAH DIVISION-ENGG / 117_2024-25 / 00969190113111"
        // OCR may split the number across lines; grab up to the 9-digit reference number
        if (preg_match('/Letter\s+No[:\s]+(.+?\d{9,})/is', $text, $m)) {
            $ln = preg_replace('/\s+/', ' ', trim($m[1]));
            // Strip trailing alphabetic junk after the long reference (e.g. " panes 221021")
            // but keep the reference number itself and slashes that are part of the number
            $ln = preg_replace('/\s+[a-zA-Z][a-zA-Z\s\d]{0,20}$/', '', $ln);

            return trim($ln);
        }
        if (preg_match('/Letter\s+No[:\s]+([^\n;\.]{5,80})/i', $text, $m)) {
            return trim(preg_replace('/\s+/', ' ', $m[1]));
        }

        return null;
    }

    private function extractLetterDate(string $text): ?string
    {
        // Explicit "Dated: dd/mm/yyyy" anywhere in the document
        if (preg_match('/Dated[:\s]+(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4})(?!\s+\d{2}:\d{2})/i', $text, $m)) {
            return $this->normaliseDate($m[1]);
        }
        // Look only in the very header (before "Tender No." reference section)
        $headerEnd = strpos($text, 'Tender No.');
        if ($headerEnd !== false) {
            $header = substr($text, 0, $headerEnd);
            // Only match dd/mm/yyyy or dd-mm-yyyy that is NOT followed by time
            if (preg_match('/\b(\d{2}[\/\-]\d{2}[\/\-]\d{4})\b(?!\s+\d{2}:\d{2})/i', $header, $m)) {
                return $this->normaliseDate($m[1]);
            }
        }

        return null;
    }

    private function extractContractorName(string $text): ?string
    {
        // Match "M/s NAME" capturing the full name
        // Examples: "M/s R N CHOUDHARY-KISHANGANJ", "M/s S. D. ENTERPRISE-BURDWAN"
        if (preg_match('/M\/s\s+(.+?)(?:\s+-\s+[A-Z]+)?(?:\s+[A-Z]{2}\s+[A-Z]+\s+\d+|$)/i', $text, $m)) {
            $name = trim($m[1] ?? '');
            if (! empty($name) && strlen($name) > 2) {
                // Clean up the name
                $name = preg_replace('/\s*-.*$/', '', $name);  // remove anything after hyphen
                $name = preg_replace('/^\s+|\s+$/', '', $name);  // trim

                return 'M/s '.trim($name);
            }
        }
        // Fallback: grab everything after M/s up to next major word
        if (preg_match('/M\/s\s+([A-Z][A-Z\s\.\-]*)/i', $text, $m)) {
            $name = trim($m[1] ?? '');
            if (! empty($name)) {
                $name = preg_replace('/\s*-.*$/', '', $name);

                return 'M/s '.trim($name);
            }
        }

        return null;
    }

    private function extractContractorAddress(string $text): ?string
    {
        // After contractor name line, collect lines until "Sub:" or "Letter Of Acceptance"
        if (preg_match('/M\/s[^\n]{5,80}\n(.+?)(?=Sub:|Letter Of Acceptance)/is', $text, $m)) {
            $addr = trim(preg_replace('/\s+/', ' ', $m[1]));
            // Remove scan artefacts
            $addr = preg_replace('/(CE\s+)?Scanned\s+with\s+OKEN\s+Scanner/i', '', $addr);

            return trim($addr);
        }

        return null;
    }

    private function extractTenderNumber(string $text): ?string
    {
        if (preg_match('/Tender\s+No\.?\s+([A-Z0-9_\-\/]+\d{4}[-_]\d{2,4})/i', $text, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/Tender\s+No\.?\s+([^\s,\.]+)/i', $text, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private function extractTenderClosingDate(string $text): ?string
    {
        if (preg_match('/closing\s+date\s+(\d{1,2}[-\/]\d{2}[-\/]\d{4}\s+\d{1,2}:\d{2})/i', $text, $m)) {
            return $this->normaliseDatetime($m[1]);
        }

        return null;
    }

    private function extractWorkDescription(string $text): ?string
    {
        // "for Destressing of LWR Section under the jurisdiction of St.DEN..."
        if (preg_match('/for\s+(.+?)\s+under\s+the\s+jurisdiction\s+of/is', $text, $m)) {
            return trim(preg_replace('/\s+/', ' ', $m[1]));
        }
        if (preg_match('/for\s+(De[-\s]?stressing[^\.]+\.)/i', $text, $m)) {
            return trim(preg_replace('/\s+/', ' ', $m[1]));
        }

        return null;
    }

    private function extractBidId(string $text): ?string
    {
        if (preg_match('/Your\s+bid\s+ID\s+(\d{7,10})/i', $text, $m)) {
            return $m[1];
        }

        return null;
    }

    private function extractBidDate(string $text): ?string
    {
        if (preg_match('/bid\s+ID\s+\d+\s+dated\s+(\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2})/i', $text, $m)) {
            return $this->normaliseDatetime($m[1]);
        }

        return null;
    }

    private function extractNegotiationBidIds(string $text): ?string
    {
        // "Negotiation bid IDs [ 17163489 dated 20/08/2024 11:21 , 17234299 dated ... ]"
        // The section spans multiple lines, so use a broad match then extract IDs
        if (preg_match('/Negotiation\s+bid\s+IDs?\s*\[?\s*(.+?)\s*\]/is', $text, $m)) {
            preg_match_all('/\b(\d{7,10})\b/', $m[1], $ids);

            return ! empty($ids[1]) ? implode(', ', $ids[1]) : null;
        }
        // Fallback: grab all 7-10 digit numbers after "Negotiation bid IDs"
        if (preg_match('/Negotiation\s+bid\s+IDs?(.{0,300})/is', $text, $m)) {
            preg_match_all('/\b(\d{7,10})\b/', $m[1], $ids);

            return ! empty($ids[1]) ? implode(', ', $ids[1]) : null;
        }

        return null;
    }

    private function extractContractValue(string $text): ?float
    {
        // "total cost of the work at the accepted rates works out to Rs. 11648783.33"
        if (preg_match('/total\s+cost[^R]*Rs\.?\s*([\d,]+\.?\d*)/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }
        if (preg_match('/works\s+out\s+to\s+Rs\.?\s*([\d,]+\.?\d*)/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        return null;
    }

    private function extractContractValueWords(string $text): ?string
    {
        if (preg_match('/\(Rupees\s+.+?Only\)/is', $text, $m)) {
            return trim(preg_replace('/\s+/', ' ', $m[0]));
        }

        return null;
    }

    private function extractEarnestMoney(string $text): ?float
    {
        // "A sum of Rs.201600 deposited as Earnest Money" (OCR may render as "Eamest")
        if (preg_match('/sum\s+of\s+Rs\.?\s*([\d,]+\.?\d*)\s+deposited\s+as\s+E[ae]?[mr]n?est\s+Money/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }
        // Fallback: Rs. amount before "deposited as E..."
        if (preg_match('/Rs\.?\s*([\d,]+\.?\d*)\s+deposited\s+as\s+E/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }
        if (preg_match('/E[ae]?[mr]n?est\s+Money\D{0,30}([\d,]+\.?\d*)/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        return null;
    }

    private function extractIrepsReferenceId(string $text): ?string
    {
        if (preg_match('/IREPS\s+reference\s+ID\s+([A-Z0-9]+)/i', $text, $m)) {
            return $m[1];
        }

        return null;
    }

    private function extractPerformanceGuarantee(string $text): ?float
    {
        // "amounting to Rs. 582439.17"
        if (preg_match('/Performance\s+Guarantee[^R]*Rs\.?\s*([\d,]+\.?\d*)/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }
        if (preg_match('/amounting\s+to\s+Rs\.?\s*([\d,]+\.?\d*)/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        return null;
    }

    private function extractNetBidValue(string $text): ?float
    {
        // "Net Bid Value | 11648783,33" — OCR may use comma as decimal separator
        if (preg_match('/Net\s+Bid\s+Value\s*[|\s]*([\d,\.]+)/i', $text, $m)) {
            $val = $m[1];
            // Handle European-style decimal: if last separator is comma with 2 decimals
            if (preg_match('/,(\d{2})$/', $val)) {
                $val = str_replace(',', '', substr($val, 0, -3)).'.'.substr($val, -2);
            } else {
                $val = str_replace(',', '', $val);
            }

            return (float) $val;
        }

        return null;
    }

    private function extractBidRatePercentage(string $text): ?float
    {
        // "rates of agency [12.98% Above]"
        if (preg_match('/([\d]+\.?\d*)\s*%\s*Above/i', $text, $m)) {
            return (float) $m[1];
        }
        if (preg_match('/Bid\s+Rate[^\d]*([\d]+\.?\d*)\s*%/i', $text, $m)) {
            return (float) $m[1];
        }

        return null;
    }

    private function extractRebateOnTotalValue(string $text): ?float
    {
        if (preg_match('/Rebate\s+on\s+Total\s+Value[^\d]*([\d,]+\.?\d*)/i', $text, $m)) {
            return (float) str_replace(',', '', $m[1]);
        }

        return 0.00;
    }

    private function extractCompletionPeriod(string $text): ?string
    {
        if (preg_match('/completed\s+within\s+(\d+\s+month[s]?)/i', $text, $m)) {
            return ucfirst($m[1]);
        }
        if (preg_match('/completion\s+(?:period|time)[^\d]*(\d+\s+(?:day|week|month|year)[s]?)/i', $text, $m)) {
            return ucfirst($m[1]);
        }

        return null;
    }

    private function extractSignedBy(string $text): ?string
    {
        // OCR: "SHIVRATAN KUMAR Sr.DEN/HWH Digitally Signed" (collapsed whitespace)
        // Use case-sensitive match so "do" (lowercase) doesn't sneak in
        if (preg_match('/([A-Z][A-Z ]{5,40}?)\s+Sr\.DEN[^\s]*\s+Digitally\s+Signed/', $text, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/([A-Z][A-Z ]{5,40}?)\s+(?:DEN|ADEN|JEN)[\/\w]+\s+Digitally\s+Signed/', $text, $m)) {
            return trim($m[1]);
        }
        // Fallback: ALL-CAPS name immediately before "Digitally Signed"
        if (preg_match('/([A-Z][A-Z ]{5,40})\s+Digitally\s+Signed/', $text, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    /**
     * Extract work items from the "Awarded Quantities And Rates" section.
     *
     * Digital (text-layer) IREPS PDFs place each item's values on a single
     * line: "<code> <qty><unit> <rate>At Par <advised value>". Scanned PDFs
     * (OCR) spread the same values across several lines. We try the digital
     * single-line parser first and fall back to the OCR multi-line parser.
     *
     * @return array<int, array<string, mixed>>
     */
    private function extractWorkItems(string $text): array
    {
        $items = $this->parseNativeWorkItems($text);

        if (! empty($items)) {
            return $items;
        }

        return $this->parseOcrWorkItems($text);
    }

    /**
     * Parse work items from a digital PDF where each item's values sit on a
     * single line. The preceding lines (after the item's serial number) form
     * the description.
     *
     * @return array<int, array<string, mixed>>
     */
    private function parseNativeWorkItems(string $text): array
    {
        $text = str_replace("\t", ' ', $text);
        $lines = explode("\n", $text);

        // Restrict to the awarded-items section to avoid false positives.
        $startIdx = 0;
        foreach ($lines as $n => $l) {
            if (stripos($l, 'Awarded Quantities') !== false) {
                $startIdx = $n;
                break;
            }
        }

        $items = [];
        $descBuffer = [];
        $currentSchedule = 'Schedule A';
        $sno = null;

        $rowCount = count($lines);
        for ($i = $startIdx; $i < $rowCount; $i++) {
            $line = trim($lines[$i]);

            if ($line === '') {
                continue;
            }

            // Schedule header: "Schedule  A- SCH-A (...)".
            if (preg_match('/^Schedule\s+([A-Z]\d?)\b/i', $line, $sm)
                && stripos($line, 'Total') === false) {
                $currentSchedule = 'Schedule '.strtoupper($sm[1]);
                $descBuffer = [];

                continue;
            }

            // Data row WITH an item code:
            //   "012011 5000cum 102.55At Par 512750.00"
            if (preg_match(
                '/^(\d{5,7})\s+([\d,]+)\s*([A-Za-z][A-Za-z.\/]*?)?\s*([\d,]+\.\d{1,2})\s*(?:At\s*Par|[\d.]+\s*%?\s*(?:Above|Below)?)\s+([\d,]+\.\d{1,2})/i',
                $line, $m
            )) {
                $items[] = [
                    'schedule_name' => $currentSchedule,
                    'item_number' => $sno ?? (string) (count($items) + 1),
                    'item_code' => $m[1],
                    'description' => $this->cleanDescription($descBuffer),
                    'quantity' => $this->cleanNumber($m[2]),
                    'unit' => $m[3] !== '' ? $this->normaliseUnitWord($m[3]) : null,
                    'escl_rate' => null,
                    'advised_value' => $this->cleanNumber($m[5]),
                    'bid_rate_unit_rate' => $this->cleanNumber($m[4]),
                    'bid_amount' => null,
                    'is_sub_item' => false,
                ];
                $descBuffer = [];
                $sno = null;

                continue;
            }

            // Data row with NO code (rates shown as "View Details"):
            //   "View Details At Par 1061535.00"
            if (preg_match('/^View\s+Details\s+(?:At\s*Par|[\d.]+\s*%?\s*(?:Above|Below)?)\s+([\d,]+\.\d{1,2})/i', $line, $m)) {
                $items[] = [
                    'schedule_name' => $currentSchedule,
                    'item_number' => $sno ?? (string) (count($items) + 1),
                    'item_code' => null,
                    'description' => $this->cleanDescription($descBuffer),
                    'quantity' => null,
                    'unit' => null,
                    'escl_rate' => null,
                    'advised_value' => $this->cleanNumber($m[1]),
                    'bid_rate_unit_rate' => null,
                    'bid_amount' => null,
                    'is_sub_item' => false,
                ];
                $descBuffer = [];
                $sno = null;

                continue;
            }

            // Standalone serial number (1–3 digits) starts a new item block.
            if (preg_match('/^(\d{1,3})$/', $line)) {
                $sno = $line;
                $descBuffer = [];

                continue;
            }

            // Skip noise / totals; otherwise accumulate as description.
            if (! $this->isNoiseLine($line) && stripos($line, 'Schedule Total') === false) {
                $descBuffer[] = $line;
            }
        }

        return $items;
    }

    /**
     * Normalise a unit word found inline (e.g. "cum", "Set", "TRM").
     */
    private function normaliseUnitWord(string $word): string
    {
        $known = $this->matchUnit($word);

        return $known ?? ucfirst(strtolower(trim($word)));
    }

    /**
     * Parse work items from OCR text where each value sits on its own line.
     *
     * @return array<int, array<string, mixed>>
     */
    private function parseOcrWorkItems(string $text): array
    {
        // Collapse to non-empty trimmed lines for predictable indexing.
        $lines = array_values(array_filter(
            array_map('trim', explode("\n", $text)),
            static fn (string $l): bool => $l !== ''
        ));
        $count = count($lines);

        $items = [];
        $descBuffer = [];
        $currentSchedule = 'Schedule A';
        $itemNumber = 0;

        for ($i = 0; $i < $count; $i++) {
            $line = $lines[$i];

            // Detect schedule header e.g. "Schedule A-SCH-A (...)", "Schedule B1-..."
            if (preg_match('/^Schedule\s+([A-Z]\d?)\b/i', $line, $sm)) {
                $currentSchedule = 'Schedule '.strtoupper($sm[1]);
                $descBuffer = [];

                continue;
            }

            // Item code = a standalone 7-digit number.
            if (preg_match('/^\d{7}$/', $line)) {
                $itemCode = $line;
                $description = $this->cleanDescription($descBuffer);
                $descBuffer = [];

                // Parse the value block that follows the code.
                $parsed = $this->parseItemValues($lines, $i + 1);
                $itemNumber++;

                $items[] = [
                    'schedule_name' => $currentSchedule,
                    'item_number' => (string) $itemNumber,
                    'item_code' => $itemCode,
                    'description' => $description,
                    'quantity' => $parsed['quantity'],
                    'unit' => $parsed['unit'],
                    'escl_rate' => null,
                    'advised_value' => $parsed['advised_value'],
                    'bid_rate_unit_rate' => $parsed['unit_rate'],
                    'bid_amount' => null,
                    'is_sub_item' => false,
                ];

                // Skip past the consumed value lines.
                $i = $parsed['next_index'] - 1;

                continue;
            }

            // Skip OCR / layout noise; otherwise accumulate as description text.
            if (! $this->isNoiseLine($line)) {
                $descBuffer[] = $line;
            }
        }

        return $items;
    }

    /**
     * Parse the quantity / unit / unit-rate / advised-value block that follows
     * an item code. Values are spread across the next several non-empty lines
     * in the order: quantity, [unit], unit rate, "At Par"/%, advised value.
     *
     * @param  array<int, string>  $lines
     * @return array{quantity: ?float, unit: ?string, unit_rate: ?float, advised_value: ?float, next_index: int}
     */
    private function parseItemValues(array $lines, int $start): array
    {
        $numbers = [];
        $unit = null;
        $count = count($lines);
        $j = $start;
        $scanned = 0;

        while ($j < $count && $scanned < 8 && count($numbers) < 3) {
            $token = $lines[$j];

            // Stop if we hit the next item code or a schedule header.
            if (preg_match('/^\d{7}$/', $token) || preg_match('/^Schedule\s+[A-Z]/i', $token)) {
                break;
            }

            // Unit token (may carry OCR prefixes like "I" or "|": ISet, |TRM, IMT).
            if ($unit === null && ($u = $this->matchUnit($token)) !== null) {
                $unit = $u;
                $j++;
                $scanned++;

                continue;
            }

            // "At Par" or a percentage bid-rate line — skip it.
            if (preg_match('/At\s*Par/i', $token) || preg_match('/^\d+(?:\.\d+)?\s*%/', $token)) {
                $j++;
                $scanned++;

                continue;
            }

            // Numeric value (strip OCR junk like trailing | ) ] } ).
            $num = $this->cleanNumber($token);
            if ($num !== null) {
                $numbers[] = $num;
                $j++;
                $scanned++;

                continue;
            }

            // Anything else marks the start of the next description block.
            break;
        }

        return [
            'quantity' => $numbers[0] ?? null,
            'unit_rate' => $numbers[1] ?? null,
            'advised_value' => $numbers[2] ?? null,
            'unit' => $unit,
            'next_index' => $j,
        ];
    }

    /**
     * Normalise a unit token, tolerating OCR noise. Returns null if not a unit.
     */
    private function matchUnit(string $token): ?string
    {
        // Strip leading OCR artefacts (I, |, l) and surrounding punctuation.
        $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $token));
        // Drop a leading bogus "I"/"L" the OCR often prepends (ISet -> SET).
        $candidates = [$clean];
        if (strlen($clean) > 2 && in_array($clean[0], ['I', 'L'], true)) {
            $candidates[] = substr($clean, 1);
        }

        $units = [
            'RM' => 'RM', 'RMT' => 'RM', 'TRM' => 'TRM', 'MT' => 'MT',
            'SET' => 'Set', 'EACH' => 'Each', 'EA' => 'Each', 'NOS' => 'Nos',
            'NO' => 'Nos', 'CUM' => 'Cum', 'JOINT' => 'Joint', 'SLEEPER' => 'Sleeper',
            'LS' => 'LS', 'KM' => 'Km', 'QUINTAL' => 'Quintal', 'TONNE' => 'Tonne',
            'SQM' => 'Sqm', 'KG' => 'Kg', 'ERC' => 'ERC',
        ];

        foreach ($candidates as $c) {
            if (isset($units[$c])) {
                return $units[$c];
            }
        }

        return null;
    }

    /**
     * Convert an OCR numeric token to a float, or null if it is not numeric.
     * Rejects tokens with more than two alphabetic characters (i.e. text).
     */
    private function cleanNumber(string $token): ?float
    {
        if (preg_match_all('/[A-Za-z]/', $token) > 2) {
            return null;
        }

        $clean = str_replace(',', '', preg_replace('/[^\d.,]/', '', $token));
        $clean = rtrim($clean, '.');

        if ($clean === '' || ! is_numeric($clean)) {
            return null;
        }

        return (float) $clean;
    }

    /**
     * Clean an accumulated description buffer into a single tidy string.
     *
     * @param  array<int, string>  $buffer
     */
    private function cleanDescription(array $buffer): ?string
    {
        if (empty($buffer)) {
            return null;
        }

        $text = implode(' ', $buffer);
        // OCR often opens descriptions with "[" — drop a single leading bracket.
        $text = ltrim($text, "[ \t");
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text) ?: null;
    }

    /**
     * Determine whether a line is OCR/layout noise (headers, URLs, scan marks)
     * that must not be treated as item description text.
     */
    private function isNoiseLine(string $line): bool
    {
        $patterns = [
            '/^ireps\.gov/i',
            '/^https?:/i',
            '/ireps\.gov\.in/i',
            '/CE\s+Scanned/i',
            '/Scanned\s+with/i',
            '/^Awarded\s+Quantities/i',
            '/^Item\s*$/i',
            '/^Sno\.?$/i',
            '/^Item\s+Desc/i',
            '/^Item\s+Code/i',
            '/^Item\s+Qty/i',
            '/^Qty\s+Unit/i',
            '/^Unit\s*$/i',
            '/^Rate\s*\(Rs\)/i',
            '/^Escl/i',
            '/^Advt\.?Value/i',
            '/^Bid\s+Amount/i',
            '/^Rate\/?$/i',
            '/^Unit\s+Rate/i',
            '/^Schedule\s+Totals/i',
            '/^Item\s+Directory/i',
            '/^View\s+Details/i',
            '/^Page\s+\d+/i',
            '/^\d{1,3}$/',              // stray page numbers
            '/^[)\]}|]+$/',            // stray bracket lines
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line)) {
                return true;
            }
        }

        return false;
    }

    private function normaliseDate(string $date): string
    {
        $date = str_replace('/', '-', $date);
        $parts = explode('-', $date);
        if (count($parts) === 3 && strlen($parts[2]) === 4) {
            return $parts[2].'-'.str_pad($parts[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        }

        return $date;
    }

    private function normaliseDatetime(string $dt): string
    {
        if (preg_match('/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})\s+(\d{1,2}:\d{2})/', $dt, $m)) {
            return $m[3].'-'.str_pad($m[2], 2, '0', STR_PAD_LEFT).'-'.str_pad($m[1], 2, '0', STR_PAD_LEFT).' '.$m[4].':00';
        }

        return $dt;
    }

    /** @return array<string, mixed> */
    private function emptyResult(): array
    {
        return [
            'railway_zone' => null,
            'division' => null,
            'office' => null,
            'letter_number' => null,
            'letter_date' => null,
            'contractor_name' => null,
            'contractor_address' => null,
            'tender_number' => null,
            'tender_closing_date' => null,
            'work_description' => null,
            'bid_id' => null,
            'bid_date' => null,
            'negotiation_bid_ids' => null,
            'contract_value' => null,
            'contract_value_words' => null,
            'earnest_money' => null,
            'ireps_reference_id' => null,
            'performance_guarantee' => null,
            'net_bid_value' => null,
            'bid_rate_percentage' => null,
            'rebate_on_total_value' => null,
            'completion_period' => null,
            'signed_by' => null,
            'work_items' => [],
        ];
    }
}
