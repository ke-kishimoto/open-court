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

    // 参加者の更新
    public function update(Participant $participant) {
        $pdo = DaoFactory::getConnection();
        $sql = 'update participant set
        name = :name
        , occupation = :occupation
        , sex = :sex
        , email = :email
        , remark = :remark
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->bindValue(':email', $participant->email, PDO::PARAM_STR);
        $prepare->bindValue(':remark', $participant->remark, PDO::PARAM_STR);
        $prepare->bindValue(':id', $participant->id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者の削除
    public function delete(int $id) {
        $pdo = DaoFactory::getConnection();
        $sql = 'delete from participant where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者情報取得
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
        , main
        , name
        , case
            when main = 0 then '同伴'
            else ''
          end companion_name
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
        , waiting_flg
        , case
            when waiting_flg = 1 then 'キャンセル待ち' 
            else ''
          end waiting_name
        , email
        , remark
        from 
        (
        select id, 1 main ,name, occupation, sex, waiting_flg, remark, email
        from participant
        where game_id = :game_id
        union all
        select participant_id, 0 ,name, occupation, sex, 0, '', ''
        from companion
        where participant_id in (select id from participant where game_id = :game_id)
        ) p
        order by id, main desc";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);

        $prepare->execute();
        return $prepare->fetchAll();
    }

    // 参加者集計情報取得
    public function getDetail(int $gameId, int $waitingFlg) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select 
        count(*) cnt
        , sum(
            case 
                when occupation = 1 and sex = 1 then 1
                else 0
            end
        ) sya_men  -- 社会人男
        ,  sum(
            case 
                when occupation = 1 and sex = 2 then 1
                else 0
            end
        ) sya_women  -- 社会人女
        , sum(
            case 
                when occupation = 2 and sex = 1 then 1
                else 0
            end
        ) dai_men  -- 大学生男
        ,  sum(
            case 
                when occupation = 2 and sex = 2 then 1
                else 0
            end
        ) dai_women  -- 大学生女
        , sum(
            case 
                when occupation = 3 and sex = 1 then 1
                else 0
            end
        ) kou_men  -- 高校生男
        ,  sum(
            case 
                when occupation = 3 and sex = 2 then 1
                else 0
            end
        ) kou_women  -- 高校生女
        from 
        (select occupation, sex 
        from participant
        where game_id = :game_id
        and waiting_flg = :waiting_flg
        union all
        select occupation, sex
        from companion
        where participant_id in (select id from participant where game_id = :game_id and waiting_flg = :waiting_flg)
        ) p';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->bindValue(':waiting_flg', $waitingFlg, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    // イベントごと削除する場合の参加者の削除
    public function deleteByGameId(int $gameId) {
        $pdo = DaoFactory::getConnection();
        // // 同伴者の削除
        // $sql = "delete from companion where participant_id in (select id from participant where game_id = :game_id)";
        // $prepare = $pdo->prepare($sql);
        // $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        // $prepare->execute();
        // 参加者の削除
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
        // 上限に達していたらtrue
        if ($info['num'] + $participants_number < 0) {
            return true;
        } else {
            return false;
        }
    }

    // 参加者idの取得
    public function getParticipantId(Participant $participant) {
        $pdo = DaoFactory::getConnection();
        $sql = 'select max(id) id
                from participant 
                where game_id = :game_id
                and occupation = :occupation 
                and sex = :sex
                and name = :name
                and email = :email';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':game_id', $participant->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_INT);
        $prepare->bindValue(':email', $participant->email, PDO::PARAM_INT);

        $prepare->execute();
        $info = $prepare->fetch();
        return $info['id'];
    }

    // キャンセル待ちフラグの更新
    public function updateWaitingFlg(int $id) {
        $pdo = DaoFactory::getConnection();
        $sql = 'update participant set
        waitig_flg = case when waitig_flg = 0 then 1 else 0 end
        where id = :id';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }
}
