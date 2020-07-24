<?php
use dao\DetailDao;
$detail = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $detail = $detailDao->getDetail($gameInfo['id']);
}

if(empty($detail)) {
    $detail = array('cnt' => 0
        , 'limit_number' => 0
        , 'sya_women' => 0
        , 'sya_men' => 0
        , 'dai_women' => 0
        , 'dai_men' => 0
        , 'kou_women' => 0
        , 'kou_men' => 0
        , 'waiting_cnt' => 0
    );
}
?>

<div>
    <details>
    <summary>参加者集計情報</summary>
    <br>
    <p>【参加予定  <span id="cnt"><?php echo $detail['cnt'] ?></span>人】【上限  <?php echo $gameInfo['limit_number'] ?>人】</p>
    <p>社会人：
        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=2&waiting_flg=0">女性 <span id="sya_women"><?php echo $detail['sya_women'] ?></span>人</a>、
        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=1&waiting_flg=0">男性 <span id="sya_men"><?php echo $detail['sya_men'] ?></span>人</a>
    <p>大学・専門：
        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=2&waiting_flg=0">女性 <span id="dai_women"><?php echo $detail['dai_women'] ?></span>人</a>、
        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=1&waiting_flg=0">男性 <span id="dai_men"><?php echo $detail['dai_men'] ?></span>人</a>
    </p>
    <p>高校生：
        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=2&waiting_flg=0">女性 <span id="kou_women"><?php echo $detail['kou_women'] ?></span>人</a>、
        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=1&waiting_flg=0">男性 <span id="kou_men"><?php echo $detail['kou_men'] ?></span>人</a>
    </p>
    <p><a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=0&sex=0&waiting_flg=1">キャンセル待ち：<span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人</a></p>
    </details>
</div>