@props(['submit'])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        </div>

        <!-- Form -->
        <form wire:submit="{{ $submit }}">
            <div class="px-6 py-6">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
