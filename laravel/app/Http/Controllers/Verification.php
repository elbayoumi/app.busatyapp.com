<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class Verification extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $id, $model_name) {
        // Validate the signed URL
        if (!URL::hasValidSignature($request)) {
            return view('auth.verification-failed');
            }

        // Retrieve the full model class from the URL parameter
        $fullModelClass = "App\\Models\\$model_name";

        // Validate the model class to ensure it is a valid and safe class name
        if (!class_exists($fullModelClass)) {
            return view('auth.verification-failed');
        }

        // Retrieve the verification record for the user from the specified model
        $model = $fullModelClass::where('user_id', $id)->first();
        // Check if the verification record exists
        if (!$model) {
            return view('auth.verification-failed');
        }

        // Retrieve the user from the model's relationship
        $user = $model->user; // Access the user via the relationship

        // Check if the user is retrieved successfully
        if (!$user) {
            return view('auth.verification-failed');
        }

        // Mark the user's email as verified
        $user->email_verified_at = 1;
        $user->save(); // Save the user data

        // Redirect to the login page or any other route with a success message
        return view('auth.verification-success');
    }

}
