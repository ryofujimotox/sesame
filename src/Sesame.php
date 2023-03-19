<?php
namespace Sesame;

use Sesame\Other\History;
use Sesame\Other\Utility;
use FrUtility\Other\Encrypt;

class Sesame {
    public $uuid;
    public $secret_key;
    public $api_key;

    public function __construct(string $uuid, string $secret_key, string $api_key) {
        $this->uuid = $uuid;
        $this->secret_key = $secret_key;
        $this->api_key = $api_key;
    }

    /**
     *
     * 施錠と解錠をトグルする
     * @return void
     */
    public function toggle(): void {
        //
        $history = base64_encode('WEB API');// 履歴に残る名前。半角しか使えないらしい
        $cmd = 88; // toggle:88/lock:82/unlock:83
        $sign = $this->getTimestampSign();
        $content = json_encode(compact('cmd', 'history', 'sign'));

        //
        $this->relay('cmd', 'POST', $content);
    }

    /**
     *
     * 現在のステータスを取得する
     *
     */
    public function status(): array {
        $data = $this->relay('', 'GET');

        $datetime = Utility::datetimeByTimestamp($data['timestamp']);
        $data['date'] = $datetime;

        return $data;
    }

    /**
     *
     * 履歴一覧の取得
     *
     * @param integer $page 0ページから~
     * @param integer $limit 1ページの履歴数
     * @return array 履歴
     */
    public function history(int $page = 0, int $limit = 10): History {
        $history = $this->relay("history?page={$page}&lg={$limit}", 'GET');
        $history = new History($history);
        return $history;
    }

    /**
     *
     * APIを呼び出す
     *
     * @param string $api
     * @param string $method
     * @param [type] $content
     * @return void|array
     */
    private function relay(string $api, string $method = 'GET', string $content = null) {
        $url = "https://app.candyhouse.co/api/sesame2/{$this->uuid}/{$api}";
        $header = [
            'Content-Type: application/json',
            'x-api-key: ' . $this->api_key
        ];
        $options = [
            'http' => compact('method', 'header', 'content')
        ];
        $result = file_get_contents($url, false, stream_context_create($options));
        if ($result) {
            return (array) json_decode($result);
        }
        return $result;
    }

    /**
     *
     * 著名データの取得
     *
     * @param string $key secret key?
     *
     * @return string
     *
     */
    public function getTimestampSign(): string {
        // big endianでバイナリ化されていたので、反転してlittle endianに変換
        $time = strtotime(date('Y/m/d H:i:s'));
        $msg = substr(strrev(hex2bin(dechex($time))), 1);

        //
        $key = hex2bin($this->secret_key);

        //
        $hashed = Encrypt::AES_CMAC($msg, $key);
        $sign = bin2hex($hashed);
        return $sign;
    }
}
