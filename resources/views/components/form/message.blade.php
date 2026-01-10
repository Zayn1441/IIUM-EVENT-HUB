@props(['error'])

@if($error)
    <p {{ $attributes->merge(['class' => 'text-sm font-medium text-destructive']) }}>
        {{ $error }}
    </p>
@endif