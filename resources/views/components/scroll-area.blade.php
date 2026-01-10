@props(['orientation' => 'vertical'])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden']) }}>
    <div class="h-full w-full rounded-[inherit] overflow-auto">
        {{ $slot }}
    </div>

    <!-- 
      Note: Truly authentic Radix UI-style custom scrollbars require JavaScript to calculate thumb position/size.
      For this Blade adaptation, we rely on native scrollbars styled via CSS or Tailwind plugins if available.
      If Tailwind Scrollbar plugin is not present, this will default to browser scrollbars.
      
      We can add basic webkit customization here for a cleaner look.
    -->
    <style>
        .overflow-auto::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background-color: hsl(var(--border));
            border-radius: 9999px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background-color: hsl(var(--muted-foreground) / 0.5);
        }
    </style>
</div>