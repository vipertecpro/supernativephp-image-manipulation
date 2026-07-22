{{--
    Profile photo example — circular avatar preview backed by a locked-down
    cropper (circle 1:1, crop-only). See App\NativeComponents\ProfilePhoto.
--}}
<column class="w-full h-full bg-[#121417] safe-area">

    {{-- Top bar --}}
    <row class="w-full items-center px-4 py-3">
        <pressable a11y-label="Back" class="w-[40] h-[40] items-center justify-center rounded-full" @navigate.back>
            <icon name="chevron.left" :size="22" class="text-white" />
        </pressable>
        <text class="flex-1 text-center text-base font-semibold text-white">Profile Photo</text>
        <column class="w-[40] h-[40]" />
    </row>

    {{-- Circular avatar preview --}}
    <column class="w-full flex-1 items-center justify-center gap-6 px-5">
        @if ($sourcePath)
            {{-- The crop output is already a square circle, so fit=1 (contain) fills
                 this square container exactly. fit=2 (cover) collapses on iOS. --}}
            <column class="w-[240] h-[240] rounded-full overflow-hidden bg-black border-2 border-white/15">
                <image src="{{ $sourcePath }}" :fit="1" alt="Profile photo" class="w-full h-full" />
            </column>
        @else
            <column class="w-[240] h-[240] rounded-full items-center justify-center bg-white/5 border-2 border-white/15">
                <icon name="person.crop.circle" :size="110" class="text-white/25" />
            </column>
        @endif

        <text class="text-center text-sm text-white/50">
            {{ $sourcePath ? 'Tap Edit to reframe your avatar' : 'Add a profile photo' }}
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
