@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-blue-600 focus:border-indigo-500 focus:ring-blue-600 rounded-md shadow-sm']) }}>
