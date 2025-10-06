<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    protected $client;

    public function __construct()
    {
        $host = config('mqtt.host');
        $port = config('mqtt.port');
        $clientId = config('mqtt.client_id');
        $username = config('mqtt.username');
        $password = config('mqtt.password');

        // تهيئة إعدادات الاتصال
        $settings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setLastWillTopic('last/will/topic') // يمكنك تعديل هذا حسب احتياجاتك
            ->setLastWillMessage('Client disconnected unexpectedly');

        try {
            $this->client = new MqttClient($host, $port, $clientId);
            $this->client->connect($settings); // تمرير إعدادات الاتصال
        } catch (MqttClientException $e) {
            throw new \Exception("فشل الاتصال بـ MQTT: " . $e->getMessage());
        }
    }

    public function publish($topic, $message, $qos = 0)
    {
        $this->client->publish($topic, $message, $qos);
    }

    public function subscribe($topic, callable $callback)
    {
        $this->client->subscribe($topic, $callback);
    }

    public function disconnect()
    {
        $this->client->disconnect();
    }

    public function sendNotification($userId, $title, $body, $type = 'both')
    {
        $notificationMessage = json_encode([
            'title' => $title,
            'body' => $body,
            'type' => $type, // notification_only, real_time_only, both
        ]);

        $this->publish("user/{$userId}/notifications", $notificationMessage);
    }
}
