<?php

namespace App\Http\Controllers;

use App\Support\BotCommands;
use Illuminate\View\View;

class HelpController extends Controller
{
    public function show(): View
    {
        return view('help', [
            'sections' => BotCommands::sections(),
            'notifications' => BotCommands::notifications(),
            'badges' => BotCommands::badges(),
        ]);
    }
}
