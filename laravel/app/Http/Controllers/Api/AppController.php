<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{


    public function index(Request $request)
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', "exists:apps,name",'regex:/^[A-Za-z0-9-]+$/']
        ]);

        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        // Find the app by name
        $app = App::where('name', $request->name)->first();

        // Check if the app exists
        if (!$app) {
            return response()->json([
                'success' => false,
                'message' => 'App not found'
            ], 404);
        }

        // Return the app status
        return response()->json([
            'success' => true,
            'name' => $app->name,
            'status' => $app->status,
            'version' => $app->version,
            'is_updating' => $app->is_updating
        ]);
    }
    public function updating($name)
    {
        // Validate the name format manually
        if (!preg_match('/^[A-Za-z0-9-]+$/', $name)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid app name format'
            ], 400);
        }

        // Find the app by name
        $app = App::where('name', $name)->first();

        if (!$app) {
            return response()->json([
                'success' => false,
                'message' => 'App not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'name' => $app->name,
            'is_updating' => $app->is_updating,
            'message' => $app->is_updating ? 'App is currently updating' : 'App is available'
        ]);
    }

}
