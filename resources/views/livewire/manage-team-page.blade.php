<div class="flex flex-col gap-4">
    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Outstanding tasks</flux:table.column>
                <flux:table.column></flux:table.column>
                <flux:table.column>Created on</flux:table.column>
                <flux:table.column>Assigned to</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            @foreach ($this->tasks->where('status', '!=', 'completed') as $task)
                <flux:table.row>
                    <flux:table.cell>
                        {{ $task->title }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $task->description }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:link href="{{ route('retrospective.view', $task->retrospective) }}">
                            {{ $task->retrospective->date->format('M d, Y') }}
                        </flux:link>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $task->user?->name }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:select variant="listbox" wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)">
                            <flux:select.option value="not_started" :selected="($task->status ?? 'not_started') === 'not_started'">
                                <div class="flex items-center gap-2">
                                    <flux:icon variant="solid" name="stop" class="text-slate-500" /> Not started
                                </div>
                            </flux:select.option>
                            <flux:select.option value="in_progress" :selected="($task->status ?? 'not_started') === 'in_progress'">
                                <div class="flex items-center gap-2">
                                    <flux:icon variant="solid" name="play" class="text-yellow-500" /> In progress
                                </div>
                            </flux:select.option>
                            <flux:select.option value="completed" :selected="($task->status ?? 'not_started') === 'completed'">
                                <div class="flex items-center gap-2">
                                    <flux:icon variant="solid" name="check-circle" class="text-green-500" /> Completed
                                </div>
                            </flux:select.option>
                        </flux:select>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table>
    </flux:card>

    <flux:card class="flex flex-col gap-4">
        <flux:input type="text" wire:model.live="name" label="Team Name" />
        <flux:input wire:model.live="retro_cycle_in_days" label="How often should this team hold retrospectives (in days)?" />
        @if ($this->isDirty)
            <flux:button variant="primary" class="w-full" wire:click="updateTeam">Update Team</flux:button>
        @endif
    </flux:card>

    <flux:toast />
</div>
