<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Retrospective;
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
        return auth()->user()->teams()
            ->with('retrospectives')->orderBy('last_retro_date', 'desc')->limit(5)
            ->with('tasks')->where('status', '!=', 'completed')
            ->get();
    }

    public function createRetrospective(int $team_id)
    {
        $team = $this->teams->firstWhere('id', $team_id);
        $retrospective = Retrospective::fromTemplate($team);

        $this->redirect(route('retrospective.view', $retrospective));
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
