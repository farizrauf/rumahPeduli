<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use Illuminate\Http\Request;

class DonaturController extends Controller
{
    public function dashboard(Request $request)
    {
        $donatur  = $request->user();
        $donations = $donatur->donasi()->with(['kampanye.panti'])->latest()->take(5)->get();
        $total    = $donatur->donasi()->where('status', 'verified')->sum('nominal');
        $count    = $donatur->donasi()->where('status', 'verified')->count();

        return view('donatur.dashboard', compact('donations', 'total', 'count'));
    }

    public function donations(Request $request)
    {
        $donations = Donasi::with(['kampanye.panti'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('donatur.donations', compact('donations'));
    }
}
