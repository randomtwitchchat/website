@props([
    'disabled' => false,
])

@php $wireModel = $attributes->get('wire:model'); @endphp

<div data-model="{{ $wireModel }}">
    <input {{ $disabled ? 'disabled' : '' }} {{ $attributes->whereStartsWith('wire:model') }} {!! $attributes->merge(['class' => 'bg-gray-700 border-gray-500 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm']) !!}>
</div>
