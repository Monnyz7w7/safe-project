<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <img x-bind:src="state" alt="Default avatar" class="w-16 h-16 rounded-full bg-gray-100">
    </div>
</x-dynamic-component>
