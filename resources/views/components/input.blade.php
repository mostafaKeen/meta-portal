@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] rounded-xl shadow-sm transition-all duration-200 text-sm']) !!}>
