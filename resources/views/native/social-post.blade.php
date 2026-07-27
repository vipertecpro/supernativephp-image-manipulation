{{--
    Social post example — Instagram-style feed post. The cropper offers the
    three post ratios (square / portrait / landscape) plus one-tap filters.
    See App\NativeComponents\SocialPost.
--}}
<column class="w-full h-full bg-[#121417] safe-area">

    {{-- Top bar --}}
    <row class="w-full items-center px-4 py-3">
        <pressable a11y-label="Back" class="w-[40] h-[40] items-center justify-center rounded-full" @navigate.back>
            <icon name="chevron.left" :size="22" class="text-white" />
        </pressable>
        <text class="flex-1 text-center text-base font-semibold text-white">Social Post</text>
        <column class="w-[40] h-[40]" />
    </row>

    {{-- Post preview — the frame hugs whichever ratio the user chose --}}
    <column class="w-full flex-1 items-center justify-center gap-6 px-5">
        @if ($sourcePath)
            @php([$pw, $ph] = $this->previewBox())
            <column class="w-[{{ $pw }}] h-[{{ $ph }}] rounded-xl overflow-hidden">
                <image src="{{ $sourcePath }}" :fit="1" alt="Post image" class="w-full h-full" />
            </column>
        @else
            <column class="w-[300] h-[300] rounded-xl items-center justify-center bg-white/5 border-2 border-white/15">
                <icon name="square.grid.2x2" :size="80" class="text-white/25" />
            </column>
        @endif

        <text class="text-center text-sm text-white/50">
            {{ $sourcePath ? 'Tap Edit to reframe or re-filter your post' : 'Add a photo for your post' }}
        </text>
    </column>

    {{-- Bottom bar: Edit · Update --}}
    <row class="w-full items-center justify-around px-6 py-4 border-t border-white/10">
        <pressable a11y-label="Edit" class="items-center gap-1" @press="startEdit">
            <icon name="crop" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Edit</text>
        </pressable>
        <pressable a11y-label="Update" class="items-center gap-1" @press="update">
            <icon name="photo.on.rectangle" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Update</text>
        </pressable>
    </row>

</column>
