<div>
    @foreach ($this->teams as $team)
        <div class="mb-4">
            <flux:card>
                <div class="flex justify-between items-center">
                    <flux:heading>
                        {{ $team->name }}
                    </flux:heading>
                    <div class="flex items-center gap-2">
                        <flux:text>Next Retro </flux:text>
                        <flux:badge :color="$team->nextRetroDueBy()->isPast() ? 'red' : 'green'">
                            {{ $team->nextRetroDueBy()->isPast() ? 'Overdue ' . $team->nextRetroDueBy()->diffForHumans() : $team->nextRetroDueBy()->diffForHumans() }}
                        </flux:badge>
                    </div>
                </div>
            </flux:card>
        </div>
    @endforeach
</div>
