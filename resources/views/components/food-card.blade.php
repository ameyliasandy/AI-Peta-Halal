@props(['name', 'price', 'image' => null, 'distance' => null, 'rating' => null])

<div class="bg-white rounded-2xl overflow-hidden shadow-sm w-full">
    <div class="h-28 sm:h-32 bg-gray-200 overflow-hidden">
        @if($image)
            <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-3xl">🍽️</div>
        @endif
    </div>
    <div class="p-2.5 sm:p-3">
        @if($rating)
            <div class="flex items-center gap-1 text-xs text-gray-500 mb-1">
                <span>⭐ {{ $rating }}</span>
                @if($distance)
                    <span>· 📍 {{ $distance }}</span>
                @endif
            </div>
        @endif
        <p class="font-semibold text-xs sm:text-sm text-gray-800 leading-tight">{{ $name }}</p>
        <p class="text-green-700 font-bold text-xs sm:text-sm mt-1">{{ $price }}</p>
    </div>
</div>