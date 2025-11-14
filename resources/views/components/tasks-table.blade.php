@props(['tasks', 'teamMemberOptions', 'onRetrospectivePage' => false])

<div class="rounded-lg">
    <flux:card>
        <div class="overflow-x-auto">
        <flux:table>
            <flux:table.columns>
                <!-- if we're on the retrospecitve page -->
                @if ($onRetrospectivePage)
                    <flux:table.column class="!whitespace-normal !break-words">What actions should we take after this?</flux:table.column>
                @else
                    <flux:table.column class="!whitespace-normal !break-words">Task</flux:table.column>
                @endif

                @if (!$onRetrospectivePage)
                    <flux:table.column class="!whitespace-normal !break-words">Created</flux:table.column>
                @endif
                <flux:table.column class="!whitespace-normal !break-words">Assigned to</flux:table.column>
                <flux:table.column class="!whitespace-normal !break-words">Status</flux:table.column>
                @if ($onRetrospectivePage)
                    <flux:table.column></flux:table.column>
                @endif
            </flux:table.columns>

            @foreach ($tasks as $task)
                <flux:table.row wire:key="task-{{ $task->id }}">
                    <flux:table.cell class="!whitespace-normal !break-words max-w-xs">
                        {{ $task->description }}
                    </flux:table.cell>
                    @if (!$onRetrospectivePage)
                        <flux:table.cell >
                            <flux:link href="{{ route('retrospective.view', $task->retrospective) }}">{{ $task->created_at->format('M d, Y') }}</flux:link>
                        </flux:table.cell>
                    @endif
                    <flux:table.cell class="shrink-0 w-48 !whitespace-nowrap">
                        <flux:select variant="listbox" wire:change="updateTaskUser({{ $task->id }}, $event.target.value)">
                            <flux:select.option value="" :selected="!$task->user_id">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex size-6 shrink-0 overflow-hidden rounded-md">
                                        <span class="flex h-full w-full items-center justify-center rounded-md bg-neutral-200 text-xs dark:bg-neutral-700">
                                            ?
                                        </span>
                                    </span>
                                </div>
                            </flux:select.option>
                            @foreach ($teamMemberOptions as $option)
                                @php
                                    $user = \App\Models\User::find($option['value']);
                                @endphp
                                <flux:select.option wire:key="task-{{ $task->id }}-user-{{ $option['value'] }}" value="{{ $option['value'] }}" :selected="$task->user_id == $option['value']">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex size-6 shrink-0 overflow-hidden rounded-md text-xs">
                                            <img
                                                src="{{ $user->gravatar(48) }}"
                                                alt="{{ $user->name }}"
                                                class="h-full w-full object-cover text-xs"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                            />
                                            <span class="hidden h-full w-full items-center justify-center rounded-md bg-neutral-200 text-xs dark:bg-neutral-700 dark:text-white">
                                                {{ $user->initials() }}
                                            </span>
                                        </span>
                                        {{ $option['label'] }}
                                    </div>
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:table.cell>
                    <flux:table.cell class="shrink-0 w-40 !whitespace-nowrap">
                        <flux:select variant="listbox" wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)">
                            <flux:select.option value="not_started" :selected="($task->status ?? 'not_started') === 'not_started'">
                                <flux:icon variant="solid" name="stop" class="text-slate-500" />
                            </flux:select.option>
                            <flux:select.option value="in_progress" :selected="($task->status ?? 'not_started') === 'in_progress'">
                                <flux:icon variant="solid" name="play" class="text-yellow-500" />
                            </flux:select.option>
                            <flux:select.option value="completed" :selected="($task->status ?? 'not_started') === 'completed'">
                                <flux:icon variant="solid" name="check-circle" class="text-green-500" />
                            </flux:select.option>
                        </flux:select>
                    </flux:table.cell>
                    @if ($onRetrospectivePage)
                        <flux:table.cell class="text-right shrink-0 w-20 !whitespace-nowrap">
                            <flux:modal.trigger name="add-task">
                                <flux:button variant="ghost" size="sm" icon="pencil" wire:click="editTask({{ $task->id }})"></flux:button>
                            </flux:modal.trigger>
                        </flux:table.cell>
                    @endif
                </flux:table.row>
            @endforeach
        </flux:table>

    </div>

        <div class="flex mt-4">
            @if ($onRetrospectivePage)
                <flux:modal.trigger name="add-task">
                    <flux:button size="sm" icon="plus" wire:click="resetTaskForm">Add</flux:button>
                </flux:modal.trigger>
            @endif
        </div>
    </flux:card>
</div>
