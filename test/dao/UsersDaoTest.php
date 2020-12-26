<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/UsersDao.php');
require_once('./model/entity/Users.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use dao\UsersDao;
use entity\Users;

class UsersDaoTest extends TestCase
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
                'users' => [
                    [
                        'id' => 1,
                        'admin_flg' => 1,
                        'name' => 'kishimoto',
                        'email' => 'kishimoto@gmail.com',
                        'password' => '1234',
                        'occupation' => '1',
                        'sex' => '1',
                        'remark' => 'aaaa',
                        'delete_flg' => '1'
                    ],
                    [
                        'id' => 2,
                        'admin_flg' => 0,
                        'name' => 'tester',
                        'email' => 'tester@gmail.com',
                        'password' => '1234',
                        'occupation' => '2',
                        'sex' => '2',
                        'remark' => 'bbbb',
                        'delete_flg' => '1'
                    ],
                ],
            ]
        );
    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $user = new Users();
        $user->name = 'newuser';
        $user->email = 'newuser@gmail.com';
        $user->password = 'password';
        $user->occupation = 3;
        $user->sex = 1;
        $user->remark = 'dddd';
        $dao->insert($user);
        
        $user = $dao->getUserById(3);
        $this->assertSame('newuser', $user['name']);
        $this->assertSame('newuser@gmail.com', $user['email']);
        $this->assertSame('password', $user['password']);
        $this->assertSame('3', $user['occupation']);
        $this->assertSame('1', $user['sex']);
        $this->assertSame('dddd', $user['remark']);
    }

    public function testUpdate()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $user = new Users();
        $user->id = 1;
        $user->name = 'updateuser';
        $user->email = 'updateuser@gmail.com';
        $user->password = 'password';
        $user->occupation = 2;
        $user->sex = 2;
        $user->remark = 'cccc';
        $dao->update($user);
        
        $user = $dao->getUserById(1);
        $this->assertSame('updateuser', $user['name']);
        $this->assertSame('updateuser@gmail.com', $user['email']);
        // $this->assertSame('password', $user['password']);
        $this->assertSame('2', $user['occupation']);
        $this->assertSame('2', $user['sex']);
        $this->assertSame('cccc', $user['remark']);
    }

    public function testUpdatePass()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $dao->updatePass(1, '5678');
        $user = $dao->getUserById(1);

        $this->assertSame('5678', $user['password']);

    }

    public function testDelete()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();
        
        $dao->delete(2);
        $user = $dao->getUserById(2);

        $this->assertSame('9', $user['delete_flg']);

    }

    public function testGetUserId()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $user = new Users();
        $user->email = 'kishimoto@gmail.com';
        $this->assertSame('1', $dao->getUsersId($user));


    }

    public function testGetUserList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $userList = $dao->getUserList();
        $this->assertSame(2, count($userList));

        $this->assertSame('社会人', $userList[0]['occupation_name']);
        $this->assertSame('男性', $userList[0]['sex_name']);
        $this->assertSame('管理者', $userList[0]['authority_name']); 
        
        $this->assertSame('大学・専門学校', $userList[1]['occupation_name']);
        $this->assertSame('女性', $userList[1]['sex_name']);
        $this->assertSame('一般', $userList[1]['authority_name']);

    }

    public function testExistsCheck()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $this->assertSame(true, $dao->existsCheck('kishimoto@gmail.com'));
        $this->assertSame(false, $dao->existsCheck('kishimoro@gmail.com'));
    }

    public function testGetUserByEmail()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();
        $user = $dao->getUserByEmail('kishimoto@gmail.com');

        $this->assertSame('1', $user['id']);
        $this->assertSame('1', $user['admin_flg']);
        $this->assertSame('kishimoto@gmail.com', $user['email']);
        $this->assertSame('kishimoto', $user['name']);
        $this->assertSame('1234', $user['password']);
        $this->assertSame('1', $user['occupation']);
        $this->assertSame('1', $user['sex']);
        $this->assertSame('aaaa', $user['remark']);
    }

    public function testGetUserById()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();
        $user = $dao->getUserById(1);

        $this->assertSame('1', $user['id']);
        $this->assertSame('1', $user['admin_flg']);
        $this->assertSame('kishimoto@gmail.com', $user['email']);
        $this->assertSame('kishimoto', $user['name']);
        $this->assertSame('1234', $user['password']);
        $this->assertSame('1', $user['occupation']);
        $this->assertSame('1', $user['sex']);
        $this->assertSame('aaaa', $user['remark']);
    }

    public function testUpdateAdminFlg()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new UsersDao();

        $dao->updateAdminFlg(1);
        $user = $dao->getUserById(1);
        $this->assertSame('0', $user['admin_flg']);

        $dao->updateAdminFlg(1);
        $user = $dao->getUserById(1);
        $this->assertSame('1', $user['admin_flg']);

    }
    
}