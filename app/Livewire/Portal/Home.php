<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.portal')]

class Home extends Component
{
    public function render()
    {
        return view('livewire.portal.home');
    }
}