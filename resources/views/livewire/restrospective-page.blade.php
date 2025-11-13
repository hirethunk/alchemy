<div class="flex flex-col gap-4">
    <flux:card>
        <div class="flex justify-between items-center">
            <flux:heading size="lg">
                {{ $this->retrospective->team->name }}
            </flux:heading>
            <flux:heading size="lg" class="flex items-center gap-2">
                {{ $this->retrospective->date->format('M d, Y') }}
                <flux:modal.trigger name="edit-date">
                    <flux:button variant="subtle" icon="pencil" size="xs"></flux:button>
                </flux:modal.trigger>
            </flux:heading>
        </div>
    </flux:card>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>What went well?</flux:table.column>
            </flux:table.columns>
            
            @foreach ($this->whatWentWellEntries as $entry)
                <flux:table.row>
                    <flux:table.cell>
                        {{ $entry->content }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach

            <flux:table.row>
                <flux:table.cell>
                    <flux:modal.trigger name="add-entry">
                        <flux:button variant="primary" size="sm" icon="plus" wire:click="setEntryType('what_went_well')">Add</flux:button>
                    </flux:modal.trigger>
                </flux:table.cell>
            </flux:table.row>
        </flux:table>
    </flux:card>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>What could improve?</flux:table.column>
            </flux:table.columns>
            
            @foreach ($this->whatCouldImproveEntries as $entry)
                <flux:table.row>
                    <flux:table.cell>
                        {{ $entry->content }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach

            <flux:table.row>
                <flux:table.cell>
                    <flux:modal.trigger name="add-entry">
                        <flux:button variant="primary" size="sm" icon="plus" wire:click="setEntryType('what_could_improve')">Add</flux:button>
                    </flux:modal.trigger>
                </flux:table.cell>
            </flux:table.row>
        </flux:table>
    </flux:card>

    <flux:modal name="edit-date" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit retrospective date</flux:heading>
            </div>

            <flux:date-picker wire:model="date" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="updateDate">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="add-entry" variant="flyout">
        <div class="space-y-6">
            <flux:textarea label="{{ $entry_type === 'what_went_well' ? 'What went well?' : 'What could improve?' }}" wire:model="entry_content" placeholder="Communication with the support team has felt much better." />
            <div class="flex">
                <flux:spacer />
                <flux:button variant="primary" wire:click="addEntry">Add</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:toast />
</div>
