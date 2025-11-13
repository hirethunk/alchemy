<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

class CreateTeamPage extends Component
{
    public string $name = '';
    public int $retro_cycle_in_days = 14;

    #[Computed]
    public function user()
    {
        return auth()->user();
    }

    public function createTeam()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'retro_cycle_in_days' => 'required|integer|min:1',
        ]);

        $this->user->teams()->create([
            'name' => $this->name,
            'retro_cycle_in_days' => $this->retro_cycle_in_days,
        ]);

        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.create-team-page');
    }
}
