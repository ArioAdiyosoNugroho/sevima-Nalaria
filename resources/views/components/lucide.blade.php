@props([
    'name',
    'class' => 'w-4 h-4',
    'size' => 18,
    'strokeWidth' => 2,
])

<i data-lucide="{{ $name }}" {{ $attributes->merge(['class' => 'inline-block ' . $class]) }} data-lucide-size="{{ $size }}" data-lucide-stroke-width="{{ $strokeWidth }}"></i>
