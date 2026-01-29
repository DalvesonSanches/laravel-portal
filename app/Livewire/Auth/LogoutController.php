<?php

namespace App\Livewire\Auth; // O namespace DEVE ser igual ao caminho da pasta

use App\Http\Controllers\Controller; // Importa a base dos controllers
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}