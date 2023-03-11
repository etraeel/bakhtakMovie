<?php

class CustomTelegram extends Telegram
{
    private $bot_token = '';
    private $data = [];
    private $updates = [];
    private $log_errors;
    private $proxy;
    private function sendAPIRequest($url, array $content, $post = true)
    {
        if (isset($content['chat_id'])) {
            $url = $url.'?chat_id='.$content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }
        // 		echo "inside curl if";
        if (!empty($this->proxy)) {
            // 			echo "inside proxy if";
            if (array_key_exists('type', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy['type']);
            }

            if (array_key_exists('auth', $this->proxy)) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy['auth']);
            }

            if (array_key_exists('url', $this->proxy)) {
                // 				echo "Proxy Url";
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy['url']);
            }

            if (array_key_exists('port', $this->proxy)) {
                // 				echo "Proxy port";
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(
                ['ok' => false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]
            );
        }
        curl_close($ch);
        if ($this->log_errors) {
            if (class_exists('TelegramErrorLogger')) {
                $loggerArray = ($this->getData() == null) ? [$content] : [$this->getData(), $content];
                TelegramErrorLogger::log(json_decode($result, true), $loggerArray);
            }
        }

        return $result;
    }
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
