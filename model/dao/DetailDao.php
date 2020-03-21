<?php

require_once(dirname(__FILE__).'/OpenCourtPDO.php');

class DetailDao {

    // 参加者登録
    public function insert(Participant $participant) {
        $pdo = new OpenCourtPDO();
        $sql = 'insert into participant (game_id, occupation, sex, name, remark) values(:gameId, :occupation, :sex, :name, :remark)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameId', $participant->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->bindValue(':remark', $participant->remark, PDO::PARAM_STR);
        $prepare->execute();
    }

    // 参加者一覧取得
    public function getParticipantList(int $gameId) {
        $pdo = new OpenCourtPDO();
        $sql = "select 
        name
        , case 
            when occupation =  1 then '社会人'
            when occupation =  2 then '大学・専門学校'
            when occupation =  3 then '高校'
            else 'その他' 
         end occupation
        , case
            when sex = 1 then '男性'
            when sex = 2 then '女性'
          end sex
        , remark
        from participant 
        where game_id = :gameId 
        order by occupation, sex, id";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameId', $gameId, PDO::PARAM_INT);

        $prepare->execute();
        return $prepare->fetchAll();
    }

    // 参加者集計情報取得
    public function getDetail(int $gameId) {
        $pdo = new OpenCourtPDO();
        $sql = 'select * from v_participant where game_id = :gameId';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameId', $gameId, PDO::PARAM_INT);

        $prepare->execute();
        return $prepare->fetch();
    }

    // 参加者の削除
    public function deleteByGameId(int $gameId) {
        $pdo = new OpenCourtPDO();
        $sql = "delete from participant where game_id = :gameId";
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameId', $gameId, PDO::PARAM_INT);
        $prepare->execute();
    }
}
