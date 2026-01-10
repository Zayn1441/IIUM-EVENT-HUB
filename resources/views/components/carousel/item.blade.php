@props(['orientation' => 'horizontal'])

<div class="min-w-0 shrink-0 grow-0 basis-full {{ $orientation === 'horizontal' ? 'pl-4' : 'pt-4' }}">
    {{ $slot }}
</div>