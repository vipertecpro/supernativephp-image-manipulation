{{--
    Remote image example — type/paste any image URL, then crop it. The plugin
    downloads the URL natively (themed loading screen with Cancel) and opens
    the normal editor. Non-croppable formats are rejected with a toast.
    See App\NativeComponents\RemoteImage.
--}}
<column class="w-full h-full bg-[#121417] safe-area">

    {{-- Top bar --}}
    <row class="w-full items-center px-4 py-3">
        <pressable a11y-label="Back" class="w-[40] h-[40] items-center justify-center rounded-full" @navigate.back>
            <icon name="chevron.left" :size="22" class="text-white" />
        </pressable>
        <text class="flex-1 text-center text-base font-semibold text-white">Remote Image</text>
        <column class="w-[40] h-[40]" />
    </row>

    {{-- Result preview --}}
    <column class="w-full flex-1 items-center justify-center gap-5 px-5">
        @if ($sourcePath)
            @php([$pw, $ph] = $this->previewBox(330, 300))
            <column class="w-[{{ $pw }}] h-[{{ $ph }}] rounded-xl overflow-hidden">
                <image src="{{ $sourcePath }}" :fit="1" alt="Cropped remote image" class="w-full h-full" />
            </column>
        @else
            <column class="w-[330] h-[200] rounded-xl items-center justify-center gap-3 bg-white/5 border-2 border-white/15">
                <icon name="globe" :size="56" class="text-white/25" />
                <text class="text-xs text-white/40">Enter a URL below, then tap Crop</text>
            </column>
        @endif

        {{-- URL input — bound live to $imageUrl; return key starts the crop --}}
        <column class="w-full gap-2 px-1">
            <text class="text-xs text-white/50 px-1">Image URL</text>
            <outlined-text-input
                native:model.debounce.400ms="imageUrl"
                placeholder="https://example.com/photo.jpg"
                keyboard="url"
                @submit="startEdit"
                class="w-full"
            />
            <text class="text-xs text-white/35 px-1">
                Croppable formats only — try a .pdf URL to see the validation toast.
            </text>
        </column>
    </column>

    {{-- Bottom bar: Crop (the typed URL) · Sample (fill a random photo URL) --}}
    <row class="w-full items-center justify-around px-6 py-4 border-t border-white/10">
        <pressable a11y-label="Crop from URL" class="items-center gap-1" @press="startEdit">
            <icon name="crop" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Crop</text>
        </pressable>
        <pressable a11y-label="Fill a sample URL" class="items-center gap-1" @press="fillSample">
            <icon name="arrow.clockwise" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Sample</text>
        </pressable>
    </row>

</column>
