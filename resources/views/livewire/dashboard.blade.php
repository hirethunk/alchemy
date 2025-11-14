<div>
    @foreach ($this->teams as $team)
        @php 
            $lastRetroDate = $team->last_retro_date;
            $nextRetroDate = $lastRetroDate?->addDays($team->retro_cycle_in_days);
            $isOverdue = $nextRetroDate?->isPast();
        @endphp
        <div class="mb-4">
            <flux:card>
                <div class="flex justify-between items-start">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-2">
                        <flux:heading size="lg">
                            {{ $team->name }}
                        </flux:heading>
                        <flux:button size="sm" variant="subtle" color="zinc" size="xs" icon="wrench" href="{{ route('team.view', $team) }}">Manage</flux:button>
                    </div>
                    <div class="flex flex-col md:flex-row items-end md:items-center gap-2">
                        <div class="flex items-center text-xs gap-2">
                            @if ($isOverdue)
                                <flux:badge size="sm" :color="$isOverdue ? 'red' : 'zinc'">
                                    Next Retro due {{ $nextRetroDate->diffForHumans() }}
                                </flux:badge>
                            @endif
                        </div>
                        <flux:button variant="primary" size="sm" icon="plus" wire:click="createRetrospective({{ $team->id }})">Start a Retro</flux:button>
                    </div>
                </div>
                @if ($team->retrospectives->isNotEmpty())
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Recent Retrospectives</flux:table.column>
                            <flux:table.column></flux:table.column>
                            <flux:table.column></flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach ($team->retrospectives as $retrospective)
                                <flux:table.row>
                                    <flux:table.cell>{{ $retrospective->date->format('M d, Y') }}</flux:table.cell>
                                    <flux:table.cell>
                                        @if ($retrospective->tasks->filter(fn($task) => $task->status !== 'completed')->isNotEmpty())
                                            <flux:badge size="sm" color="yellow">
                                                {{ $retrospective->tasks->filter(fn($task) => $task->status !== 'completed')->count() }} outstanding tasks
                                            </flux:badge>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell class="text-right">
                                        <flux:button size="sm" variant="ghost" icon="eye" color="zinc" href="{{ route('retrospective.view', $retrospective) }}">View</flux:button>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @endif
            </flux:card>
        </div>
    @endforeach

    <flux:card>
        <flux:button size="sm" variant="outline" color="zinc" href="{{ route('team.create') }}">Create Team</flux:button>

    </flux:card>
</div>
