<?php

class CustomTelegram extends Telegram
{
    public function endpoint($api, array $content, $post = true)
    {
        $url = '127.0.0.1:8081/bot'.$this->bot_token.'/'.$api;
        if ($post) {
            $reply = $this->sendAPIRequest($url, $content);
        } else {
            $reply = $this->sendAPIRequest($url, [], false);
        }

        return json_decode($reply, true);
    }

}
