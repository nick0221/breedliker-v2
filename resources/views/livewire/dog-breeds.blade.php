<div class="p-4">
    <!-- Success Message -->
    @if(session()->has('success'))
        <div class="text-green-500 font-semibold mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <!-- Title -->
        <h1 class="text-2xl font-bold mb-2 sm:mb-0">Random Dog Images</h1>

        <!-- Filter Dropdown and Button -->
        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-x-4 sm:space-y-0">
            <select
                wire:model="selectedBreed"
                class="border rounded px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-300 w-full sm:w-auto">
                @foreach ($breeds as $breed)
                    <option value="{{ $breed }}">{{ ucfirst($breed) }}</option>
                @endforeach
            </select>
            <button
                wire:click="filterImages"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none w-full sm:w-auto"
                @disabled($loading)
            >
                @if($loading)
                    <span>Loading...</span>
                @else
                    Apply Filter
                @endif
            </button>
        </div>
    </div>

    <!-- Images Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($randomImages as $image)
            <div class="p-4 border rounded shadow">
                <!-- Breed Name -->
                <p class="text-center font-semibold text-lg mb-2">{{ ucfirst($selectedBreed) }}</p>

                <!-- Like Button -->
                <div class="mb-4">
                    <!-- Check if this image is already liked -->
                    @php
                        $isLiked = auth()->user()->dogPreferences()->where('breed', $selectedBreed)->where('image', $image)->exists();
                    @endphp

                        <!-- Button that changes based on the like/unlike state -->
                    <button
                        wire:click="likeDog('{{ $image }}')"
                        class="px-4 py-2 rounded-full hover:text-red-600 focus:outline-none {{ $isLiked ? 'text-red-500' : '' }}"
                    @disabled($loadingLike)
                    >
                    @if($loadingLike)
                        <span>Loading...</span> <!-- Loading text while the action is processing -->
                        @else
                            {{ $isLiked ? '👍 Liked' : '❤️ Like' }}
                        @endif
                        </button>
                </div>

                <!-- Image with Lazy Loading -->
                <img src="{{ $image }}" alt="Dog Image" class="w-full h-48 object-cover rounded" loading="lazy">

                <!-- Display Users Who Liked (Only if there are users who liked) -->
                @php
                    $likedByUsers = \App\Models\DogPreference::where('image', $image)->pluck('user_id');
                    $users = \App\Models\User::whereIn('id', $likedByUsers)->get();
                @endphp

                @if($users->isNotEmpty()) <!-- Only display if there are users who liked -->
                <div class="mt-2 text-sm">
                    <p class="font-semibold">Liked by:</p>
                    <ul>
                        @foreach ($users as $user)
                            <li>
                                <a href="{{ route('user.profile', $user->id) }}" class="text-blue-500 hover:underline">
                                    {{ $user->id === auth()->id() ? 'You' : $user->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        @endforeach
    </div>

</div>
