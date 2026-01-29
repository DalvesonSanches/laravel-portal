<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.auth.dashboard');
    }
}
