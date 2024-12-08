<?php

namespace App\Http\Controllers;

use App\Models\DogPreference;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        // Fetch the user's liked dog images/preferences
        $dogPreferences = DogPreference::where('user_id', $user->id)->get();

        return view('user.profile', compact('user', 'dogPreferences'));
    }
}
