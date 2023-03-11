<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Helper\CustomTelegram;


class DownAndUpMovie implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $channelId;

    /**
     * Create a new job instance.
     */

    public function __construct($channelId)
    {
        $this->channelId = $channelId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $workingDirectory = '/tmp/';
        $channelId = $this->channelId;
        $url = 'http://dl7.digimoviz.top/www2/film/1401/12/Luther.The.Fallen.Sun.2023.480p.WEB-DL.HardSub.DigiMoviez.mp4';
        $fileName = last(explode('/' , $url));
        $fileDownloaded = false;

        $telegram = new CustomTelegram( env('TELEGRAM_BOT_TOKEN'));
        $content = array('chat_id' => $channelId, 'text' => "Downloading!!");
        $telegram->sendMessage($content);

        if (!file_exists($workingDirectory.$fileName)) {
            $result = shell_exec("aria2c -x 16 -s 16  --dir=$workingDirectory");
            if($result == 0){
                $fileDownloaded = true;
            }
        }else{
            $fileDownloaded = true;
        }
        if($fileDownloaded){

            $video= curl_file_create($workingDirectory.$fileName,'video/mp4'); // Must be on the same hosting filesystem
            $content = array('chat_id' => $channelId, 'video' => $video, 'length'=> '639'); //There was a stange bug with the lenght
            $response = $telegram->sendVideo($content);

            $message = json_encode($response);
            if($response['ok'] == true){
                $message = $response['result']['video']['file_id'];
            }
            $telegram = new CustomTelegram( env('TELEGRAM_BOT_TOKEN'));
            $content = array('chat_id' => $channelId, 'text' => $message);
            $telegram->sendMessage($content);
        }


    }
}
