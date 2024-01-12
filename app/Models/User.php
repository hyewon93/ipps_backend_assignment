<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The attributes for achievements and badges.
     *
     * @var array<number>
     */
    private $lessonsWatchedAchievements = [1, 5, 10, 25, 50];
    private $commentsWrittenAchievements = [1, 3, 5, 10, 20];
    private $badges = [0, 4, 8, 10];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * The achievements for a user.
     *      - unlockedAchievements: The achievements that a user has.
     *      - nextAvailableAchievements: The next achievements that a user can achieve.
     */
    public function achievements()
    {
        $unlockedAchievements = [];
        $nextAvailableAchievements = [];

        $watchedLessons = $this->lessons;
        for($i=0; $i<count($this->lessonsWatchedAchievements); $i++) {
            if($this->lessonsWatchedAchievements[$i] <= count($watchedLessons)) {
                if($i == 0) {
                    array_push($unlockedAchievements, "First Lesson Watched");
                } else {
                    array_push($unlockedAchievements, $this->lessonsWatchedAchievements[$i] . " Lesson Watched");
                }

            } else {
                if($i == 0) {
                    array_push($nextAvailableAchievements, "First Lesson Watched");
                } else {
                    array_push($nextAvailableAchievements, $this->lessonsWatchedAchievements[$i] . " Lesson Watched");
                }
                break;
            }
        }

        $comments = $this->comments;
        for($i=0; $i<count($this->commentsWrittenAchievements); $i++) {
            if($this->commentsWrittenAchievements[$i] <= count($comments)) {
                if($i == 0) {
                    array_push($unlockedAchievements, "First Comment Written");
                } else {
                    array_push($unlockedAchievements, $this->commentsWrittenAchievements[$i] . " Comment Written");
                }

            } else {
                if($i == 0) {
                    array_push($nextAvailableAchievements, "First Comment Written");
                } else {
                    array_push($nextAvailableAchievements, $this->commentsWrittenAchievements[$i] . " Comment Written");
                }
                break;
            }
        }


        return [
            "unlockedAchievements" => $unlockedAchievements,
            "nextAvailableAchievements" => $nextAvailableAchievements
        ];
    }

    /**
     * The badges for a user.
     *      - currentBadge: The badge that a user has currently.
     *      - nextBadge: The next badge that a user can earn.
     *      - remainingToUnlockNextBadge: The number of remaining achievements for a user to earn the next badge.
     */
    public function badges()
    {
        $totalUnlockedAchievements = count($this->achievements()['unlockedAchievements']);

        $currentBadgeIdx = 0;
        if($totalUnlockedAchievements > 0) {
            for($i=0; $i<count($this->badges); $i++) {
                if($this->badges[$i] <= $totalUnlockedAchievements) {
                    $currentBadgeIdx = $this->badges[$i];
                } else {
                    break;
                }
            }
        }

        $currentBadge = "";
        $nextBadge = "";
        switch($currentBadgeIdx) {
            case 0:
                $currentBadge = "Beginner";
                $nextBadge = "Intermediate";
                break;
            case 1:
                $currentBadge = "Intermediate";
                $nextBadge = "Advanced";
                break;
            case 2:
                $currentBadge = "Advanced";
                $nextBadge = "Master";
                break;
            case 3:
                $currentBadge = "Master";
                $nextBadge = "-";
                break;
            default:
                break;
        }

        $remainingToUnlockNextBadge = 0;
        if($remainingToUnlockNextBadge < $this->badges[count($this->badges) - 1]) {
            $remainingToUnlockNextBadge = $this->badges[$currentBadgeIdx + 1] - $totalUnlockedAchievements;
        }

        return [
            "currentBadge" => $currentBadge,
            "nextBadge" => $nextBadge,
            "remainingToUnlockNextBadge" => $remainingToUnlockNextBadge
        ];
    }
}

