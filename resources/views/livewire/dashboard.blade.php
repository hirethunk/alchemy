<div>
    @foreach ($this->teams as $team)
        @php 
            $lastRetroDate = $team->lastRetroDate();
            $nextRetroDate = $lastRetroDate->addDays($team->retro_cycle_in_days);
            $isOverdue = $nextRetroDate->isPast();
        @endphp
        <div class="mb-4">
            <flux:card>
                <div class="flex justify-between items-center">
                    <flux:heading>
                        {{ $team->name }}
                    </flux:heading>
                    <div class="flex items-center gap-2">
                        <flux:text>Next Retro due</flux:text>
                        <flux:badge :color="$isOverdue ? 'red' : 'green'">
                            {{ $nextRetroDate->diffForHumans() }}
                        </flux:badge>
                    </div>
                </div>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Previous Retrospectives</flux:table.column>
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
            </flux:card>
        </div>
    @endforeach
</div>
