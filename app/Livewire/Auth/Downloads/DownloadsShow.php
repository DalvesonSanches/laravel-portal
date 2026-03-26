<?php

namespace App\Livewire\Auth\Downloads;

use Livewire\Component;
use App\Models\Downloads;
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')]

class DownloadsShow extends Component
{
    public Downloads $downloads;

    public function render()
    {
        return view('livewire.auth.downloads.downloads-show');
    }
}
