<?php
namespace dao;
use PDO;
use dao\BaseDao;

require_once(__DIR__.'/BaseDao.php');

class NoticeDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'notice';
    }

    public function selectById(int $id)
    {
        $prepare = $this->query("
        select * 
        , date_format(register_date, '%Y-%m-%d') date
        from notice
        where id = :id
        and delete_flg = :delete_flg
        order by id desc
        ", ['id' => $id, 'delete_flg' => 1]);
        $noticeList = $prepare->fetch();
        return $noticeList;
    }

    public function selectAll(int $deleteFlg = 1)
    {
        $prepare = $this->query("
        select * 
        , date_format(register_date, '%Y-%m-%d') date
        from notice
        where delete_flg = :delete_flg
        order by id desc
        ", ['delete_flg' => $deleteFlg]);
        $noticeList = $prepare->fetchAll();
        return $noticeList;
    }
    
}
