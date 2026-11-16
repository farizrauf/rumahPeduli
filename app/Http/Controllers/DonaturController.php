<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use Illuminate\Http\Request;

class DonaturController extends Controller
{
    public function dashboard(Request $request)
    {
        $donations = $request->user()->donasi()->with(['kampanye.panti'])->latest()->take(5)->get();

        return view('donatur.dashboard', [
            'donations' => $donations,
            'total' => $request->user()->donasi()->where('status', 'verified')->sum('nominal'),
            'count' => $request->user()->donasi()->where('status', 'verified')->count(),
        ]);
    }

    public function donations(Request $request)
    {
        return view('donatur.donations', [
            'donations' => Donasi::with(['kampanye.panti'])->where('user_id', $request->user()->id)->latest()->paginate(12),
        ]);
    }
}
