<?php
require_once('./model/dao/UsersDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/MampPDO.php');
require_once('./model/entity/Users.php');

use PHPUnit\Framework\TestCase;
use dao\UsersDao;
use entity\Users;

class UsersDaoTest extends TestCase
{
    // プログラム開始時に1度だけ実行
    public static function setUpBeforeClass(): void
    {
    
    }
    
    // テストメソッド実行前に実行
    public function setUp(): void
    {
        
    }
    
    // テストメソッド
    public function testMethod()
    {
        $result = 1 + 2;
        $this->assertSame(3, $result);
    }
    
    // テストメソッド実行後に実行
    public function tearDown(): void
    {
        
    }
    
    // 最後に1度だけ実行
    public static function tearDownAfterClass(): void
    {
    
    }
    
    
}