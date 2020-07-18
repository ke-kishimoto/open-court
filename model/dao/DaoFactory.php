<?php

namespace dao;

require_once(dirname(__FILE__).'/connect/HerokuPDO.php');
require_once(dirname(__FILE__).'/connect/XamppPDO.php');
require_once(dirname(__FILE__).'/connect/MampPDO.php');

use dao\HerokuPDO;
use dao\XamppPDO;
use dao\MampPDO;

class DaoFactory {

    // const ENVIROMENT = 'Heroku';
    // const ENVIROMENT = 'XAMPP';
    const ENVIROMENT = 'MAMP';
    
    public static function getConnection() {
        
        if (DaoFactory::ENVIROMENT === 'Heroku') {
            return new HerokuPDO();
        } elseif (DaoFactory::ENVIROMENT === 'XAMPP') {
            return new XamppPDO();
        } elseif (DaoFactory::ENVIROMENT === 'MAMP') {
            return new MampPDO();
        }
    } 

}

?>