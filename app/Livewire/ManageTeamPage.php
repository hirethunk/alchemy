<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ManageTeamPage extends Component
{
    public Team $team;

    public string $name;
    public int $retro_cycle_in_days;

    #[Computed]
    public function user()
    {
        return auth()->user();
    }

    #[Computed]
    public function users()
    {
        return $this->team->users;
    }

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->name = $team->name;
        $this->retro_cycle_in_days = $team->retro_cycle_in_days;
    }

    public function updateTeam()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'retro_cycle_in_days' => 'required|integer|min:1',
        ]);

        $this->team->update([
            'name' => $this->name,
            'retro_cycle_in_days' => $this->retro_cycle_in_days,
        ]);

        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.manage-team-page');
    }
}
