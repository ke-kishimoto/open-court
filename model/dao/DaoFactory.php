<?php
namespace dao;

use dao\HerokuPDO;
use dao\XamppPDO;
use dao\MampPDO;
use dao\TestPDO;

class DaoFactory {

    // 環境の切り替え
    // const ENVIROMENT = 'XAMPP';
    const ENVIROMENT = 'MAMP';
    // const ENVIROMENT = 'TEST';

    // DBの切り替え
    // const DBTYPE = 'PostgreSQL';
    const DBTYPE = 'MySQL';
    
    public static function getConnection() {
        
        if (DaoFactory::ENVIROMENT === 'XAMPP') {
            return new XamppPDO();
        } elseif (DaoFactory::ENVIROMENT === 'MAMP') {
            return new MampPDO();
        } elseif (DaoFactory::ENVIROMENT === 'TEST') {
            return new TestPDO();
        }
    } 

    public static function getGameInfoListSQL() {
        if (DaoFactory::DBTYPE === 'MySQL') {
            return "where year(game_date) = :year and month(game_date) = :month ";
        } elseif (DaoFactory::DBTYPE === 'PostgreSQL') {
            return "where date_part('year', game_date) = :year and date_part('month', game_date) = :month ";
        }
    }

}

?>