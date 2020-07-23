<?php

namespace dao;

require_once(dirname(__FILE__).'/DaoFactory.php');

use dao\DaoFactory;

use PDO;
use entity\Participant;

class DetailDao {

    // 参加者登録
    public function insert(Participant $participant) {
        $pdo = DaoFactory::getConnection();
        $sql = 'insert into participant 
        (game_id, occupation, sex, name, email, waiting_flg, remark) 
        values(:game_id, :occupation, :sex, :name, :email, :waiting_flg, :remark)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $participant->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->bindValue(':email', $participant->email, PDO::PARAM_STR);
        $prepare->bindValue(':waiting_flg', $participant->waitingFlg, PDO::PARAM_INT);
        $prepare->bindValue(':remark', $participant->remark, PDO::PARAM_STR);
        $prepare->execute();
    }

    public function update(Participant $participant) {
        $pdo = DaoFactory::getConnection();
        $sql = 'update participant set
        name = :name
        , occupation = :occupation
        , sex = :sex
        , companion = :companion
        , remark = :remark
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->bindValue(':companion', $participant->companion, PDO::PARAM_INT);
        $prepare->bindValue(':remark', $participant->remark, PDO::PARAM_STR);
        $prepare->bindValue(':id', $participant->id, PDO::PARAM_INT);
        $prepare->execute();
    }

    public function delete(int $id) {
        $pdo = DaoFactory::getConnection();
        $sql = 'delete from participant where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者登録
    public function getParticipant(int $id) {
        $pdo = DaoFactory::getConnection();
        $sql = "select * 
        , case 
            when occupation =  1 then '社会人'
            when occupation =  2 then '大学・専門学校'
            when occupation =  3 then '高校'
            else 'その他' 
         end occupation_name
        , case
            when sex = 1 then '男性'
            when sex = 2 then '女性'
          end sex_name
        from participant where id = :id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    // 参加者一覧取得
    public function getParticipantList(int $gameId) {
        $pdo = DaoFactory::getConnection();
        $sql = "select
        id 
        , name
        , occupation
        , case 
            when occupation =  1 then '社会人'
            when occupation =  2 then '大学・専門学校'
            when occupation =  3 then '高校'
            else 'その他' 
         end occupation_name
        , sex
        , case
            when sex = 1 then '男性'
            when sex = 2 then '女性'
          end sex_name
        , waiting_flg
        , companion
        , remark
        from participant 
        where game_id = :game_id 
        order by occupation, sex, id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);

        $prepare->execute();
        return $prepare->fetchAll();
    }

    // 参加者集計情報取得
    public function getDetail(int $gameId) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select * from v_participant where game_id = :game_id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);

        $prepare->execute();
        return $prepare->fetch();
    }

    // 参加者の削除
    public function deleteByGameId(int $gameId) {
        $pdo = DaoFactory::getConnection();
        $sql = "delete from participant where game_id = :game_id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者の上限チェック
    public function limitCheck(int $gameId, int $participants_number) {
        $pdo = DaoFactory::getConnection();
        $sql = "select (max(g.limit_number) - count(p.id) - coalesce(sum(cnt))) num
                from game_info g 
                left join (select *
                            , (select count(*) from companion where participant_id = participant.id) cnt
                            from participant) p
                on g.id = p.game_id 
                where game_id = :game_id ";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->execute();
        $info = $prepare->fetch();
        if ($info['num'] + $participants_number >= 0) {
            return true;
        } else {
            return false;
        }

    }
}
