<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostmanController extends Controller
{
    public function showUploadForm()
    {
        return view('postman.upload');
    }

    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $filePath = $request->file('file')->store('uploads');

        // قم بتخزين المسار في الجلسة أو قاعدة البيانات حسب الحاجة
        session(['postman_file' => $filePath]);

        return redirect()->route('postman.view');
    }

    public function viewContent()
    {
        // $fileUrl = asset('storage/api/api.json'); // رابط الملف

        // // جلب محتوى الملف عبر HTTP
        // $response = Http::get($fileUrl);

        // if ($response->failed()) {
        //     return redirect()->route('postman.upload.form')->with('error', 'Unable to fetch the file.');
        // }

        // $fileContent = $response->body(); // محتوى الملف
        // $data = json_decode($fileContent, true); // تحويل إلى JSON

        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     return redirect()->route('postman.upload.form')->with('error', 'Invalid JSON format.');
        // }
        // $fileUrl = 'path/to/your/file.json';  // المسار المحلي للملف
        // $decodedText = file_get_contents($fileUrl);
        // if ($decodedText === false) {
        //     dd("Error reading file");
        // }
        // $myArray = json_decode($decodedText, true);
        // dd($decodedText);  // لمراجعة محتوى الملف


        $bus_stage="https://stage.busatyapp.com";
        $busat="https://test.busatyapp.com";

        return view('postman.view', compact('bus_stage','busat'));
    }


}
