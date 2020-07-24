<?php
use dao\DetailDao;
use dao\GameInfoDao;
$detail = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $gameInfoDao = new GameInfoDao();
    $detail = $detailDao->getDetail($gameInfo['id']);
}

if(empty($detail)) {
    $detail = array('count' => 0
        , 'limit_number' => 0
        , 'sya_women' => 0
        , 'sya_men' => 0
        , 'dai_women' => 0
        , 'dai_men' => 0
        , 'kou_women' => 0
        , 'kou_men' => 0);
}
?>

<div>
    <details>
    <summary>参加情報</summary>
    <p>【参加予定  <span id="cnt"><?php echo $detail['cnt'] ?></span>人】【上限  <?php echo $gameInfo['limit_number'] ?>人】</p>
    <p>社会人：女性 <span id="sya_women"><?php echo $detail['sya_women'] ?></span>人、男性 <span id="sya_men"><?php echo $detail['sya_men'] ?></span>人
    <p>大学・専門：女性 <span id="dai_women"><?php echo $detail['dai_women'] ?></span>人、男性 <span id="dai_men"><?php echo $detail['dai_men'] ?></span>人</p>
    <p>高校生：女性 <span id="kou_women"><?php echo $detail['kou_women'] ?></span>人、男性 <span id="kou_men"><?php echo $detail['kou_men'] ?></span>人</p>
    <p>キャンセル待ち：<span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人</p>
    </details>
</div>