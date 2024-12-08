<?php

namespace App\Livewire;


use Illuminate\Support\Facades\Http;
use Livewire\Component;

class DogBreeds extends Component
{
    public $randomImages = [];
    public $breeds = [];
    public $selectedBreed = 'pug'; // Default breed to display
    public $loading = false; // Track loading state for fetching images
    public $loadingLike = false; // Track loading state for liking/unliking images

    public function mount()
    {
        // Fetch all available breeds
        $response = Http::withoutVerifying()->get('https://dog.ceo/api/breeds/list/all');
        if ($response->successful()) {
            $this->breeds = array_keys($response->json()['message']);
        }

        // Load initial images
        $this->loadRandomImages();
    }

    public function loadRandomImages()
    {
        // Set loading state to true
        $this->loading = true;

        // Fetch random images based on the selected breed
        $response = Http::withoutVerifying()->get("https://dog.ceo/api/breed/{$this->selectedBreed}/images/random/6");

        // Set loading state to false after API call
        $this->loading = false;

        if ($response->successful()) {
            $this->randomImages = $response->json()['message'];
        }
    }

    public function filterImages()
    {
        // Load new images with the current breed filter
        $this->loadRandomImages();
    }

    public function likeDog($imageUrl)
    {
        // Set loading state to true when the like/unlike action is triggered
        $this->loadingLike = true;

        $dogBreed = $this->selectedBreed;

        // Check if the user has already liked this image
        $existingPreference = auth()->user()->dogPreferences()
            ->where('breed', $dogBreed)
            ->where('image', $imageUrl)
            ->first();

        if ($existingPreference) {
            // If already liked, remove the like (unlike)
            $existingPreference->delete();
            session()->flash('success', 'Dog unliked!');
        } else {
            // If not liked, add the like (like)
            auth()->user()->dogPreferences()->create([
                'breed' => $dogBreed,
                'image' => $imageUrl,
            ]);
            session()->flash('success', 'You liked the '. ucfirst($dogBreed) . ' breed!');
        }

        // Set loading state to false after the like/unlike action is complete
        $this->loadingLike = false;

    }

    public function render()
    {
        return view('livewire.dog-breeds');
    }
}
