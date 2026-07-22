{{--
    Adjust-only example — no cropping. See App\NativeComponents\AdjustPhoto.
--}}
<column class="w-full h-full bg-[#121417] safe-area">

    {{-- Top bar --}}
    <row class="w-full items-center px-4 py-3">
        <pressable a11y-label="Back" class="w-[40] h-[40] items-center justify-center rounded-full" @navigate.back>
            <icon name="chevron.left" :size="22" class="text-white" />
        </pressable>
        <text class="flex-1 text-center text-base font-semibold text-white">Adjust</text>
        <column class="w-[40] h-[40]" />
    </row>

    {{-- Whole-image preview --}}
    <column class="w-full flex-1 items-center justify-center gap-6 px-5">
        @if ($sourcePath)
            {{-- previewBox() sizes the frame to the image's aspect → no black bars. --}}
            @php([$pw, $ph] = $this->previewBox())
            <column class="w-[{{ $pw }}] h-[{{ $ph }}] rounded-2xl overflow-hidden">
                <image src="{{ $sourcePath }}" :fit="1" alt="Adjusted photo" class="w-full h-full" />
            </column>
        @else
            <column class="w-[220] h-[220] rounded-full items-center justify-center bg-white/5 border-2 border-white/15">
                <icon name="slider.horizontal.3" :size="96" class="text-white/25" />
            </column>
        @endif

        <text class="text-center text-sm text-white/50">
            {{ $sourcePath ? 'Tap Edit to adjust colours' : 'Add a photo to adjust' }}
        </text>
    </column>

    {{-- Bottom bar: Edit · Update --}}
    <row class="w-full items-center justify-around px-6 py-4 border-t border-white/10">
        <pressable a11y-label="Edit" class="items-center gap-1" @press="startEdit">
            <icon name="slider.horizontal.3" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Edit</text>
        </pressable>
        <pressable a11y-label="Update" class="items-center gap-1" @press="update">
            <icon name="photo.on.rectangle" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Update</text>
        </pressable>
    </row>

</column>
