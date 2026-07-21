{{--
    Image Studio — a single preview screen.

    Big circular preview (placeholder when empty, or the saved/cropped image)
    with Edit + Update. Editing happens in the native crop editor (the
    image-cropper plugin), which opens straight after a photo is picked or when
    Edit is tapped — there is no intermediate editor screen here.
--}}
<column class="w-full h-full bg-[#121417] safe-area">

    {{-- Top bar --}}
    <row class="w-full items-center px-4 py-3">
        <pressable a11y-label="Back" class="w-[40] h-[40] items-center justify-center rounded-full" @navigate.back>
            <icon name="chevron.left" :size="22" class="text-white" />
        </pressable>
        <text class="flex-1 text-center text-base font-semibold text-white">Image Studio</text>
        <column class="w-[40] h-[40]" />
    </row>

    {{-- Preview shows the image AS IS (fit=1 = whole image, letterboxed in a
         rounded rectangle). native:image is sized by CSS classes + fills its
         fixed-size parent. --}}
    <column class="w-full flex-1 items-center justify-center gap-6 px-5">
        @if ($sourcePath)
            <column class="w-full h-[360] rounded-2xl overflow-hidden items-center justify-center bg-black">
                <image src="{{ $sourcePath }}" :fit="1" alt="Current photo" class="w-full h-full" />
            </column>
        @else
            <column class="w-[220] h-[220] rounded-full items-center justify-center bg-white/5 border-2 border-white/15">
                <icon name="photo.on.rectangle.angled" :size="96" class="text-white/25" />
            </column>
        @endif

        <text class="text-center text-sm text-white/50">
            {{ $sourcePath ? 'Tap Edit to crop & adjust' : 'Add a photo to get started' }}
        </text>
    </column>

    {{-- Bottom bar: Edit · Update --}}
    <row class="w-full items-center justify-around px-6 py-4 border-t border-white/10">
        <pressable a11y-label="Edit" class="items-center gap-1" @press="startEdit">
            <icon name="pencil" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Edit</text>
        </pressable>
        <pressable a11y-label="Update" class="items-center gap-1" @press="update">
            <icon name="photo.on.rectangle" :size="24" class="text-white" />
            <text class="text-xs text-white/80">Update</text>
        </pressable>
    </row>

</column>
