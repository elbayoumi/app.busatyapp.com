<?php
use Illuminate\Http\Request;
use App\Models\Notification;
/**
*   ---------------------------------
*   | Notifications types:           |
*   ---------------------------------
*   new message = 0
*   user follow user = 1
*   user need joun to a group = 2
*   joun group request accepted = 3
*   Someone mentioned you in a post = 4
*   Someone mentioned you in a comment = 5

**/


function getModelDataFromText($originalText){
    // $originalText = $request->q;

    // استخراج الكلمة بين القوسين

    // استخراج الكلمة بين {{ و }}
    preg_match('/\{\{(.*?)\}\}/', $originalText, $matches);
    if (isset($matches[1])) {
        $model = $matches[1]; // الموديل داخل {{ }}
    } else {
        die("لا توجد كلمة بين {{ }}.");
    }

    // استخراج النص بعد السهم ->
    $arrowPosition = strpos($originalText, '->');
    if ($arrowPosition !== false) {
        $parameter = substr($originalText, $arrowPosition + 2); // الجزء بعد ->
    } else {
        die("لا يوجد سهم -> في النص.");
    }

    // عرض النتائج
    echo "الموديل: $model\n";
    echo "المعامل: $parameter\n";

    // استخدام الكلمة بين {{ }} كموديل ديناميكيًا
    $modelName = ucfirst($model); // تحويل الكلمة إلى صيغة العنوان لتناسب أسماء الموديلات
    $fullModelClass = "App\\Models\\$modelName"; // تكوين اسم الموديل بالكامل

    if (class_exists($fullModelClass)) {
        $modelInstance = new $fullModelClass();

        // استدعاء الموديل مع المعامل
        $result = $modelInstance->$parameter();

        if ($result) {
            return "تم تنفيذ $parameter على $modelName بنجاح.";
        } else {
            return "لم يتم العثور على نتائج.";
        }
    } else {
        return "الموديل '$modelName' غير موجود.";
    }
}
function saveNotification($type, $title, $body, $user_ids)
{
    switch ($type) {
        case 1:
            foreach ($user_ids as $user_id) {
                $Notification = new Notification;
                $Notification->user_id = $user_id;
                $Notification->title = $title;
                $Notification->body = (is_object($body)) ? json_encode($body) : $body;
                $Notification->type = $type;
                $Notification->save();
            }
            break;
        case 2:
            foreach ($user_ids as $user_id) {
                $Notification = new Notification;
                $Notification->user_id = $user_id;
                $Notification->title = $title;
                $Notification->body = (is_object($body)) ? json_encode($body) : $body;
                $Notification->type = $type;
                $Notification->save();
            }
            break;
        case 3:
            foreach ($user_ids as $user_id) {
                $Notification = new Notification;
                $Notification->user_id = $user_id;
                $Notification->title = $title;
                $Notification->body = (is_object($body)) ? json_encode($body) : $body;
                $Notification->type = $type;
                $Notification->save();
            }
            break;
        case 4:
            foreach ($user_ids as $user_id) {
                $Notification = new Notification;
                $Notification->user_id = $user_id;
                $Notification->title = $title;
                $Notification->body = (is_object($body)) ? json_encode($body) : $body;
                $Notification->type = $type;
                $Notification->save();
            }
            break;
        case 5:
            foreach ($user_ids as $user_id) {
                $Notification = new Notification;
                $Notification->user_id = $user_id;
                $Notification->title = $title;
                $Notification->body = (is_object($body)) ? json_encode($body) : $body;
                $Notification->type = $type;
                $Notification->save();
            }
            break;
        default:
            # code...
            break;
    }

}

function sendNotification($tokens, $type, $title, $body, $user_ids = null) {

    if ($user_ids != null) {
        saveNotification($type, $title, $body, $user_ids);
    }

    $data = [
        "registration_ids" => $tokens,
        "data" => [
            "type" => $type,
            "title" => $title,
            "body" => $body,
        ]
    ];
    if ($title == null) {
        unset($data['title']);
    }
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . env('FCM_SERVER_KEY', 'AAAA_M4WFYM:APA91bEMVX8qO8Z7j7Q21SrS4ZyoVsml6Us8uha0QfD77smD3dsOVbbKoLkjVdtbGB4y6xOebwQG6BUbdPWO_4ycD2NWzX7Y9PaYsROzsMS25KRW9Z4DtJKSIBkXEj8z3AGD-bCKqw0i'),
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}
