<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        </div>

        <!-- Content -->
        <div class="px-6 py-6">
            {{ $content }}
        </div>
    </div>
</div>
