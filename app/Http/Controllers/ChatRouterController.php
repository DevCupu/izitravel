<?php

namespace App\Http\Controllers;

use App\Services\ChatRouterService;
use Illuminate\Http\Request;

class ChatRouterController extends Controller
{
    public function redirect(Request $request, ChatRouterService $router)
    {
        $result = $router->route($request);

        return view('chat', $result);
    }
}
