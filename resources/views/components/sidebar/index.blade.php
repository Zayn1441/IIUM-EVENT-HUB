@props(['side' => 'left', 'variant' => 'sidebar', 'collapsible' => 'offcanvas'])

<template x-if="isMobile">
    <x-sheet x-model="openMobile" :side="$side" class="w-[18rem] p-0">
        <div class="flex h-full w-full flex-col bg-sidebar text-sidebar-foreground">
            {{ $slot }}
        </div>
    </x-sheet>
</template>

<div x-show="!isMobile" class="group peer hidden md:block text-sidebar-foreground"
    :data-state="open ? 'expanded' : 'collapsed'" data-collapsible="{{ $collapsible }}" data-side="{{ $side }}"
    data-variant="{{ $variant }}">
    <!-- Gap -->
    <div class="relative h-full w-[var(--sidebar-width)] bg-transparent transition-[width] duration-200 ease-linear"
        :class="{
            'w-0': !open && '{{ $collapsible }}' === 'offcanvas',
            'w-[var(--sidebar-width-icon)]': !open && '{{ $collapsible }}' === 'icon'
        }"></div>

    <!-- Container -->
    <div class="fixed inset-y-0 z-10 hidden h-screen w-[var(--sidebar-width)] transition-[left,right,width] duration-200 ease-linear md:flex"
        :class="{
            'left-0': '{{ $side }}' === 'left',
            'right-0': '{{ $side }}' === 'right',
            '-left-[var(--sidebar-width)]': !open && '{{ $collapsible }}' === 'offcanvas' && '{{ $side }}' === 'left',
            '-right-[var(--sidebar-width)]': !open && '{{ $collapsible }}' === 'offcanvas' && '{{ $side }}' === 'right',
            'w-[var(--sidebar-width-icon)]': !open && '{{ $collapsible }}' === 'icon'
        }">
        <div class="bg-sidebar text-sidebar-foreground flex h-full w-full flex-col border-r border-sidebar-border">
            {{ $slot }}
        </div>
    </div>
</div>