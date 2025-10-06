<?php

namespace App\Services\Firebase;

use App\Models\FcmToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


class NewFcmService
{
  protected Messaging $messaging;

  public function __construct()
  {
    $this->messaging = (new Factory())
      ->withServiceAccount(config('services.firebase.credentials_file'))
      ->createMessaging();
  }

  /**
   * Send a notification to a single device token.
   */
  public function sendToToken(
    string $token,
    string $title,
    string $body,
    array $data = []
  ) {
    $message = CloudMessage::withTarget('token', $token)
      ->withNotification(Notification::create($title, $body))
      ->withData($data);

    return $this->messaging->send($message);
  }

  /**
   * Send a notification to all devices subscribed to a topic. ( +500 tokens )
   */
  public function sendToTopic(
    string $topic,
    string $title,
    string $body,
    array $data = []
  ) {
    $message = CloudMessage::withTarget('topic', $topic)
      ->withNotification(Notification::create($title, $body))
      ->withData($data);

    return $this->messaging->send($message);
  }

  /**
   * Send a notification to multiple device tokens at once.  ( [ 2 - 500 ]  tokens  )
   */
  public function sendMulticast(
    array $tokens,
    string $title,
    string $body,
    array $data = []
  ) {
    $message = CloudMessage::new()
      ->withNotification(Notification::create($title, $body))
      ->withData($data);

    $report = $this->messaging->sendMulticast($message, $tokens);

    //  remove invalid & unknown tokens
    $invalidTargets = $report->invalidTokens(); // string[]
    $unknownTargets = $report->unknownTokens(); // string[]

    FcmToken::whereIn('fcm_token', $invalidTargets)
      ->orWhereIn('fcm_token', $unknownTargets)->delete();
    return true;
  }

  /**
   * Subscribe one or more device tokens to a topic.
   */
  public function subscribeToTopic(
    string $topic,
    array|string $tokens
  ): array {
    return $this->messaging->subscribeToTopic($topic, $tokens);
  }

  /**
   * Unsubscribe one or more device tokens from a topic.
   */
  public function unsubscribeFromTopic(
    string $topic,
    array|string $tokens
  ): array {
    return $this->messaging->unsubscribeFromTopic($topic, $tokens);
  }
}
