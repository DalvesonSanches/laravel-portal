<?php

namespace App\Livewire\Auth\Profile;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')]

class Profile extends Component
{
    public function render()
    {
        return view('livewire.auth.profile.profile');
    }
}
