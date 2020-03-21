<?php require_once(dirname(__FILE__).'/model/dao/GameInfoDao.php');?>
<?php require("calendar.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>オープンコートイベントカレンダー</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container">
<h2>イベントカレンダー</h2>
<?php echo $year; ?>年<?php echo $month; ?>月
<div  class="month">
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
 
        <td class="days">
        <?php $cnt++; ?>
        <!-- <a class="days" href="detail_date.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
            <?php echo $value['day']; ?>
        </a> -->
        <?php echo $value['day']; ?>
        </td>
 
    <?php if ($cnt == 7): ?>
    </tr>
    <tr>
    <?php $cnt = 0; ?>
    <?php endif; ?>
 
    <?php endforeach; ?>
    </tr>
</table>
<h2>イベント一覧</h2>
<?php
$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoList($year, $month);
?>
<ul id="event-list">
    <?php foreach ($gameInfoList as $gameInfo): ?>
        <hr>
        <li>
            <a href="detail.php?id=<?php echo $gameInfo['id']; ?>">
                <?php echo $gameInfo['title']; ?><br>
                日時：<?php echo $gameInfo['game_date']; ?>  <?php echo $gameInfo['start_time']; ?>～<?php echo $gameInfo['end_time']; ?><br>
                場所：<?php echo $gameInfo['place']; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    'use strict';
    $(function() {
        $('.days').on('click', function(event) {
            // event.preventDefault(),
            $.ajax({
                url:'./controller/EventList.php',
                type:'POST',
                data:{
                    'date':$(this).text()
                }
            })
             // Ajaxリクエストが成功した時発動
            .done( (data) => {
                $('#event-list').html(data);
                console.log(data);
            })
            // Ajaxリクエストが失敗した時発動
            .fail( (data) => {
                $('#event-list').html(data);
                console.log(data);
            })
            // Ajaxリクエストが成功・失敗どちらでも発動
            .always( (data) => {

            })
        }) 
    });
</script>
</body>
</html>