<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;



function getAppsName() {
    $filesArray = [];

    $files = File::files(base_path('routes/Api'));

    foreach ($files as $file) {
        $filesArray[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    }

    return $filesArray;
}

// if (!function_exists('settings')) {
//     function settings(){

//         $settings = Settings::first();
//         return $settings;
//     }
// }

function switchLocaleUrl($locale = 'ar')
{
    if ($locale === 'ar') {
        return '/ar/' . collect(Request::segments())->splice(1)->implode('/');
    }
    return '/en/' . collect(Request::segments())->splice(1)->implode('/');

}


/**
 * @param $route
 * @return string
 */
function activateRouteClass($route)
{
    if (! isRouteActive($route)) {
        return '';
    }
    return 'class=active';
}

/**
 * @param $numbers
 * @return mixed
 */
function translateNumbers($numbers)
{
    // Don't translate if the locale is English
    if (config('app.locale') === 'en') {
        return $numbers;
    }

    $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];
    $english = ['9', '8', '7', '6', '5', '4', '3', '2', '1', '0'];

    return str_replace($english, $arabic, $numbers);
}

function translateCurrency($currency)
{
    // Don't translate if the locale is English
    if (config('app.locale') === 'en') {
        return $currency;
    }

    $arabic = ['جنيه', 'دولار', 'يورو', 'درهم اماراتي', 'ريال سعودي', 'جنيه استرليني'];
    $english = ['EGP', 'USD', 'EU', 'AED', 'SAR', 'GBP'];

    return str_replace($english, $arabic, $currency);
}



function isActive($names)
{

    if (is_array($names)) {
        $is_active = false;
        foreach ($names as $name) {
            $current_route_name = \Route::current()->getName();
            $current_route_array = explode('.', $current_route_name);
            if (in_array($name, $current_route_array)) {
                $is_active = true;
            }
        }
        return $is_active;
    } else {
        $current_route_name = \Route::current()->getName();
        $current_route_array = explode('.', $current_route_name);
        if (in_array($names, $current_route_array)) {
            return true;
        }
        return false;
    }


}


function itemIsActive($name, $action)
{
    $current_route_name = \Route::current()->getName();
    $current_route_array = explode('.', $current_route_name);
    if (in_array($name, $current_route_array) && in_array($action, $current_route_array)) {
        return true;
    }
    return false;
}


function active($name)
{
    $current_route_name = \Route::current()->getName();
    $current_route_array = explode('.', $current_route_name);
    if (in_array($name, $current_route_array)) {
        return true;
    }
    return false;
}

function __e($ar,$en)
{
    return app()->getLocale() == 'ar'?  $ar : $en;
}


function exerpt_text($text, $length)
{

    $max_length = $length;

    if (strlen($text) > $max_length)
    {
        $offset = ($max_length - 3) - strlen($text);
        $text = substr($text, 0, strrpos($text, ' ', $offset)) . '...';
    }

    return $text;
}


function image_or_placeholder($image, $type = 'main_placeholder')
{
    // if ($image != null && file_exists(public_path('storage') . '/' . $file . '/' . $image)) {
    //     return asset('storage') . '/' . $file . '/' . $image;
    // }
    if ($image == null) {
        switch ($type) {
            case 'profile':
                return asset('assets/dashboard') . '/' . 'profile_placeholder.png';
                break;

            default:
                return asset('assets/dashboard') . '/' . 'placeholder_image.jpg';
                break;
        }
    }
    return $image;
}



// function save_images(object $object, array|object $images, string $type, string $directory = '')
// {
//     if (is_array($images)) {
//         $full_urls = [];
//         foreach ($images as $image) {
//             $fileExt = $image->getClientOriginalExtension();
//             $fileNameNew        = uniqid() . time() . '.' . $fileExt;
//             $image->storeAs('storage/' . $directory, $fileNameNew);

//             $object->images()->create([
//                 'src' => 'storage/'. $directory."/".$fileNameNew,
//                 'type' => $type
//             ]);
//             array_push($full_urls, asset('storage/' . $directory . '/' . $fileNameNew));
//         }
//         return $full_urls;

//     } else {
//         $fileExt = $images->getClientOriginalExtension();
//         $fileNameNew        = uniqid() . time() . '.' . $fileExt;
//         $images->storeAs('public/' . $directory, $fileNameNew);

//         $object->images()->create([
//             'src' => $fileNameNew,
//             'type' => $type
//         ]);
//         return asset('storage/' . $directory . '/' . $fileNameNew);
//     }
//     return null;

// }
function save_images(object $object, array|object $images, string $type, string $directory = '')
{
    $saveImage = function($image) use ($object, $type, $directory) {
        $fileExt = $image->getClientOriginalExtension();
        $fileNameNew = uniqid() . time() . '.' . $fileExt;
        $filePath = $directory ? $directory . '/' . $fileNameNew : $fileNameNew;

        // Store image in 'public' disk
        $image->storeAs($directory, $fileNameNew, 'public');

        $object->images()->create([
            'src' => 'storage/' . $filePath,
            'type' => $type
        ]);

        return asset('storage/' . $filePath);
    };

    if (is_array($images)) {
        $full_urls = array_map($saveImage, $images);
        return $full_urls;
    } else {
        return $saveImage($images);
    }

    return null;
}


function check_relition_id($relition_id) {

    if (isset($relition_id)) {
        $relition_id = $relition_id;
    }else {
        $relition_id = '';

    }


    return $relition_id;

}


function check_relition_name($relition_name) {

    if (isset($relition_name)) {
        $relition_name = $relition_name;
    }else {
        $relition_name = 'غير معرف';

    }


    return $relition_name;

}

function delete_duplicate_and_null_array($students){
    $allFirebaseTokensSchools = [];

    foreach ($students as $student) {
        $allFirebaseTokensSchools = array_merge($allFirebaseTokensSchools, $student);
        $allFirebaseTokensSchools = array_unique($allFirebaseTokensSchools);
    }
    $allFirebaseTokensSchools=array_filter($allFirebaseTokensSchools, function($value) { return !is_null($value) && $value !== ''; });
    return $allFirebaseTokensSchools;
}
function daysDifference($startDate,$endDate){

    $startDate = \Carbon\Carbon::parse($startDate);
    $endDate = \Carbon\Carbon::parse($endDate);
    $daysDifference = $startDate->diffInDays($endDate);

return $daysDifference;
}
function statusAr($index=null){
        $status = [
            'subscribed' => 'مشترك',

            'cancel' => 'ملغاة',
            'pending' => 'قيد الانتظار',
            'not_subscribed' => 'غير مشترك',
            'expired' => 'انتهت الصلاحية',
            'trial' => 'تجريبي',
            'coupon' => 'بطاقة خصم',

        ];
        return $status[$index]??$status;
}
function radCode(){
    return rand(1, 9).(rand(1, 9)*rand(1, 9)).(rand(1, 9)*rand(1, 9));
}
function AuthU ()
{
    return auth()->guard('web')->user();
}
