<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

class AchievementsListener
{
    /**
     * The attributes for achievements and badges.
     *
     * @var array<number>
     */
    private $lessonsWatchedAchievements = [1, 5, 10, 25, 50];
    private $commentsWrittenAchievements = [1, 3, 5, 10, 20];
    private $badges = [0, 4, 8, 10];

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {

        if(empty($event->user)) {
            $event->user = Auth::user();
        }

        $watchedLessons = $event->user->lessons;
        $comments = $event->user->comments;

        // Lessons Watched Achievement
        $unlockedLessonAchievementIdx = 0;
        switch(count($watchedLessons)) {
            case $this->lessonsWatchedAchievements[0]:
                $unlockedLessonAchievementIdx = 1;
                break;
            case $this->lessonsWatchedAchievements[1]:
                $unlockedLessonAchievementIdx = 2;
                break;
            case $this->lessonsWatchedAchievements[2]:
                $unlockedLessonAchievementIdx = 3;
                break;
            case $this->lessonsWatchedAchievements[3]:
                $unlockedLessonAchievementIdx = 4;
                break;
            case $this->lessonsWatchedAchievements[4]:
                $unlockedLessonAchievementIdx = 5;
                break;
            default:
                break;
        }

        if($unlockedLessonAchievementIdx > 0) {
            // Dispatch AchievementUnlocked Event 

            $achievementName = "";
            if($unlockedLessonAchievementIdx == 1) {
                $achievementName = "First Lesson Watched";
            } else {
                $achievementName = $this->lessonsWatchedAchievements[$unlockedLessonAchievementIdx - 1] . " Lesson Watched";
            }

            AchievementUnlocked::dispatch($achievementName, $event->user);
        }

        // Comment Written Achievement
        $unlockedCommentAchievementIdx = 0;
        switch(count($comments)) {
            case $this->commentsWrittenAchievements[0]:
                $unlockedCommentAchievementIdx = 1;
                break;
            case $this->commentsWrittenAchievements[1]:
                $unlockedCommentAchievementIdx = 2;
                break;
            case $this->commentsWrittenAchievements[2]:
                $unlockedCommentAchievementIdx = 3;
                break;
            case $this->commentsWrittenAchievements[3]:
                $unlockedCommentAchievementIdx = 4;
                break;
            case $this->commentsWrittenAchievements[4]:
                $unlockedCommentAchievementIdx = 5;
                break;
            default:
                break;
        }

        if($unlockedCommentAchievementIdx > 0) {
            // Dispatch AchievementUnlocked Event 

            $achievementName = "";
            if($unlockedLessonAchievementIdx == 1) {
                $achievementName = "First Comment Written";
            } else {
                $achievementName = $this->commentsWrittenAchievements[$unlockedCommentAchievementIdx - 1] . " Comment Written";
            }

            dd($achievementName);

            AchievementUnlocked::dispatch($achievementName, $event->user);
        }

        // Badge Unlocked
        $totalUnlockedAchievement = 0;
        if(count($watchedLessons) > 0) {
            for($i=0; $i<count($this->lessonsWatchedAchievements); $i++) {
                if($this->lessonsWatchedAchievements[$i] <= count($watchedLessons)) {
                    $totalUnlockedAchievement++;
                } else {
                    break;
                }
            }
        }

        if(count($comments) > 0) {
            for($i=0; $i<count($this->commentsWrittenAchievements); $i++) {
                if($this->commentsWrittenAchievements[$i] <= count($comments)) {
                    $totalUnlockedAchievement++;
                } else {
                    break;
                }
            }
        }

        $unlockedBadgeName = "Beginner";
        switch($totalUnlockedAchievement) {
            case $this->badges[1]:
                $unlockedBadgeName = "Intermediate";
                break;
            case $this->badges[2]:
                $unlockedBadgeName = "Advanced";
                break;
            case $this->badges[3]:
                $unlockedBadgeName = "Master";
                break;
            default:
                break;
        }

        if($unlockedBadgeName !== "" && $unlockedBadgeName !== "Beginner") {
            // Dispatch BadgeUnlocked Event 
            BadgeUnlocked::dispatch($unlockedBadgeName, $event->user);
        }
    }
}
