<?php

class ActivityLog
{
    public static function create($userId, $activity)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO activity_logs (user_id, activity) VALUES (?, ?)"
        );
        $stmt->execute([$userId, $activity]);
    }
}
