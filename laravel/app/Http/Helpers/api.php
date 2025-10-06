<?php

use App\Mail\SenVerificationCode;
use App\Mail\TestMail;
use App\Models\School;
use Carbon\Carbon;

use App\Models\Settings;
use App\Notifications\SendVerificationCodeNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

if (!function_exists('lang')) {
    function lang(): string
    {
        return request()->header('ln') ?? config('app.fallback_locale');
    }
}

function isValidText($text)
{
    return !empty($text) && strtolower($text) !== 'null';
}
function res($data, $status, $messages, $code = 200)
{

    return response()->json([
        'status' => [
            'status' => $status,
            'messages' => $messages,
            // 'server_titm' => Carbon::now()->timestamp,
        ],
        'data' => $data,
    ], $code);
}
function removeAppModels($modelType)
{
    return str_replace('App\\Models\\', '', $modelType);
}
///

/**
 * Summary of save_files
 * @param object $object
 * @param mixed $files
 * @param string $type
 * @param string $directory
 * @return array|string
 */
function save_files(object $object,  $files, string $type, string $directory = '')
{
    if (is_array($files)) {
        $full_urls = [];
        foreach ($files as $image) {
            $fileExt = $image->getClientOriginalExtension();
            $fileNameNew        = uniqid() . time() . '.' . $fileExt;
            $image->storeAs('public/' . $directory, $fileNameNew);

            $object->files()->create([
                'src' => $fileNameNew,
                'type' => $type
            ]);
            array_push($full_urls, asset('storage/' . $directory . '/' . $fileNameNew));
        }
        return $full_urls;
    } else {
        $fileExt = $files->getClientOriginalExtension();
        $fileNameNew        = uniqid() . time() . '.' . $fileExt;
        $files->storeAs('public/' . $directory, $fileNameNew);

        $object->files()->create([
            'src' => $fileNameNew,
            'type' => $type
        ]);
        return asset('storage/' . $directory . '/' . $fileNameNew);
    }
    return null;
}


/**
 * Summary of randKey
 * @return string
 */
function randKey()
{
    $key = ['a', 'b', 'c', 'd', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'y', 'v', 'w', 'x', 'y', 'z'];
    $key = rand(10, 99) . rand(10, 99) . rand(10, 99);
    return $key;
}


/**
 * Summary of attendence_status
 * @param string $attendence_status
 * @param string $status
 * @return string
 */
function attendence_status(string $attendence_status, string $status)
{
    switch ($attendence_status) {
        case 0:
            return 'غياب';
        case 1:
            switch ($status) {
                case 0:
                    return 'الطالب في الباص';
                case 1:
                    return 'حضور';
            }
    }
}


/**
 * Summary of student_trip_type_check
 * @param string $type
 * @return array
 */
function student_trip_type_check(String $type)
{

    $data = [];
    switch ($type) {
        case 'start':
            $data += ['full_day', 'start_day'];

            return $data;
        case 'end':
            $data += ['full_day', 'end_day'];
            return $data;
        default:
            return $data;
    }
}

/**
 * Summary of tr_student_check_has_trip_type
 * @param string $type
 * @return array
 */
function tr_student_check_has_trip_type(string $type)
{

    $data = [];
    switch ($type) {
        case 'full_day':
            $data += ['full_day', 'start_day', 'end_day'];

            return $data;
        case 'start_day':
            $data += ['start_day'];
            return $data;
        case 'end_day':
            $data += ['end_day'];
            return $data;
        default:
            return $data;
    }
}
/**
 * Summary of attendence_absence_type
 * @param string $type
 * @return array
 */
function attendence_absence_type(string $type)
{

    $data = [];
    switch ($type) {
        case 'start':
            $data += ['full_day', 'start_day'];

            return $data;
        case 'end':
            $data += ['full_day', 'end_day'];
            return $data;
        default:
            return $data;
    }
}

/**
 * Summary of attendence_type
 * @param string $attendence_type
 * @return string
 */
function attendence_type(string $attendence_type)
{

    switch ($attendence_type) {
        case 'full_day':
            return 'اليوم كامل';
        case 'start_day':
            return 'بدية اليوم';
        case 'end_day':
            return 'نهاية اليوم';
    }
}
/**
 * Summary of sendJSON
 * @param mixed $data
 * @return Illuminate\Http\JsonResponse|mixed
 */
function sendJSON($data)
{
    if ($data->exists()) {
        return JSON($data->first());
    }
    return response()->json([
        'message' => 'data not found',
        'status' => false,
    ], 404);
}
/**
 * Summary of JSON
 * @param mixed $data
 * @return Illuminate\Http\JsonResponse|mixed
 */
function JSON($data, $status_code = 200)
{
    return response()->json([
        'data' => $data,
        'message' => __("success message"),
        'status' => true
    ], $status_code);
}
/**
 * Summary of JSON
 * @param mixed $message
 * @return Illuminate\Http\JsonResponse|mixed
 */
function JSONMessage($message, $status_code = 200)
{

    return response()->json([
        'message' => $message,
        'status' => true
    ], $status_code);
}
function JSONcondtion($data, $status_code = 200)
{
    return response()->json([
        'data' => $data->first(),
        'message' => __("success message"),
        'status' => $data->exists()
    ], $status_code);
}
function JSONerror($messages, $status_code = 422)
{
    return response()->json([
        'errors' => true,
        'status' => false,
        'messages' => $messages
    ], $status_code);
}
/**
 * Summary of sendApiJSON
 * @param mixed $data
 * @param string $param
 * @param int $paginate
 * @param mixed $orderAt
 * @param string $orderBy
 * @return Illuminate\Http\JsonResponse|mixed
 */
function sendApiJSON($data, String $param = 'first', int $paginate = 10, $orderAt = null, String $orderBy = 'id')
{
    $data = ($orderBy == 'desc') || ($orderBy == 'asc') ? $data->orderBy($orderBy, $orderAt) : $data;
    if ($param == 'get') {
        $data = $data->get();
    } elseif ($param == 'paginate') {
        $data = $data->paginate($paginate);
    }
    if ($param == 'first') {
        $data = $data->first();
    }
    return response()->json([
        'data' => $data,
        'message' => __("success message"),
        'status' => true
    ], 200);
}
function trip_status($status)
{
    $statusArr = ['end' => 1, 'work' => 0];
    return $statusArr[$status];
}
/**
 * Summary of get_nearest_timezone
 * @param mixed $school_id
 * @param mixed $cur_lat
 * @param mixed $cur_long
 * @param mixed $country_code
 * @return string
 */
function get_nearest_timezone($school_id = false, $format = 'Y-m-d', $cur_lat = 31.056233387663298, $cur_long = 31.399663440341342, $country_code = '')
{
    switch (is_numeric($school_id)) {
        case true:
            $school = School::find($school_id);
            $cur_lat = $school->latitude;
            $cur_long = $school->longitude;
            break;
        case false:
            $school = $school_id;
            $cur_lat = $school->latitude;
            $cur_long = $school->longitude;
            break;
    }
    $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
        : DateTimeZone::listIdentifiers();

    if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

        $time_zone = '';
        $tz_distance = 0;

        //only one identifier?
        if (count($timezone_ids) == 1) {
            $time_zone = $timezone_ids[0];
        } else {

            foreach ($timezone_ids as $timezone_id) {
                $timezone = new DateTimeZone($timezone_id);
                $location = $timezone->getLocation();
                $tz_lat   = $location['latitude'];
                $tz_long  = $location['longitude'];

                $theta    = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                    + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                // echo '<br />'.$timezone_id.' '.$distance;

                if (!$time_zone || $tz_distance > $distance) {
                    $time_zone   = $timezone_id;
                    $tz_distance = $distance;
                }
            }
        }
        $dateTime = new DateTime('now', new DateTimeZone($time_zone));
        $currentTimes[$time_zone] = $dateTime->format($format);
        return  $currentTimes[$time_zone];
    }
    return 'unknown';
}
/**
 * Summary of latest
 * @param object $OPJ
 * @param array $data
 * @param int $take
 * @return mixed
 */
function latest(object $OPJ, array $data = [], int $take = 5)
{
    $keys = array_keys($data);
    $keys_count = count($keys);
    switch ($keys_count) {
        case 0:
            return $OPJ::latest()->take($take)->get();
        default:

            return where($OPJ,  $data)->latest()->take($take)->get();
    }
}
function get(object $OPJ, array $data = [])
{
    $keys = array_keys($data);
    $keys_count = count($keys);

    switch ($keys_count) {
        case 0:
            return $OPJ::get();
        default:

            return where($OPJ,  $data)->get();
    }
}
function where(object $OPJ, array $data = [])
{
    $keys = array_keys($data);

    $opj = $OPJ::where($keys[0], $data[$keys[0]]);

    for ($i = 1; $i < count($keys); $i++) {
        $opj->where($keys[$i], $data[$keys[$i]]);
    }

    return $opj;
}


/**
 * Summary of removeAbsence
 * @param object $AbsenceOpj
 * @param mixed $request
 * @param mixed $student_id
 * @param mixed $attendence_type
 * @return string
 */
function removeAbsence(object $AbsenceOpj, $request, $student_id, $attendence_type)
{
    $current_date = Carbon::now()->format('Y-m-d');
    $absence = $AbsenceOpj::where('attendence_date', $current_date)->where('student_id', $student_id)->where('attendence_date', $current_date)->whereIn('attendence_type', [$attendence_type, 'full_day']);
    $reverseAttendence_type = $attendence_type == 'end_day' ? 'start_day' : 'end_day';
    if ($absence->exists()) {
        $student_absence = $absence->first();
        $type = $student_absence->attendence_type;
        if ($type == 'full_day') {

            $student_absence->attendence_type = $reverseAttendence_type;
            $student_absence->updated_by = $request->user()->type;
            $student_absence->save();
        } else if ($type == $attendence_type) {
            $student_absence->delete();
        }
    } else {
        return __('This student is not absent');
    }
    return __('Moved to waiting');
}
//timezone for one NY co-ordinate
//send verification email confirmation number
/**
 * Summary of getAbsenceByStudent
 * @param object $ObjectVerfication
 * @param mixed $user
 * @return void
 */
function sendCodeVerfication(object $ObjectVerfication, $user)
{
    $code = mt_rand(100000, 999999); // Generate a random verification code
    $data['user_id'] = $user->id;
    $data['code'] = $code;

    // Delete any existing verification codes for this user
    // $ObjectVerfication::whereNotNull('user_id')->where(['user_id' => $data['user_id']])->delete();
        // Delete any existing verification codes for this user
        $ObjectVerfication::where('user_id', $user->id)->delete(); // Access the model dynamically

        // Create a new verification record using the model name
        $verification = $ObjectVerfication::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);

        // Generate the activation link with a temporary signed route and add the model_name as a query parameter
        $activationLink = URL::temporarySignedRoute(
            'user.activate', // The route name for user activation
            Carbon::now()->addMinutes(60), // Link expiration time (1 hour)
            ['id' => $user->id,'model_name' => class_basename($verification)], // Pass the user ID to the route
        ) ;

        // Prepare the message content
        $message = "Thank you for registering on Busaty App. Your activation code is <b>{$code}</b><br>";
        $message .= "To activate your account, please click the link below:<br>";
        $message .= "<a href='{$activationLink}'>Activate Your Account</a>";

        // Prepare the mail data
        $mailData = [
            'recipient' => $user->email,
            'subject' => 'Email Verification',
            'body' => $message,
            'code' => $code,
            'activation_link' => $activationLink, // Include the activation link
            'model_name' => $ObjectVerfication, // Pass the model name
            'email' => $user->email,
        ];
Mail::to($user->email)->send(new SenVerificationCode($mailData));

    try {


        // return $user->notify(new SendVerificationCodeNotification($user, $ObjectVerfication));

        // Attempt to send the verification email
        // Mail::to($mail_data['recipient'])->send(new SenVerificationCode($mail_data));
    } catch (\Exception $ex) {
        // Return an error message if the email fails to send
        return [
            'message' => __('Failed to send verification email. Please try again with a valid email.'),
            'error' => $ex,
            'status' => false,
            'code' => 556,
        ];
    }

    // Return a success message if the email is sent successfully
    return [
        'message' => __('Verification email sent successfully.'),
        'status' => true,
        'code' => 200,
    ];
}


function firebase_token($user, $request)
{
    if (!empty($request->firebase_token)) {
        $user->firebase_token = $request->firebase_token;
        $user->save();
    }
}
function settings()
{
    return Settings::first();
}
function firebase_tokenArrayRes($elements)
{
    // $elements=$elements->toArray();
    $allFirebaseTokens = [];
    $elements = $elements->toArray();
    foreach ($elements as $in => $element) {

        $allFirebaseTokens[$in] =  $element['firebase_token'];
    }
    $allFirebaseTokens = array_unique($allFirebaseTokens);

    $allFirebaseTokens = array_filter($allFirebaseTokens, function ($value) {
        return !is_null($value) && $value !== '';
    });
    return $allFirebaseTokens;
}
function convertArabicToWesternNumerals($value)
{
    $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $westernNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    return str_replace($arabicNumerals, $westernNumerals, $value);
}
function convertToClass($originalText)
{
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
// function textToClass($originalText ,array $classes)
// {
//     preg_match('/\{\{(.*?)\}\}/', $originalText, $matches);
//     if (isset($matches[1])) {
//         $model = $matches[1]; // الموديل داخل {{ }}
//     } else {
//         die("لا توجد كلمة بين {{ }}.");
//     }

//     // استخراج النص بعد السهم ->
//     $arrowPosition = strpos($originalText, '->');
//     if ($arrowPosition !== false) {
//         $parameter = substr($originalText, $arrowPosition + 2); // الجزء بعد ->
//     } else {
//         die("لا يوجد سهم -> في النص.");
//     }

//     // عرض النتائج
//     echo "الموديل: $model\n";
//     echo "المعامل: $parameter\n";
//   // استخدام الكلمة بين {{ }} كموديل ديناميكيًا
//   $modelName = ucfirst($model); // تحويل الكلمة إلى صيغة العنوان لتناسب أسماء الموديلات
//   $fullModelClass = "App\\Models\\$modelName";
//   if (in_array("Irix", $classes)) {
//     $classes[$fullModelClass];
//         $result = $classes[$fullModelClass]->$parameter();
//         return $result;
//   }
// }


// example
// $classes = [
//     "App\\Models\\User" => new User(),
//     "App\\Models\\Post" => new Post(),
// ];

function textToClass($originalText, array $classes)
{
    // Use preg_replace_callback to replace all instances of {{model}}->parameter
    $data = preg_replace_callback('/\{\{(.*?)\}\}->(.*?)\s/', function ($matches) use ($classes) {
        $model = $matches[1]; // The model inside {{ }}
        $parameter = trim($matches[2]); // Part after ->

        // Capitalize the first letter to match model names
        $modelName = ucfirst($model);
        $fullModelClass = "App\\Models\\$modelName";

        // Check if the model class exists in the array and if it has the required method
        if (isset($classes[$fullModelClass])) {
            $modelInstance = $classes[$fullModelClass];

            // Retrieve the parameter value from the model instance
            if (isset($modelInstance->$parameter)) {
                return $modelInstance->$parameter . ' '; // Return the value and add a space
            } else {
                throw new Exception("المعلمة '$parameter' غير موجودة في الموديل '$modelName'.");
            }
        } else {
            throw new Exception("الموديل '$modelName' غير موجود.");
        }
    }, $originalText.'  ');

    return $data;
}
function getClassData($originalText)
{
    $data = preg_replace_callback('/\{\{(.*?)\}\}->(.*?)\s/', function ($matches) {
        $model = $matches[1]; // The model inside {{ }}
        $parameter = trim($matches[2]); // Part after ->

        // Capitalize the first letter to match model names
        $modelName = ucfirst($model);
        $fullModelClass = "App\\Models\\$modelName";

        // Attempt to instantiate the model and retrieve the parameter
        try {
            $modelInstance = $fullModelClass::first();

            if ($modelInstance === null) {
                throw new Exception("لا يوجد سجل لموديل '$modelName'.");
            }

            // Check if the property exists on the model instance

            return ' '.$modelInstance[$parameter].' ' ; // Return the value and add a space
        } catch (Throwable $e) {
            throw new Exception("خطأ في استرجاع الموديل '$modelName': " . $e->getMessage());
        }
    }, $originalText . ' ');

    return $data;
}



function removeClassWord($originalText)
{
    // Use a regular expression to match any pattern like {{Model}}->something
    $pattern = '/\{\{.*?\}\}->\w+/';

    // Replace the matched pattern with a general placeholder
    $replacement = ' ';

    // Perform the replacement
    $remainingText = preg_replace($pattern, $replacement, $originalText);

    // Trim any extra spaces and return the result
    return trim($remainingText);
}



// function langHeader(){
//         $locale = request()->header('ln') ?? config('app.fallback_locale');
//         return $locale;
// }
// function textToClass($originalText, array $classes, $newMethodName)
// {
//     // Extract the model inside {{ }}
//     preg_match('/\{\{(.*?)\}\}/', $originalText, $matches);
//     if (!isset($matches[1])) {
//         throw new Exception("لا توجد كلمة بين {{ }}.");
//     }

//     $model = $matches[1]; // The model inside {{ }}

//     // Remove the arrow and any text after it
//     $baseMethodName = preg_replace('/->.*$/', '', $originalText);

//     // Create the new full method name by appending the new method name
//     $fullMethodName = trim($baseMethodName) . $newMethodName;

//     // Update $originalText to reflect the new method
//     $updatedOriginalText = str_replace($baseMethodName, $fullMethodName, $originalText);

//     // Dynamically use the word between {{ }} as a model
//     $modelName = ucfirst($model); // Capitalize the first letter to match model names
//     $fullModelClass = "App\\Models\\$modelName";

//     // Check if the model class exists in the array and if it has the required method
//     if (isset($classes[$fullModelClass])) {
//         $modelInstance = $classes[$fullModelClass];

//         // Call the new method dynamically and return the result
//         if (method_exists($modelInstance, $fullMethodName)) {
//             $result = $modelInstance->$fullMethodName();
//             return [
//                 'updatedText' => $updatedOriginalText, // Return the updated text
//                 'result' => $result, // Return the result of the method call
//             ];
//         } else {
//             throw new Exception("الموديل $fullModelClass لا يحتوي على المعامل $fullMethodName.");
//         }
//     }

//     throw new Exception("الموديل $fullModelClass غير موجود في المجموعة.");
// }
