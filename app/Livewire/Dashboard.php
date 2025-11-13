<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    #[Computed]
    public function user()
    {
        return auth()->user();
    }

    #[Computed]
    public function teams()
    {
        return auth()->user()->teams;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
