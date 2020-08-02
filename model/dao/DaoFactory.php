<?php

namespace dao;

require_once(dirname(__FILE__).'/connect/HerokuPDO.php');
require_once(dirname(__FILE__).'/connect/XamppPDO.php');
require_once(dirname(__FILE__).'/connect/MampPDO.php');

use dao\HerokuPDO;
use dao\XamppPDO;
use dao\MampPDO;

class DaoFactory {

    // 環境の切り替え
    const ENVIROMENT = 'Heroku';
    // const ENVIROMENT = 'XAMPP';
    // const ENVIROMENT = 'MAMP';

    // DBの切り替え
    const DBTYPE = 'PostgreSQL';
    // const DBTYPE = 'MySQL';
    
    public static function getConnection() {
        
        if (DaoFactory::ENVIROMENT === 'Heroku') {
            return new HerokuPDO();
        } elseif (DaoFactory::ENVIROMENT === 'XAMPP') {
            return new XamppPDO();
        } elseif (DaoFactory::ENVIROMENT === 'MAMP') {
            return new MampPDO();
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