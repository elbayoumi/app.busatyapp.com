<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\MqttUserService;

class ProjectObserver
{
    protected $mqttUserService;

    public function __construct(MqttUserService $mqttUserService)
    {
        $this->mqttUserService = $mqttUserService;
    }

    public function created(Project $project)
    {
        try {
            $this->mqttUserService->addMqttUser($project["username"], $project['password']);
        } catch (\Exception $e) {
            // تعامل مع الاستثناءات إذا لزم الأمر
        }
    }

    public function updated(Project $project)
    {
        try {
            // تحقق من وجود كلمة المرور
            if (!empty($project['password'])) {
                $this->mqttUserService->updateMqttUser($project["username"], $project['password']);
            }
        } catch (\Exception $e) {
            // تعامل مع الاستثناءات إذا لزم الأمر
        }
    }

    public function deleted(Project $project)
    {
        try {
            $this->mqttUserService->deleteMqttUser($project["username"]);
        } catch (\Exception $e) {
            // تعامل مع الاستثناءات إذا لزم الأمر
        }
    }

    public function restored(Project $project)
    {
        //
    }

    public function forceDeleted(Project $project)
    {
        //
    }
}
