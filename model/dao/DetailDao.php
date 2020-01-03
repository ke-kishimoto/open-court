<?php

require_once(dirname(__FILE__).'/OpenCourtPDO.php');

class DetailDao {

    // 参加者登録
    public function insert(Participant $participant) {
        $pdo = new OpenCourtPDO();
        $sql = 'insert into participant (game_id, occupation, sex, name) values(:gameId, :occupation, :sex, :name)';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameId', $participant->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->execute();
    }

    // 参加者情報取得
    public function getDetail(int $gameId) {
        $pdo = new OpenCourtPDO();
        $sql = 'select * from v_participant where game_id = :gameId';
        $prepare = $pdo->prepare($sql);
        $prepare->bindValue(':gameId', $gameId, PDO::PARAM_INT);

        $prepare->execute();
        return $prepare->fetch();

    }
}
