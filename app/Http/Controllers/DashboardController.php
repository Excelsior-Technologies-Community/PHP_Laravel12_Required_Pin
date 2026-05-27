<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginActivity;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // CHECK PIN VERIFIED
        if (!session('pin_verified')) {
            return redirect()->route('pin.required');
        }

        $query = LoginActivity::where('user_id', auth()->id());

        // GLOBAL SEARCH
        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%")
                    ->orWhere('login_at', 'like', "%{$search}%");

            });
        }

        $logs = $query->latest()->paginate(3);

        return view('dashboard', compact('logs'));
    }
}