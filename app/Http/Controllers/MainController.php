<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class MainController extends Controller
{
    public function index()
    {
        dd("iman");
        $telegram = new Telegram(env('TELEGRAM_BOT_TOKEN'));
        $chat_id = $telegram->ChatID();
        $content = array('chat_id' => $chat_id, 'text' => 'Hi');
        $telegram->sendMessage($content);
    }
}
