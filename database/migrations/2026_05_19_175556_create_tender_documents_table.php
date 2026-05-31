<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // File info
            $table->string('original_filename');
            $table->string('file_path');
            $table->enum('status', ['uploaded', 'extracted', 'confirmed'])->default('uploaded');

            // Railway / Office info
            $table->string('railway_zone')->nullable();
            $table->string('division')->nullable();
            $table->string('office')->nullable();

            // Letter info
            $table->string('letter_number')->nullable();
            $table->date('letter_date')->nullable();

            // Contractor info
            $table->string('contractor_name')->nullable();
            $table->text('contractor_address')->nullable();

            // Tender / Contract info
            $table->string('tender_number')->nullable();
            $table->dateTime('tender_closing_date')->nullable();
            $table->text('work_description')->nullable();
            $table->string('bid_id')->nullable();
            $table->dateTime('bid_date')->nullable();
            $table->string('negotiation_bid_ids')->nullable();

            // Financial info
            $table->decimal('contract_value', 15, 2)->nullable();
            $table->text('contract_value_words')->nullable();
            $table->decimal('earnest_money', 15, 2)->nullable();
            $table->string('ireps_reference_id')->nullable();
            $table->decimal('performance_guarantee', 15, 2)->nullable();
            $table->decimal('net_bid_value', 15, 2)->nullable();
            $table->decimal('bid_rate_percentage', 8, 2)->nullable();
            $table->decimal('rebate_on_total_value', 15, 2)->nullable();
            $table->decimal('total_advertised_value', 15, 2)->nullable();

            // Work info
            $table->string('completion_period')->nullable();
            $table->string('signed_by')->nullable();

            // Meta
            $table->text('notes')->nullable();
            $table->timestamp('extracted_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_documents');
    }
};
