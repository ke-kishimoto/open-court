<?php
use dao\DetailDao;
use dao\GameInfoDao;
$detail = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $gameInfoDao = new GameInfoDao();
    $detail = $detailDao->getDetail($gameInfo['id'], 0);
    $waiting = $detailDao->getDetail($gameInfo['id'], 1);
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
    <h4>参加情報</h4>
    <p>【参加予定  <?php echo $detail['cnt'] ?>人】【上限  <?php echo $gameInfo['limit_number'] ?>人】</p>
    <p>社会人：女性 <?php echo $detail['sya_women'] ?>人、男性 <?php echo $detail['sya_men'] ?>人
    <p>大学・専門：女性 <?php echo $detail['dai_women'] ?>人、男性 <?php echo $detail['dai_men'] ?>人</p>
    <p>高校生：女性 <?php echo $detail['kou_women'] ?>人、男性 <?php echo $detail['kou_men'] ?>人</p>
    <p>キャンセル待ち：<?php echo $waiting['cnt'] ?>人</p>
</div>