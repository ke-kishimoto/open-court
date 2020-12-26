<?php
require_once "vendor/autoload.php";
require_once(__DIR__.'/MyApp_DbUnit_ArrayDataSet.php');
require_once('./model/dao/CompanionDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/entity//Companion.php');

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use dao\CompanionDao;
use entity\Companion;

class CompaionDaoTest extends TestCase
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
                'participant' => [
                    [
                        'id' => 1,
                        'name' => 'kishimoto',
                    ],
                    
                ],
                'companion' => [
                    [
                        'id' => 1,
                        'participant_id' => 1,
                        'occupation' => 1,
                        'sex' => 1,
                        'name' => '同伴者1'
                    ],
                    [
                        'id' => 2,
                        'participant_id' => 1,
                        'occupation' => 2,
                        'sex' => 2,
                        'name' => '同伴者2'
                    ],
                ],
            ]
        );
    }

    public function testGetCompanionList()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new CompanionDao();

        $list = $dao->getCompanionList(1);
        $this->assertSame(2, count($list));

    }

    public function testInsert()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new CompanionDao();

        $companion = new Companion();
        $companion->participantId = 1;
        $companion->occupation = 3;
        $companion->sex = 2;
        $companion->name = '同伴者3';
        $dao->insert($companion);

        $list = $dao->getCompanionList(1);
        $this->assertSame(3, count($list));

    }

    public function testDeleteByparticipantId()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $dao = new CompanionDao();

        $dao->deleteByparticipantId(1);

        $list = $dao->getCompanionList(1);
        $this->assertSame(0, count($list));
    }
   
}