<?php

namespace App\Livewire\Auth\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')]

class UserShow extends Component
{

    public User $user;

    public function render()
    {
        return view('livewire.auth.users.user-show');
    }
}
