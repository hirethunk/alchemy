@props(['truths'])

@php
    // Shuffle truths for random order
    $shuffledTruths = $truths->shuffle();

    // Get random background image
    $truthsDir = public_path('truths');
    $images = [];
    if (file_exists($truthsDir)) {
        $files = glob($truthsDir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        $images = array_map(fn($file) => '/truths/' . basename($file), $files);
    }
    $randomImage = !empty($images) ? $images[array_rand($images)] : '';

    // Generate random positions for each truth (20-80% range)
    $positions = [];
    foreach ($shuffledTruths as $index => $truth) {
        $positions[$index] = [
            'x' => rand(20, 80),
            'y' => rand(20, 80),
        ];
    }
@endphp

<div x-data="{
        whimsical: false,
        currentTruthIndex: 0,
        backgroundImage: '{{ $randomImage }}',
        startAnimation() {
            if (this.whimsical) {
                // Play audio with error handling
                const playPromise = this.$refs.music.play();
                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        console.log('Birds chirping started');
                    }).catch(error => {
                        console.error('Audio playback failed:', error);
                    });
                }
                this.cycleTruths();
            } else {
                this.$refs.music.pause();
                this.$refs.music.currentTime = 0;
            }
        },
        cycleTruths() {
            if (!this.whimsical) return;

            setInterval(() => {
                if (this.whimsical) {
                    this.currentTruthIndex = (this.currentTruthIndex + 1) % {{ $shuffledTruths->count() }};
                }
            }, 8000); // 8 seconds between truths
        }
     }"
     x-init="$watch('whimsical', value => startAnimation())"
     class="relative overflow-hidden rounded-lg transition-all duration-500"
     :class="whimsical ? 'min-h-[500px]' : ''">

    <!-- Toggle Button -->
    <div class="absolute top-4 right-4 z-20">
        <button @click="whimsical = !whimsical"
                class="px-4 py-2 rounded-lg font-semibold transition-all duration-300 shadow-lg"
                :class="whimsical ? 'bg-white text-purple-600 hover:scale-110 animate-pulse' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-900 dark:text-white hover:bg-zinc-300 dark:hover:bg-zinc-600'">
            <span x-show="!whimsical" x-transition>✨ Bask in your hard earned wisdom ✨</span>
            <span x-show="whimsical" x-transition>📋 Just give it to me straight</span>
        </button>
    </div>

    <!-- Nature Sounds -->
    <audio x-ref="music" loop preload="auto">
        <source src="/birds.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <!-- Pastoral Landscape Background -->
    <div x-show="whimsical"
         x-transition:enter="transition-opacity duration-1000"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="absolute inset-0 bg-cover bg-center"
         :style="`background-image: url('${backgroundImage}'); filter: brightness(0.85);`">
        <!-- Overlay for better text readability -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/30"></div>
    </div>

    <!-- Business View -->
    <div x-show="!whimsical"
         x-transition:enter="transition-opacity duration-500"
         x-transition:leave="transition-opacity duration-500"
         class="relative">
        <flux:card>
            <div class="overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column class="!whitespace-normal !break-words">Evergreen Truths</flux:table.column>
                    </flux:table.columns>
                    @foreach($shuffledTruths as $truth)
                        <flux:table.row>
                            <flux:table.cell class="!whitespace-normal !break-words">
                                {{ $truth->description }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table>
            </div>
        </flux:card>
    </div>

    <!-- Whimsical View -->
    <div x-show="whimsical"
         x-transition:enter="transition-opacity duration-1000"
         x-transition:enter-start="opacity-0"
         class="relative min-h-[500px] flex items-center justify-center p-8">

        @foreach($shuffledTruths as $index => $truth)
            <div x-show="currentTruthIndex === {{ $index }}"
                 x-cloak
                 x-transition:enter="transition-opacity duration-1000"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-1000"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute z-10 max-w-xs md:max-w-2xl"
                 style="display: none; top: {{ $positions[$index]['y'] }}%; left: {{ $positions[$index]['x'] }}%; transform: translate(-50%, -50%); max-height: 80vh; overflow-y: auto;">
                <p class="text-2xl md:text-4xl text-white text-center leading-relaxed px-4 italic break-words"
                   style="font-family: 'Garamond', 'Georgia', serif; text-shadow: 4px 4px 8px rgba(0,0,0,0.7), 2px 2px 4px rgba(0,0,0,0.5); word-wrap: break-word; overflow-wrap: break-word;">
                    {{ $truth->description }}
                </p>
            </div>
        @endforeach
    </div>

</div>
