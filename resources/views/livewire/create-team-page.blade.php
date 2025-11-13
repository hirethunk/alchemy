<div>
    <flux:card class="flex flex-col gap-4">
        <flux:input type="text" wire:model="name" label="Team Name" />
        <flux:input wire:model="retro_cycle_in_days" label="How often should this team hold retrospectives (in days)?" />
        <flux:button variant="primary" class="w-full" wire:click="createTeam">Create Team</flux:button>
    </flux:card>
</div>
