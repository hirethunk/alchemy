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
                    <div class="flex flex-col gap-2">
                        <flux:heading size="lg">
                            {{ $team->name }}
                        </flux:heading>
                        <flux:button size="sm" inset variant="subtle" color="zinc" size="xs" icon="wrench" href="{{ route('team.view', $team) }}">Manage</flux:button>
                    </div>
                    <div class="flex flex-col gap-2 items-end">
                        <flux:button variant="primary" icon="plus" wire:click="createRetrospective({{ $team->id }})">Start a Retro</flux:button>
                        <div class="flex items-center gap-2">
                            @if ($lastRetroDate !== null)
                                <flux:text>Next Retro due</flux:text>
                                <flux:badge size="xs" :color="$isOverdue ? 'red' : 'green'">
                                    {{ $nextRetroDate->diffForHumans() }}
                                </flux:badge>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($team->retrospectives->isNotEmpty())
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Recent Retrospectives</flux:table.column>
                            <flux:table.column></flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach ($team->retrospectives as $retrospective)
                                <flux:table.row>
                                    <flux:table.cell>{{ $retrospective->date->format('M d, Y') }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:button size="sm" variant="outline" color="zinc">View</flux:button>
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
