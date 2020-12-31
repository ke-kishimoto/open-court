<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/DefaultCompanionDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity//BaseEntity.php');
require_once('./model/entity//DefaultCompanion.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\DefaultCompanionDao;
use entity\DefaultCompanion;

class DefaultCompaionDaoTest extends TestCase
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
                        'name' => 'kishimoto',
                    ],
                    
                ],
                'default_companion' => [
                    [
                        'id' => 1,
                        'user_id' => 1,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => '同伴者1'
                    ],
                    [
                        'id' => 2,
                        'user_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => '同伴者2'
                    ],
                ],
            ]
        );
    }

    public function testGetDefaultCompanionList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DefaultCompanionDao();

        $list = $dao->getDefaultCompanionList(1);
        $this->assertSame(2, count($list));

    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DefaultCompanionDao();

        $companion = new DefaultCompanion();
        $companion->userId = 1;
        $companion->occupation = 3;
        $companion->sex = 2;
        $companion->name = '同伴者3';
        $dao->insert($companion);

        $list = $dao->getDefaultCompanionList(1);
        $this->assertSame(3, count($list));

    }

    public function testDeleteByuserId()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new DefaultCompanionDao();

        $dao->deleteByuserId(1);

        $list = $dao->getDefaultCompanionList(1);
        $this->assertSame(0, count($list));
    }
   
}