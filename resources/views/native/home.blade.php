{{--
    Home — a gallery of image-cropper examples. Each card opens the SAME native
    plugin configured differently (full-featured, locked profile, locked cover),
    showing how one cropper covers many use cases.
--}}
<column fill class="safe-area bg-theme-background px-6 py-6">
    <scroll-view class="w-full flex-1">
        <column class="w-full items-center gap-7">

            {{-- Header --}}
            <column class="w-full items-center gap-2 mt-2">
                <image
                    src="{{ public_path('images/nativephp-logo.png') }}"
                    alt="NativePHP"
                    class="w-16 h-16 object-contain"
                />
                <text font="accent" class="text-2xl font-bold text-center text-theme-on-surface">
                    Image Cropper
                </text>
                <text class="text-center text-sm text-theme-on-surface-variant px-2">
                    One configurable native cropper — tap an example to see it set up differently.
                </text>
            </column>

            {{-- Example cards --}}
            <column class="w-full gap-3">

                <row
                    a11y-label="Image Studio"
                    a11y-hint="The full-featured example with every option"
                    class="w-full items-center gap-4 rounded-2xl bg-theme-surface border border-theme-outline px-4 py-4"
                    @press="openImageStudio"
                >
                    <column class="w-[44] h-[44] rounded-xl items-center justify-center bg-theme-surface-variant">
                        <icon name="slider.horizontal.3" :size="22" class="text-theme-primary" />
                    </column>
                    <column class="flex-1 gap-[2]">
                        <text class="text-base font-semibold text-theme-on-surface">Image Studio</text>
                        <text class="text-xs text-theme-on-surface-variant">Every preset · crop, adjust &amp; filter</text>
                    </column>
                    <icon name="chevron.right" :size="16" class="text-theme-on-surface-variant" />
                </row>

                <row
                    a11y-label="Profile Photo"
                    a11y-hint="A circular avatar cropper, locked and simple"
                    class="w-full items-center gap-4 rounded-2xl bg-theme-surface border border-theme-outline px-4 py-4"
                    @press="openProfilePhoto"
                >
                    <column class="w-[44] h-[44] rounded-xl items-center justify-center bg-theme-surface-variant">
                        <icon name="person.crop.circle" :size="22" class="text-theme-primary" />
                    </column>
                    <column class="flex-1 gap-[2]">
                        <text class="text-base font-semibold text-theme-on-surface">Profile Photo</text>
                        <text class="text-xs text-theme-on-surface-variant">Circular · locked 1:1 · crop only</text>
                    </column>
                    <icon name="chevron.right" :size="16" class="text-theme-on-surface-variant" />
                </row>

                <row
                    a11y-label="Cover Photo"
                    a11y-hint="A wide banner cropper, locked and simple"
                    class="w-full items-center gap-4 rounded-2xl bg-theme-surface border border-theme-outline px-4 py-4"
                    @press="openCoverPhoto"
                >
                    <column class="w-[44] h-[44] rounded-xl items-center justify-center bg-theme-surface-variant">
                        <icon name="photo.on.rectangle.angled" :size="22" class="text-theme-primary" />
                    </column>
                    <column class="flex-1 gap-[2]">
                        <text class="text-base font-semibold text-theme-on-surface">Cover Photo</text>
                        <text class="text-xs text-theme-on-surface-variant">Wide banner · locked ratio · crop only</text>
                    </column>
                    <icon name="chevron.right" :size="16" class="text-theme-on-surface-variant" />
                </row>

                <row
                    a11y-label="Adjust"
                    a11y-hint="Colour adjustments only, no cropping"
                    class="w-full items-center gap-4 rounded-2xl bg-theme-surface border border-theme-outline px-4 py-4"
                    @press="openAdjustPhoto"
                >
                    <column class="w-[44] h-[44] rounded-xl items-center justify-center bg-theme-surface-variant">
                        <icon name="slider.horizontal.3" :size="22" class="text-theme-primary" />
                    </column>
                    <column class="flex-1 gap-[2]">
                        <text class="text-base font-semibold text-theme-on-surface">Adjust</text>
                        <text class="text-xs text-theme-on-surface-variant">Brightness / contrast / saturation · no crop</text>
                    </column>
                    <icon name="chevron.right" :size="16" class="text-theme-on-surface-variant" />
                </row>

                <row
                    a11y-label="Filter"
                    a11y-hint="One-tap filters only, no cropping"
                    class="w-full items-center gap-4 rounded-2xl bg-theme-surface border border-theme-outline px-4 py-4"
                    @press="openFilterPhoto"
                >
                    <column class="w-[44] h-[44] rounded-xl items-center justify-center bg-theme-surface-variant">
                        <icon name="camera.filters" :size="22" class="text-theme-primary" />
                    </column>
                    <column class="flex-1 gap-[2]">
                        <text class="text-base font-semibold text-theme-on-surface">Filter</text>
                        <text class="text-xs text-theme-on-surface-variant">One-tap filter presets · no crop</text>
                    </column>
                    <icon name="chevron.right" :size="16" class="text-theme-on-surface-variant" />
                </row>

            </column>

            <text class="text-center text-xs text-theme-on-surface-variant px-4">
                Same plugin, different config — presets, tools &amp; modes. On Done the cropped
                file is saved on the device (swap in your own backend sync).
            </text>

        </column>
    </scroll-view>
</column>
