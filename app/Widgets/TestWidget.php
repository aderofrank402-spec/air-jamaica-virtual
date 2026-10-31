<?php

namespace App\Widgets;

use App\Contracts\Widget;
use Illuminate\Support\Facades\Auth;

/**
 * Simple test widget to verify widget system is working
 */
class TestWidget extends Widget
{
    public function run()
    {
        $user = Auth::user();
        
        return view('widgets.test_widget', [
            'user' => $user,
            'message' => 'Widget system is working!'
        ]);
    }
}