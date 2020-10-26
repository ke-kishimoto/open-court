<?php
namespace dao;

use dao\DaoFactory;
use dao\GameInfoDao;

use PDO;
use entity\Participant;

class DetailDao {

    private $pdo;
    public function __construct() {
        $this->pdo = DaoFactory::getConnection();
    }
    public function getPdo() {
        return $this->pdo;
    }
    public function setPdo(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // 参加者登録
    public function insert(Participant $participant) {
        $sql = 'insert into participant 
        (game_id, occupation, sex, name, email, waiting_flg, remark, register_date) 
        values(:game_id, :occupation, :sex, :name, :email, :waiting_flg, :remark, :register_date)';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $participant->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->bindValue(':email', $participant->email, PDO::PARAM_STR);
        $prepare->bindValue(':waiting_flg', $participant->waitingFlg, PDO::PARAM_INT);
        $prepare->bindValue(':remark', $participant->remark, PDO::PARAM_STR);
        $prepare->bindValue(':register_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->execute();
    }

    // 参加者の更新
    public function update(Participant $participant) {
        $sql = 'update participant set
        name = :name
        , occupation = :occupation
        , sex = :sex
        , email = :email
        , remark = :remark
        , update_date = :update_date
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':occupation', $participant->occupation, PDO::PARAM_INT);
        $prepare->bindValue(':sex', $participant->sex, PDO::PARAM_INT);
        $prepare->bindValue(':name', $participant->name, PDO::PARAM_STR);
        $prepare->bindValue(':email', $participant->email, PDO::PARAM_STR);
        $prepare->bindValue(':remark', $participant->remark, PDO::PARAM_STR);
        $prepare->bindValue(':update_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $prepare->bindValue(':id', $participant->id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者の削除
    public function delete(int $id) {
        $sql = 'delete from participant where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者情報取得
    public function getParticipant(int $id) {
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
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetch();
    }

    // 参加者一覧取得
    public function getParticipantList(int $gameId, int $occupation = 0, int $sex = 0, int $waitingFlg = -1) {
        // イベントの日付を取得しておく
        $gameInfoDao = new GameInfoDao();
        $gameInfo = $gameInfoDao->getGameInfo($gameId);
        $andOcc = $occupation > 0 ? ' and occupation = :occupation ' : '';
        $andSex = $sex > 0 ? ' and sex = :sex ' : '';
        $andwaitingFlg = $waitingFlg > -1 ? ' and waiting_flg = :waiting_flg'  : '';
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
            when waiting_flg = 1 then '【キャンセル待ち】' 
            else ''
          end waiting_name
        , email
        , remark
        , chk
        from 
        (
        select id, 1 main ,name, occupation, sex, waiting_flg, remark, email
        , (select distinct '重複あり' from participant where game_id 
                        in (select id from game_info where id <> p.game_id and game_date = :game_date)
                        and (email = p.email or name = p.name)
            ) chk
        from participant p
        where game_id = :game_id " 
        . $andOcc . $andSex . $andwaitingFlg .
        " union all
        select participant_id, 0 ,name, occupation, sex, 0, '', '', ''
        from companion
        where participant_id in (select id from participant where game_id = :game_id" . $andwaitingFlg . ")"
        . $andOcc . $andSex .
        ") p
        order by id, main desc";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        if($occupation > 0) {
            $prepare->bindValue(':occupation', $occupation, PDO::PARAM_INT);
        }
        if($sex > 0) {
            $prepare->bindValue(':sex', $sex, PDO::PARAM_INT);
        }
        if($andwaitingFlg > -1) {
            $prepare->bindValue(':waiting_flg', $waitingFlg, PDO::PARAM_INT);
        }
        $prepare->bindValue(':game_date', $gameInfo['game_date']);
        $prepare->execute();
        return $prepare->fetchAll();
    }

    // 参加者集計情報取得
    public function getDetail(int $gameId) {
        $sql = 'select 
        count(*) cnt
        , sum(
            case 
                when occupation = 1 then 1
                else 0
            end
        ) sya_all  -- 社会人全体
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
                when occupation = 2 then 1
                else 0
            end
        ) dai_all  -- 大学生
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
                when occupation = 3 then 1
                else 0
            end
        ) kou_all  -- 高校生
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
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->bindValue(':waiting_flg', 0, PDO::PARAM_INT); // 参加予定
        $prepare->execute();
        // return $prepare->fetch();
        $info = $prepare->fetch();
        $prepare->bindValue(':waiting_flg', 1, PDO::PARAM_INT);  // キャンセル待ち
        $prepare->execute();
        $waitingInfo = $prepare->fetch();
        $info['waiting_cnt'] = $waitingInfo['cnt'];
        return $info;

    }

    // イベントごと削除する場合の参加者の削除
    public function deleteByGameId(int $gameId) {
        // // 同伴者の削除
        // $sql = "delete from companion where participant_id in (select id from participant where game_id = :game_id)";
        // $prepare = $pdo->prepare($sql);
        // $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        // $prepare->execute();
        // 参加者の削除
        $sql = "delete from participant where game_id = :game_id";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->execute();
    }

    // 参加者の上限チェック
    public function limitCheck(int $gameId, int $participants_number) {
        $sql = "select (max(g.limit_number) - coalesce(count(p.id), 0) - coalesce(sum(cnt), 0)) num
                from game_info g 
                left join (select *
                            , (select count(*) from companion 
                               where participant_id = participant.id) cnt
                            from participant) p
                on g.id = p.game_id 
                and waiting_flg = 0
                where g.id = :game_id ";
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->execute();
        $info = $prepare->fetch();
        // 上限に達していたらtrue
        if ($info['num'] - $participants_number < 0) {
            return true;
        } else {
            return false;
        }
    }

    // 参加者idの取得
    public function getParticipantId(Participant $participant) {
        $sql = 'select max(id) id
                from participant 
                where game_id = :game_id
                and email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $participant->gameId, PDO::PARAM_INT);
        $prepare->bindValue(':email', $participant->email, PDO::PARAM_STR);

        $prepare->execute();
        $info = $prepare->fetch();
        return $info['id'];
    }

    // キャンセル待ちフラグの更新
    public function updateWaitingFlg(int $id) {
        $sql = 'update participant set
        waiting_flg = case when waiting_flg = 0 then 1 else 0 end
        where id = :id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        return $this->getParticipant($id);
    }

    // メールアドレスによる存在チェック
    public function existsCheck(int $gameId, string $email) {
        $participant = new Participant($gameId, 0, 0, '', $email, 0, '');
        $id = $this->getParticipantId($participant);
        if (isset($id)) {
            return true;
        }
        return false;
    }

    // メールアドレスによる削除処理
    public function deleteByMailAddress(int $gameId, string $email) {
        // 存在チェック
        $participant = new Participant($gameId, 0, 0, '', $email, 0, '');
        $id = $this->getParticipantId($participant);
        if ($id == null) {
            return 0;
        }
        // 同伴者削除
        $sql = 'delete from companion where participant_id = :participant_id';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':participant_id', $id, PDO::PARAM_INT);
        $prepare->execute();
        // 参加者削除
        $sql = 'delete from participant where game_id = :game_id and email = :email';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':game_id', $gameId, PDO::PARAM_INT);
        $prepare->bindValue(':email', $email, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->rowCount();
    }

    // 参加済み、参加予定のイベントリスト
    public function getEventListByEmail(string $email, string $gameDate) {
        $sql = 'select g.*
        from participant p
        join game_info g
        on p.game_id = g.id
        where p.email = :email 
        order by g.game_date, g.start_time';
        $prepare = $this->pdo->prepare($sql);
        $prepare->bindValue(':email', $email, PDO::PARAM_STR);
        // $prepare->bindValue(':game_date', $gameDate, PDO::PARAM_STR);
        $prepare->execute();
        return $prepare->fetchAll();
    }
}
