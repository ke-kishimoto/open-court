<?php
require_once(__DIR__.'/../dao/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/InquiryDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/UsersDao.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/BaseEntity.php');
require_once('./model/entity/Config.php');
require_once('./model/entity/Participant.php');
require_once('./model/entity/Inquiry.php');
require_once('./model/entity/Users.php');
require_once('./service/UserService.php');
require_once(__DIR__.'/../../controller/api/LineApi.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use api\LineApi;
use entity\Inquiry;
use entity\Participant;
use service\UserService;

class LineApiTest extends TestCase
{

    use TestCaseTrait;

    /**
     * @return PHPUnit\Extensions\Database\DB\IDatabaseConnection
     */
    public function getConnection()
    {
        $pdo = new PDO('mysql:dbname=eventman_test;host=127.0.0.1:8889', 'root', 'root');
        return $this->createDefaultDBConnection($pdo, 'eventman_test');
    }

    public function getDataSet()
    {
        return new MyApp_DbUnit_ArrayDataSet(
            [
                'config' => [
                    [
                        'id' => 1,
                        'line_token' => 'hzgfhEZuegOKyg8tcY6j6wAGLCeKjof2UMiKsg4OJMX',
                        'system_title' => 'event calendar',
                        'bg_color' => 'orange',
                        'logo_img_path' => '',
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23',
                        'waiting_flg_auto_update' => '1',
                        'client_id' => '1656224816',
                        'client_secret' => '',
                        'channel_access_token' => '',
                    ],
                    
                ],
                'inquiry' => [
                    [
                        'id' => 1,
                        'game_id' => 1,
                        'name' => 'test taro',
                        'email' => 'test@test.com',
                        'content' => '問い合わせ内容',
                        'status_flg' => 0,
                        'register_date' => '2020-01-01 17:15:23',
                        'update_date' => '2020-01-01 17:15:23'
                    ],
                ],
                'users' => [

                ]
            ]
        );
    }

    // 予約の通知
    public function testReserveNotify()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();

        $participant = new Participant();
        $participant->name = 'kishimoto';
        $participant->email = 'kishimoto@gmail.com';
        $participant->occupation = 1;
        $participant->sex = 1;

        $result = $api->reserve_notify($participant, 'イベントサンプル', '2020-01-01', 1);
        $this->assertTrue($result);

    }

    // キャンセルの通知
    public function testCancelNotify()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();

        $participant = new Participant();
        $participant->name = 'kishimoto';

        $result = $api->cancel_notify($participant->name, 'イベントサンプル', '2020-01-01', 1);
        $this->assertTrue($result);
    }

    // 複数件の予約
    public function testMultipleReserve()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();

        $result = $api->multiple_reserve('ksuke', 3);
        $this->assertTrue($result);

    }
    // お問い合わせ
    public function testiInquiry()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $api = new LineApi();
        $inquiry = new Inquiry();

        $inquiry->gameTitle = 'ビギナーズ';
        $inquiry->name = 'ksuke';
        $inquiry->email = 'kishimoto@gmail.com';
        $inquiry->content = 'はじめました。';

        $result = $api->inquiry($inquiry);
        $this->assertTrue($result);

    }

    // LINEログイン
    public function testGetAccessToken()
    {
        $api = new LineApi();
        $code = 'MnO8AZnSyYJ8uTKmtGLb'; // リダイレクトの後にURLパラメータで取得可能
        $response = $api->getAccessToken($code);

        // var_dump($response);

        $accessToken = $response->access_token;
        $idToken = $response->id_token;

        $response = $api->tokenVerify($idToken);
        var_dump($response);

        // $response = $api->getLineProfile($accessToken);
        // var_dump($response);

        // $response = $api->getLineProfileByCode($code);
        // $this->assertEquals($response->displayName, "岸本 けーすけ");
        $this->assertEquals($response->name, "岸本 けーすけ");

        // $this->assertTrue($response);
    }

    public function testLineLogin()
    {
        $service = new UserService();
        $code = 'pMkenSzWtYXy6B7P9lvm';
        $user = $service->lineLogin($code);

        // var_dump($user);

        // 新規登録の場合
        $this->assertEquals("岸本 けーすけ", $user['name']);
    }

    public function testReservationPushMessage()
    {
        $api = new LineApi();
        $userId = '';
        $result = $api->pushMessage($userId, 'message');

        var_dump($result);
        $this->assertEquals((int)($result->status), 0);
    }

    public function testWebhook()
    {
        $api = new LineApi();

        $url = 'http://localhost:8888/api/line/webhook';
        $ch = curl_init($url);
        $headers = [
            "Content-Type: application/json",
        ];
        $data = json_encode([
            "destination" => "xxxxxxxxxx",
            "events" => [
                [
                  "replyToken" => "0f3779fba3b349968c5d07db31eab56f",
                  "type" => "message",
                  "mode" => "active",
                  "timestamp" => 1462629479859,
                  "source" => [
                    "type" => "user",
                    "userId" => "U4af4980629..."
                    ],
                  "message"=> [
                    "id" => "325708",
                    "type" => "text",
                    "text" => "予約"
                  ]
                ],
                [
                    "replyToken" => "8cf9239d56244f4197887e939187e19e",
                    "type" => "follow",
                    "mode" => "active",
                    "timestamp"=> 1462629479859,
                    "source"=> [
                      "type" => "user",
                      "userId" => "U4af4980629..."
                ]
                ]
            ]
        ]);
        curl_setopt($ch, CURLOPT_POST, TRUE);  //POSTで送信
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); //データをセット
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //受け取ったデータを変数に

        curl_exec($ch);
        // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // ステータスコードを受け取る
        curl_close($ch);
        // $response = json_decode($result);

    }

}