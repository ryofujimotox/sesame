<?php
use PHPUnit\Framework\TestCase;
use Sesame\Sesame;

// env読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class SesameTest extends TestCase {
    // 動作確認
    public function testSample() {
        $this->checkEnv();

        $cad = new Sesame($_ENV['UUID'], $_ENV['SECRET_KEY'], $_ENV['API_KEY']);
        $status = $cad->status();
        var_dump($status);
        exit;
    }

    // env設定しているか確認する
    public function checkEnv() {
        $setted = isset($_ENV['UUID'], $_ENV['SECRET_KEY'], $_ENV['API_KEY']);
        if (!$setted) {
            echo '.envにキーを設定してください。';
            exit;
        }
    }
}
