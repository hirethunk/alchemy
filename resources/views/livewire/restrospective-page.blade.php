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

    <div class="bg-blue-100 dark:bg-blue-900 rounded-lg">
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
    </div>

    <div class="bg-red-100 dark:bg-red-900 rounded-lg">
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
    </div>

    <div class="rounded-lg">
        <flux:card>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>What specific actions should we take after this?</flux:table.column>
                    <flux:table.column></flux:table.column>
                    <flux:table.column>Assigned to</flux:table.column>
                </flux:table.columns>

                @foreach ($this->tasks as $task)
                    <flux:table.row>
                        <flux:table.cell>
                            {{ $task->title }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $task->description }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $task->user?->name }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach

                <flux:table.row>
                    <flux:table.cell>
                        <flux:modal.trigger name="add-task">
                            <flux:button variant="primary" size="sm" icon="plus" wire:click="setEntryType('what_could_improve')">Add</flux:button>
                        </flux:modal.trigger>
                    </flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                </flux:table.row>
            </flux:table>
        </flux:card>
    </div>

    <div class="bg-green-100 dark:bg-green-900 rounded-lg">
        <flux:card>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Which evergreen truths revealed themselves during this retrospective?</flux:table.column>
                    <flux:table.column></flux:table.column>
                    <flux:table.column>Assigned to</flux:table.column>
                </flux:table.columns>

                @foreach ($this->truths as $truth)
                    <flux:table.row>
                        <flux:table.cell>
                            {{ $truth->description }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach

                <flux:table.row>
                    <flux:table.cell>
                        <flux:modal.trigger name="add-truth">
                            <flux:button variant="primary" size="sm" icon="plus">Add</flux:button>
                        </flux:modal.trigger>
                    </flux:table.cell>
                </flux:table.row>
            </flux:table>
        </flux:card>
    </div>

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

    <flux:modal name="add-task" variant="flyout">
        <div class="space-y-6">
            <flux:input label="Title" wire:model="task_title" placeholder="Title" />
            <flux:textarea label="Description" wire:model="task_description" placeholder="Description" />
            <flux:select label="Assign to (optional)" wire:model="task_user_id">
                <flux:select.option value="" selected class="placeholder">Select a user</flux:select.option>
                @foreach ($this->teamMemberOptions as $option)
                    <flux:select.option value="{{ $option['value'] }}" label="{{ $option['label'] }}" />
                @endforeach
            </flux:select>
            <div class="flex">
                <flux:spacer />
                <flux:button variant="primary" wire:click="addTask">Add</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="add-truth" variant="flyout">
        <div class="space-y-6">
            <flux:textarea label="Truth" wire:model="truth_description" placeholder="Truth" />
            <div class="flex">
                <flux:spacer />
                <flux:button variant="primary" wire:click="addTruth">Add</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:toast />
</div>
