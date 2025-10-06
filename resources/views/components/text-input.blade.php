@props(['disabled' => false, 'icon' => null])

<div class="relative w-full">

    <input @disabled($disabled)
        {{ $attributes->merge([
            'class' => 'w-full border-gray-300 focus:border-blue-500 focus:ring-blue-600 rounded-md shadow-sm h-11 ' . ($icon ? 'pl-11 pr-4' : 'px-4')
        ]) }}>
</div>
