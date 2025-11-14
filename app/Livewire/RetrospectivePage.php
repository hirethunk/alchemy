<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Task;
use Livewire\Component;
use App\Models\Retrospective;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;

class RetrospectivePage extends Component
{
    public Retrospective $retrospective;

    public Carbon $date;

    public string $entry_type = 'what_went_well';
    public string $entry_content = '';
    public ?int $editing_entry_id = null;

    public string $task_title = '';
    public string $task_description = '';
    public ?int $task_user_id = null;
    public ?int $editing_task_id = null;

    public string $truth_description = '';
    public ?int $editing_truth_id = null;

    #[Computed]
    public function entries()
    {
        return $this->retrospective->entries;
    }

    #[Computed]
    public function whatWentWellEntries()
    {
        return $this->entries->where('type', 'what_went_well');
    }

    #[Computed]
    public function whatCouldImproveEntries()
    {
        return $this->entries->where('type', 'what_could_improve');
    }

    #[Computed]
    public function tasks()
    {
        return $this->retrospective->tasks;
    }

    #[Computed]
    public function truths()
    {
        return $this->retrospective->truths;
    }

    #[Computed]
    public function teamMembers()
    {
        return $this->retrospective->team->users;
    }

    #[Computed]
    public function teamMemberOptions()
    {
        return $this->teamMembers->map(fn($user) => [
            'value' => $user->id,
            'label' => $user->name,
        ])->toArray();
    }

    public function mount(Retrospective $retrospective)
    {
        $this->retrospective = $retrospective;
        $this->date = $retrospective->date;
    }

    public function setEntryType(string $type)
    {
        $this->entry_type = $type;
        $this->editing_entry_id = null;
        $this->entry_content = '';
    }

    public function editEntry(int $entry_id)
    {
        $entry = $this->entries->firstWhere('id', $entry_id);

        if (!$entry) {
            return;
        }

        $this->editing_entry_id = $entry_id;
        $this->entry_type = $entry->type;
        $this->entry_content = $entry->content;
    }

    public function updateDate()
    {
        $this->validate([
            'date' => 'required|date',
        ]);

        $this->retrospective->update([
            'date' => $this->date,
        ]);

        Flux::modals()->close();

        Flux::toast(
            variant: 'success',
            text: 'Retrospective date updated to ' . $this->date->format('M d, Y'),
        );
    }

    public function addEntry()
    {
        $this->validate([
            'entry_content' => 'required|string|max:255',
        ]);

        if ($this->editing_entry_id) {
            $entry = $this->entries->firstWhere('id', $this->editing_entry_id);
            $entry->update([
                'content' => $this->entry_content,
            ]);
            $message = 'Entry updated';
        } else {
            $this->retrospective->entries()->create([
                'type' => $this->entry_type,
                'content' => $this->entry_content,
                'team_id' => $this->retrospective->team_id,
                'retrospective_id' => $this->retrospective->id,
            ]);
            $message = $this->entry_type === 'what_went_well' ? 'Positive added' : 'Improvement added';
        }

        Flux::modals()->close();

        Flux::toast(
            variant: 'success',
            heading: $message,
            text: $this->entry_content,
        );

        $this->entry_content = '';
        $this->editing_entry_id = null;
    }

    public function editTask(int $task_id)
    {
        $task = $this->tasks->firstWhere('id', $task_id);

        if (!$task) {
            return;
        }

        $this->editing_task_id = $task_id;
        $this->task_title = $task->title;
        $this->task_description = $task->description;
        $this->task_user_id = $task->user_id;
    }

    public function resetTaskForm()
    {
        $this->editing_task_id = null;
        $this->task_title = '';
        $this->task_description = '';
        $this->task_user_id = null;
    }

    public function resetTruthForm()
    {
        $this->editing_truth_id = null;
        $this->truth_description = '';
    }

    public function addTask()
    {
        $this->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
        ]);

        if ($this->editing_task_id) {
            $task = $this->tasks->firstWhere('id', $this->editing_task_id);
            $task->update([
                'title' => $this->task_title,
                'description' => $this->task_description,
                'user_id' => $this->task_user_id,
            ]);
            $message = 'Task updated';
        } else {
            $this->retrospective->tasks()->create([
                'title' => $this->task_title,
                'description' => $this->task_description,
                'user_id' => $this->task_user_id,
                'team_id' => $this->retrospective->team_id,
                'retrospective_id' => $this->retrospective->id,
            ]);
            $message = 'Task added';
        }

        Flux::modals()->close();

        Flux::toast(
            variant: 'success',
            heading: $message,
            text: $this->task_title,
        );

        $this->task_title = '';
        $this->task_description = '';
        $this->task_user_id = null;
        $this->editing_task_id = null;
    }

    public function editTruth(int $truth_id)
    {
        $truth = $this->truths->firstWhere('id', $truth_id);

        if (!$truth) {
            return;
        }

        $this->editing_truth_id = $truth_id;
        $this->truth_description = $truth->description;
    }

    public function addTruth()
    {
        $this->validate([
            'truth_description' => 'required|string',
        ]);

        if ($this->editing_truth_id) {
            $truth = $this->truths->firstWhere('id', $this->editing_truth_id);
            $truth->update([
                'description' => $this->truth_description,
            ]);
            $message = 'Truth updated';
        } else {
            $this->retrospective->truths()->create([
                'description' => $this->truth_description,
                'team_id' => $this->retrospective->team_id,
                'retrospective_id' => $this->retrospective->id,
            ]);
            $message = 'Truth added';
        }

        Flux::modals()->close();

        Flux::toast(
            variant: 'success',
            heading: $message,
            text: $this->truth_description,
        );

        $this->truth_description = '';
        $this->editing_truth_id = null;
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

        $this->retrospective->refresh();
        unset($this->tasks);

        Flux::toast(
            variant: 'success',
            text: 'Task status updated',
        );
    }

    public function completeTask(int $task_id)
    {
        $task = $this->tasks->firstWhere('id', $task_id);

        if (!$task) {
            return;
        }

        $task->update([
            'completed' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.restrospective-page');
    }
}
