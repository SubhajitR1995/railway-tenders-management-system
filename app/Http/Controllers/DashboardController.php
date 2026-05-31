<?php

namespace App\Http\Controllers;

use App\Models\TenderDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total' => TenderDocument::where('user_id', $user->id)->count(),
            'confirmed' => TenderDocument::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'extracted' => TenderDocument::where('user_id', $user->id)->where('status', 'extracted')->count(),
            'uploaded' => TenderDocument::where('user_id', $user->id)->where('status', 'uploaded')->count(),
        ];

        $recentDocuments = TenderDocument::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentDocuments'));
    }
}
