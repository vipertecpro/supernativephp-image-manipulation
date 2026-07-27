{{--
    Remote image example — the cropper opens straight from an http(s) URL.
    The plugin downloads the image natively (themed loading screen with
    Cancel) and then shows the normal editor. See App\NativeComponents\RemoteImage.
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

    {{-- Result preview + the source URL --}}
    <column class="w-full flex-1 items-center justify-center gap-5 px-5">
        @if ($sourcePath)
            @php([$pw, $ph] = $this->previewBox())
            <column class="w-[{{ $pw }}] h-[{{ $ph }}] rounded-xl overflow-hidden">
                <image src="{{ $sourcePath }}" :fit="1" alt="Cropped remote image" class="w-full h-full" />
            </column>
        @else
            <column class="w-[330] h-[220] rounded-xl items-center justify-center gap-3 bg-white/5 border-2 border-white/15">
                <icon name="globe" :size="64" class="text-white/25" />
                <text class="text-xs text-white/40">No local file — the source is a URL</text>
            </column>
        @endif

        <column class="w-full items-center gap-1 px-4">
            <text class="text-center text-sm text-white/50">
                {{ $sourcePath ? 'Cropped from the remote source below' : 'Tap Edit to crop straight from this URL' }}
            </text>
            <text class="text-center text-xs text-white/30" :max-lines="1">{{ $imageUrl }}</text>
        </column>
    </column>

    {{-- Bottom bar: Edit (crop this URL) · New Image (another random URL) --}}
    <row class="w-full items-center justify-around px-6 py-4 border-t border-white/10">
        <pressable a11y-label="Edit" class="items-center gap-1" @press="startEdit">
            <icon name="crop" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Edit</text>
        </pressable>
        <pressable a11y-label="New Image" class="items-center gap-1" @press="update">
            <icon name="arrow.clockwise" :size="24" class="text-white" />
            <text class="text-xs text-white/80">New Image</text>
        </pressable>
    </row>

</column>
