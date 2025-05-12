<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    /**
     * 把全域共享資料 (shared props) 注入前端
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            // ✅ flash：單行訊息（success / info 皆用這欄位）
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],

            // ✅ errors：保留 Laravel 原本的錯誤 Bag，
            //    另外再加一個「第一條錯誤訊息」方便前端顯示
            'errors' => fn () => [
                'bag'     => $request->session()->get('errors')?->getMessages() ?? [],
                'message' => $request->session()->get('errors')?->first(),
            ],
        ]);
    }
}
