<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Task;
use App\Models\Team;
use Livewire\Component;
use Livewire\Attributes\Computed;

class ManageTeamPage extends Component
{
    public Team $team;

    public string $name;
    public ?int $retro_cycle_in_days;

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

    #[Computed]
    public function retrospectives()
    {
        return $this->team->retrospectives()->orderBy('date', 'desc')->get();
    }

    #[Computed]
    public function tasks()
    {
        return $this->team->tasks()->with('retrospective')->get();
    }

    #[Computed]
    public function outstandingTasks()
    {
        return $this->tasks->where('status', '!=', 'completed');
    }

    #[Computed]
    public function truths()
    {
        return $this->team->truths;
    }

    #[Computed]
    public function isDirty()
    {
        return $this->name !== $this->team->name || (int) $this->retro_cycle_in_days !== $this->team->retro_cycle_in_days;
    }

    #[Computed]
    public function teamMemberOptions()
    {
        return $this->team->users->map(fn($user) => [
            'value' => $user->id,
            'label' => $user->name,
        ])->toArray();
    }

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->name = $team->name;
        $this->retro_cycle_in_days = $team->retro_cycle_in_days;
    }

    public function updateTaskStatus(int $task_id, string $status)
    {
        $task = Task::find($task_id);

        if (!$task) {
            Flux::toast(
                variant: 'danger',
                text: 'Task not found',
            );
            return;
        }

        $task->status = $status;
        $task->save();

        $this->team->refresh();
        unset($this->tasks);
        unset($this->outstandingTasks);

        Flux::toast(
            variant: 'success',
            text: 'Task status updated',
        );
    }

    public function updateTaskUser(int $task_id, $user_id)
    {
        $task = Task::find($task_id);

        if (!$task) {
            Flux::toast(
                variant: 'danger',
                text: 'Task not found',
            );
            return;
        }

        $task->user_id = $user_id ?: null;
        $task->save();

        $this->team->refresh();
        unset($this->tasks);
        unset($this->outstandingTasks);

        Flux::toast(
            variant: 'success',
            text: 'Task assignee updated',
        );
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

        Flux::toast(
            variant: 'success',
            text: 'Team updated',
        );
    }

    public function render()
    {
        return view('livewire.manage-team-page');
    }
}
