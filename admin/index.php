<?php require_once('../model/dao/GameInfoDao.php');?>
<?php require("../calendar.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>オープンコートイベントカレンダー</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h2>イベントカレンダー</h2>
<?php echo $year; ?>年<?php echo $month; ?>月
<div>
<a href=".?year=<?php echo $pre_year; ?>&month=<?php echo $lastmonth; ?>"><?php echo $lastmonth; ?>月</a>
<a href=".?year=<?php echo $next_year; ?>&month=<?php echo $nextmonth; ?>"><?php echo $nextmonth; ?>月</a>
</div>
<table>
    <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
    </tr>
 
    <tr>
    <?php $cnt = 0; ?>
    <?php foreach ($calendar as $key => $value): ?>
 
        <td>
        <?php $cnt++; ?>
        <a href="gamemodinfo.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
            <?php echo $value['day']; ?>
        </a>
        </td>
 
    <?php if ($cnt == 7): ?>
    </tr>
    <tr>
    <?php $cnt = 0; ?>
    <?php endif; ?>
 
    <?php endforeach; ?>
    </tr>
</table>
<a href="gameinfomod.php">新規イベント登録</a>
<h2>今月のイベント一覧</h2>
<?php
$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoList($year, $month);
?>
<ul>
<?php foreach ($gameInfoList as $gameInfo): ?>
    <hr>
<li>
    <a href="gameinfomod.php?id=<?php echo $gameInfo['id']; ?>">
<?php echo $gameInfo['title']; ?><br>
日時：<?php echo $gameInfo['game_date']; ?>  <?php echo $gameInfo['start_time']; ?>～<?php echo $gameInfo['end_time']; ?><br>
場所：<?php echo $gameInfo['place']; ?>
</a>
</li>
<?php endforeach; ?>
</ul>
</body>
</html>