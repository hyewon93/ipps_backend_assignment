<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements' => $user->achievements()['unlockedAchievements'],
            'next_available_achievements' => $user->achievements()['nextAvailableAchievements'],
            'current_badge' => $user->badges()['currentBadge'],
            'next_badge' => $user->badges()['nextBadge'],
            'remaing_to_unlock_next_badge' => $user->badges()['remainingToUnlockNextBadge'],
        ]);
    }
}
