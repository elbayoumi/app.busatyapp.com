<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnsubscribeController extends Controller
{
    public function oneClick(Request $request)
    {
        // اعمل لوجيك الإلغاء (مثلاً بعمدة users.unsubscribe = true)
        // أو استخدم البريد من بارامتر/توقيع
        // ...

        return response('You have been unsubscribed.', 200);
    }
}
