<?php

namespace App\Services\Schools\Actions;

use App\Models\School;
use App\Models\Topic;

class AttachTopicToSchoolAction
{
    public static function run(School $school): void
    {
        $topicName = $school->getMorphClass();
        $topic = Topic::firstOrCreate(['name' => $topicName]);

        $school->topics()->attach($topic);
    }
}
