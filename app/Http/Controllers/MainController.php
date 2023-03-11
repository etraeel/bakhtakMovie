<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helper\CustomTelegram;
use App\Jobs\DownAndUpMovie;

class MainController extends Controller
{
    public function index()
    {
        $telegram = new CustomTelegram( env('TELEGRAM_BOT_TOKEN'));
        $chat_id = $telegram->ChatID();
        $message = $telegram->Text();
        if($message == 'DOO'){
            $content = array('chat_id' => $chat_id, 'text' => $message);
            $telegram->sendMessage($content);

            DownAndUpMovie::dispatch($chat_id);
//            $video= 'http://dl7.digimoviz.top/www2/film/1401/12/Luther.The.Fallen.Sun.2023.480p.WEB-DL.HardSub.DigiMoviez.mp4';
//            $content = array('chat_id' => $chat_id, 'video' => $video);
//            $telegram->sendVideo($content);

//        $video= curl_file_create('/tmp/v.mp4','video/mp4'); // Must be on the same hosting filesystem
//        $content = array('chat_id' => $chat_id, 'video' => $video, 'length'=> '639'); //There was a stange bug with the lenght
//        $telegram->sendVideo($content);
        }

    }
}


