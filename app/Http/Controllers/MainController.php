<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class MainController extends Controller
{
    public function index()
    {
        $telegram = new Telegram( env('TELEGRAM_BOT_TOKEN') , $proxy=['127.0.0.1:8081']);
        $chat_id = $telegram->ChatID();
        $message = $telegram->Text();
        $content = array('chat_id' => $chat_id, 'text' => $message);
        $telegram->sendMessage($content);

//        $video= 'https://dl7.digimoviz.top/www2/film/1401/12/Luther.The.Fallen.Sun.2023.480p.WEB-DL.HardSub.DigiMoviez.mp4';
//        $video= '/var/www/html/bakhtakMovie/public/v.mp4';
//        $content = array('chat_id' => $chat_id, 'video' => $video, 'caption' => 'TEST');
//        $telegram->sendVideo($content);

        $video= curl_file_create('/var/www/html/bakhtakMovie/public/v.mp4','video/mp4'); // Must be on the same hosting filesystem
        $content = array('chat_id' => $chat_id, 'video' => $video, 'length'=> '639'); //There was a stange bug with the lenght
        $telegram->sendVideo($content);
    }
}



//$video= curl_file_create('filename.mp4','video/mp4'); // Must be on the same hosting filesystem
//$content = array('chat_id' => $chat_id, 'video' => $video, 'length'=> '639'); //There was a stange bug with the lenght
//$telegram->sendVideo($content);
//
//
//
//$video= 'https://static.cdn.asset.aparat.com/avt/12161828_15s.mp4';
//$content = array('chat_id' => $chat_id, 'video' => $video, 'caption' => 'TEST');
//$telegram->sendVideo($content);
