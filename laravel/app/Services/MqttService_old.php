<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MqttService_old
{
    protected $client;

    public function __construct()
    {
        $host = config('mqtt.host');
        $port = config('mqtt.port');
        $clientId = config('mqtt.client_id');

        try {
            $this->client = new MqttClient($host, $port, $clientId);
            $this->client->connect();
        } catch (MqttClientException $e) {
            // معالجة الأخطاء
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
}
