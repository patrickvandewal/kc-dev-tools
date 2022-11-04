<?php

namespace KingsCode\DevTools\Http\Controllers;

use Illuminate\Routing\Controller;

class DevToolsController extends Controller
{
    /**
     * Default view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function overview()
    {
        return 'hi';
    }
}
