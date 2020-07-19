<?php
namespace dao;

require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;
use PDO;
use entity\EventTemplate;

class EventTemplateDao {

    public function getEventTemplateList() {
        $pdo = DaoFactory::getConnection();
        $sql = 'select * from event_template order by id';
        $prepare = $pdo->prepare($sql);
        $prepare->execute();

        return $prepare->fetchAll();
    }

    public function getEventTemplate($id) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select * from event_template where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id);
        $prepare->execute();

        return $prepare->fetch();
    }

    public function insert(EventTemplate $eventTemplate) {
        $pdo = DaoFactory::getConnection();
        $sql = 'insert into event_template (template_name, title, short_title, place, limit_number, detail) 
            values(:template_name, :title, :short_title, :place, :limit_number, :detail)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':template_name', $eventTemplate->templateName, PDO::PARAM_STR);
        $prepare->bindValue(':title', $eventTemplate->title, PDO::PARAM_STR);
        $prepare->bindValue(':short_title', $eventTemplate->shortTitle, PDO::PARAM_STR);
        $prepare->bindValue(':place', $eventTemplate->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $eventTemplate->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $eventTemplate->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(EventTemplate $eventTemplate) {
        $pdo = DaoFactory::getConnection();
        $sql = 'update event_template set
        template_name = :template_name 
        , title = :title
        , short_title = :short_title
        , place = :place
        , limit_number = :limit_number
        , detail = :detail
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':template_name', $eventTemplate->templateName, PDO::PARAM_STR);
        $prepare->bindValue(':title', $eventTemplate->title, PDO::PARAM_STR);
        $prepare->bindValue(':short_title', $eventTemplate->shortTitle, PDO::PARAM_STR);
        $prepare->bindValue(':place', $eventTemplate->place, PDO::PARAM_STR);
        $prepare->bindValue(':limit_number', $eventTemplate->limitNumber, PDO::PARAM_INT);
        $prepare->bindValue(':detail', $eventTemplate->detail, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function delete(int $id){
        $pdo = DaoFactory::getConnection();
        $sql = "delete from event_template where id = :id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();

    }
}