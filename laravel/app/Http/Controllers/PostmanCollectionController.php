<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostmanCollectionController extends Controller
{
    public function import(Request $request)
    {
        // قراءة الملف
        $filePath = $request->file('postman_file')->getPathname();
        $jsonContent = File::get($filePath);
        $collection = json_decode($jsonContent, true);

        // حفظ الكوليكشن
        $collectionId = DB::table('collections')->insertGetId([
            'name' => $collection['info']['name'] ?? 'Unnamed Collection',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // حفظ الـ APIs
        foreach ($collection['item'] as $item) {
            if (isset($item['item'])) {
                foreach ($item['item'] as $subItem) {
                    $this->storeApiEndpoint($collectionId, $subItem);
                }
            } else {
                $this->storeApiEndpoint($collectionId, $item);
            }
        }

        return response()->json(['message' => 'Collection imported successfully.']);
    }

    private function storeApiEndpoint($collectionId, $apiItem)
    {
        DB::table('api_endpoints')->insert([
            'collection_id' => $collectionId,
            'name' => $apiItem['name'] ?? 'Unnamed API',
            'method' => $apiItem['request']['method'] ?? 'GET',
            'url' => $apiItem['request']['url']['raw'] ?? '',
            'headers' => json_encode($apiItem['request']['header'] ?? []),
            'body' => json_encode($apiItem['request']['body'] ?? []),
            'auth' => json_encode($apiItem['request']['auth'] ?? []),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function getAllCollections()
    {
        return DB::table('collections')->select('id', 'name', 'created_at')->get();
    }

    public function getCollectionApis($collectionId)
    {
        return DB::table('api_endpoints')
            ->where('collection_id', $collectionId)
            ->select('id', 'name', 'method', 'url')
            ->get();
    }
}
