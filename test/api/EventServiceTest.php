<?php
require_once(__DIR__.'/../dao/MyApp_DbUnit_ArrayDataSet.php');
require_once(__DIR__.'/../../controller/api/LineApi.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/DetailDao.php');
require_once('./model/dao/CompanionDao.php');
require_once('./model/dao/UsersDao.php');
require_once('./model/dao/GameInfoDao.php');
require_once('./model/dao/InquiryDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity/BaseEntity.php');
require_once('./model/entity/Config.php');
require_once('./model/entity/Participant.php');
require_once('./model/entity/Inquiry.php');
require_once('./model/entity/Companion.php');
require_once('./service/EventService.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use api\LineApi;
use entity\Inquiry;
use entity\Participant;
use service\EventService;
use dao\DetailDao;
use dao\CompanionDao;
use entity\Companion;

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
                        'waiting_flg_auto_update' => 1
                    ],
                ],
                'users' => [
                    [
                        'id' => 1,
                        'name' => 'aaa',
                        'email' => 'aaa@gmail.com',
                        'password' => '$2y$10$1hi3Da50nrt6tipP9gDPXerrhu.zrDEztvX5yIw0GeCCuCGHVS08i',
                    ],
                    [
                        'id' => 2,
                        'name' => 'bbb',
                        'email' => 'bbb@gmail.com',
                        'password' => '$2y$10$1hi3Da50nrt6tipP9gDPXerrhu.zrDEztvX5yIw0GeCCuCGHVS08i',
                    ],
                    [
                        'id' => 3,
                        'name' => 'ccc',
                        'email' => 'ccc@gmail.com',
                        'password' => '$2y$10$1hi3Da50nrt6tipP9gDPXerrhu.zrDEztvX5yIw0GeCCuCGHVS08i',
                    ]
                ],
                'game_info' => [
                    [
                        'id' => 1,
                        'title' => 'イベントタイトル',
                        'limit_number' => 2,
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 2,
                        'title' => 'イベントタイトル',
                        'limit_number' => 4,
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                    [
                        'id' => 3,
                        'title' => 'イベントタイトル',
                        'limit_number' => 4,
                        'delete_flg' => 1,
                        'price1' => 100,
                        'price2' => 200,
                        'price3' => 300,
                    ],
                ],
                'participant' => [
                    [
                        'id' => 1,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'aaa',
                        'email' => 'aaa@gmail.com',
                        'waiting_flg' => 0,
                        'delete_flg' => 1
                    ],
                    [
                        'id' => 2,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'bbb',
                        'email' => 'bbb@gmail.com',
                        'waiting_flg' => 0,
                        'delete_flg' => 1
                    ],
                    [
                        'id' => 3,
                        'game_id' => 1,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'ccc',
                        'email' => 'ccc@gmail.com',
                        'waiting_flg' => 1, // キャンセル待ち
                        'delete_flg' => 1
                    ],
                    [
                        'id' => 4,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'aaa',
                        'email' => 'aaa@gmail.com',
                        'waiting_flg' => 0,
                        'delete_flg' => 1
                    ],
                    [
                        'id' => 5,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'bbb',
                        'email' => 'bbb@gmail.com',
                        'waiting_flg' => 0,
                        'delete_flg' => 1
                    ],
                    [
                        'id' => 6,
                        'game_id' => 2,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => 'ccc',
                        'email' => 'ccc@gmail.com',
                        'waiting_flg' => 1,
                        'delete_flg' => 1
                    ],
                ],
                'companion' => [
                    [
                        'id' => 1,
                        'participant_id' => 6,
                    ],
                    [
                        'id' => 2,
                        'participant_id' => 6,
                    ],
                    [
                        'id' => 3,
                        'participant_id' => 6,
                    ]
                ]
            ]
        );
    }

    // 1人の登録処理
    public function testOneParticipantRegist()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $service = new EventService();

        $participant = new Participant();
        $participant->gameId = 3;
        $participant->name = 'テスト太郎';
        $participant->sex = 1;
        $participant->occupation = 1;
        $participant->email = '1111@gmail.com';

        $companions = [];
        $companion = new Companion();
        $companion->name = '同伴１';
        $companion->sex = 1;
        $companion->occupation = 2;
        $companions[] = $companion;
        $companion = new Companion();
        $companion->name = '同伴２';
        $companion->sex = 1;
        $companion->occupation = 3;
        $companions[] = $companion;

        $service->oneParticipantRegist($participant, $companions);

        $participant = new Participant();
        $participant->gameId = 3;
        $participant->name = 'テスト太郎2';
        $participant->sex = 1;
        $participant->occupation = 1;
        $participant->email = '2222@gmail.com';

        $companions = [];
        $companion = new Companion();
        $companion->name = '同伴3';
        $companion->sex = 1;
        $companion->occupation = 2;
        $companions[] = $companion;
        $companion = new Companion();
        $companion->name = '同伴4';
        $companion->sex = 1;
        $companion->occupation = 3;
        $companions[] = $companion;

        $service->oneParticipantRegist($participant, $companions);

        $detailDao = new DetailDao();
        $companionDao = new CompanionDao();
        $par = $detailDao->getParticipant(7);
        $this->assertSame('100', $par['amount']);
        $this->assertSame('0', $par['waiting_flg']);
        $this->assertSame('1', $par['attendance']);
        $com = $companionDao->selectById(4);
        $this->assertSame('同伴１', $com['name']);
        $this->assertSame('200', $com['amount']);
        $this->assertSame('1', $com['attendance']);
        $com = $companionDao->selectById(5);
        $this->assertSame('同伴２', $com['name']);
        $this->assertSame('300', $com['amount']);
        $this->assertSame('1', $com['attendance']);

        $par = $detailDao->getParticipant(8);
        $this->assertSame('100', $par['amount']);
        $this->assertSame('1', $par['waiting_flg']);
        $this->assertSame('2', $par['attendance']);
        $com = $companionDao->selectById(7);
        $this->assertSame('300', $com['amount']);
        $this->assertSame('2', $com['attendance']);

    }

    // 更新処理
    public function testParticipantUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $service = new EventService();

        $participant = new Participant();
        $participant->id = 1;
        $participant->gameId = 1;
        $participant->name = '更新';
        $participant->sex = 1;
        $participant->occupation = 1;
        $participant->email = 'update@gmail.com';
        $participant->waitingFlg = 0;

        $companions = [];
        $companion = new Companion();
        $companion->name = '同伴１';
        $companion->sex = 1;
        $companion->occupation = 2;
        $companions[] = $companion;
        $companion = new Companion();
        $companion->name = '同伴２';
        $companion->sex = 1;
        $companion->occupation = 3;
        $companions[] = $companion;

        $service->participantUpdate($participant, $companions);

        $detailDao = new DetailDao();
        $companionDao = new CompanionDao();
        $par = $detailDao->getParticipant(1);
        $this->assertSame('更新', $par['name']);
        $this->assertSame('update@gmail.com', $par['email']);
        $this->assertSame('100', $par['amount']);
        $this->assertSame('0', $par['waiting_flg']);
        $this->assertSame('1', $par['attendance']);
        $com = $companionDao->selectById(4);
        $this->assertSame('同伴１', $com['name']);
        $this->assertSame('200', $com['amount']);
        $this->assertSame('1', $com['attendance']);
    }

    // キャンセル処理
    public function testCancelCompete()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $service = new EventService();

        $participant = new Participant();
        $participant->gameId = 1;
        $participant->email = 'bbbb@gmail.com'; // メールアドレス間違い
        $password = 'password';
        $userId = 3;
        $msg = $service->cancelComplete($participant, $password, $userId);
        $this->assertSame('入力されたメールアドレスによる登録がありませんでした。', $msg);

        $participant->email = 'bbb@gmail.com';
        $password = 'password1234'; // パスワード間違い
        $msg = $service->cancelComplete($participant, $password, $userId);
        $this->assertSame('パスワードが異なります。', $msg);

        $password = 'password';
        $msg = $service->cancelComplete($participant, $password, $userId);
        $this->assertEmpty($msg);
        $detailDao = new DetailDao();
        $par = $detailDao->getParticipant(3);
        // キャンセル待ちが解除されていることの確認
        $this->assertSame('0', $par['waiting_flg']);

        $participant->gameId = 2; // 4人
        $msg = $service->cancelComplete($participant, $password, $userId);
        $par = $detailDao->getParticipant(6); // 1 + 同伴3 = 4人
        // 同伴者がいるので解除されない(まだ合計5人なのでキャンセルまちにならない)
        $this->assertSame('1', $par['waiting_flg']);

        $participant->email = 'aaa@gmail.com';
        $msg = $service->cancelComplete($participant, $password, $userId);
        $par = $detailDao->getParticipant(6);
        // 解除される
        $this->assertSame('0', $par['waiting_flg']);

    }

    
}