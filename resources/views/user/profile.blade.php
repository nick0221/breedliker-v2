<!-- resources/views/user/profile.blade.php -->

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    <!-- Back Button -->
                    <div class="mb-4">
                        <button
                            onclick="window.history.back()"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 focus:outline-none">
                            ← Back
                        </button>
                    </div>

                    <!-- Profile Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">User Profile: {{ $user->name }}</h1>
                    </div>

                    <!-- Liked Dog Images Section -->
                    <div class="mb-4">
                        <p class="font-semibold">Liked Dog Images:</p>

                        @if($dogPreferences->isEmpty())
                            <p class="text-gray-500">This user hasn't liked any dogs yet.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($dogPreferences as $preference)
                                    <div class="p-4 border rounded shadow">
                                        <!-- Display Dog Image -->
                                        <img src="{{ $preference->image }}" alt="Dog Image" class="w-full h-48 object-cover rounded" loading="lazy">

                                        <!-- Display Dog Breed Name -->
                                        <p class="text-center mt-2 text-lg font-semibold">{{ ucfirst($preference->breed) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
