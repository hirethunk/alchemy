<?php

namespace App\Livewire;

use Livewire\Component;

class ManageTeamPage extends Component
{
    public Team $team;

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
    }

    public function render()
    {
        return view('livewire.manage-team-page');
    }
}
