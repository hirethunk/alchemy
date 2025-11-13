<?php

namespace App\Livewire;

use Flux\Flux;
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

    public function mount(Retrospective $retrospective)
    {
        if ($retrospective === null) {
            $this->redirect(route('dashboard'));
            return;
        }

        $this->retrospective = $retrospective;
        $this->date = $retrospective->date;
    }

    public function setEntryType(string $type)
    {
        $this->entry_type = $type;
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

        $this->retrospective->entries()->create([
            'type' => $this->entry_type,
            'content' => $this->entry_content,
            'team_id' => $this->retrospective->team_id,
            'retrospective_id' => $this->retrospective->id,
        ]);

        Flux::modals()->close();

        Flux::toast(
            variant: 'success',
            heading: $this->entry_type === 'what_went_well' ? 'Positive added' : 'Improvement added',
            text: $this->entry_content,
        );

        $this->entry_content = '';
    }

    public function render()
    {
        return view('livewire.restrospective-page');
    }
}
