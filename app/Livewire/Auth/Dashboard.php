<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.auth')]

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.auth.dashboard');
    }
}
