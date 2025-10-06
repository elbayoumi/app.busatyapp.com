<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class UserActivationService
{
    /**
     * Send the activation link to the user with encrypted data.
     *
     * @param  $user
     * @return void
     */
    public function sendActivationLink( $user)
    {
        // Encrypt the model name and user ID before sending the link
        $modelData = Crypt::encrypt([
            'model' => get_class($user), // Get the class name (model) of the user
            'id' => $user->id // The user's ID
        ]);

        // Generate the activation link with the encrypted data
        $activationLink = URL::temporarySignedRoute(
            'user.activate', // The route name for user activation
            Carbon::now()->addMinutes(60), // Link expiration time (1 hour)
            ['data' => $modelData] // Pass the encrypted model data
        );

        // Send the activation link to the user via notification
        $user->notify(new UserActivated($activationLink, $user));
    }

    /**
     * Activate the user by decrypting the data from the activation link.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        // Validate the signed route (to make sure the link is valid)
        $request->validateSignedRoute();

        // Decrypt the data from the link
        $encryptedData = $request->query('data');
        $modelData = Crypt::decrypt($encryptedData); // Decrypt the encrypted data

        // Extract model class name and user ID from the decrypted data
        $modelClass = $modelData['model']; // The class name (model)
        $userId = $modelData['id']; // The user's ID

        // Retrieve the model instance based on the decrypted data
        $model = $modelClass::findOrFail($userId); // Find the user by ID

        // Activate the user by updating the email_verified_at field
        $model->email_verified_at = now(); // Set the email_verified_at timestamp to current time
        $model->save(); // Save the updated model

        // You can add additional logic if needed (e.g., notify the user that their account is activated)
    }
}
