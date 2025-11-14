<div class="flex flex-col gap-4">
    <x-tasks-table :tasks="$this->outstandingTasks" :teamMemberOptions="$this->teamMemberOptions" />

    <x-truths-viewer :truths="$this->truths" />

    <flux:card class="flex flex-col gap-4">
        <flux:input type="text" wire:model.live="name" label="Team Name" />
        <flux:input wire:model.live="retro_cycle_in_days" label="How often should this team hold retrospectives (in days)?" />
        @if ($this->isDirty)
            <flux:button variant="primary" class="w-full" wire:click="updateTeam">Update Team</flux:button>
        @endif
    </flux:card>

    <flux:toast />
</div>
