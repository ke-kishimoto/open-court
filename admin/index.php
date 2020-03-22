<?php require_once('../model/dao/GameInfoDao.php');?>
<?php require("../calendar.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>オープンコートイベントカレンダー</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="container">
<h2>イベントカレンダー</h2>
<span id="year"><?php echo $year; ?></span>年<span id="this-month"><?php echo $month; ?></span>月
<div class="month">
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
        <a class="days" href="gamemodinfo.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
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
<p>
    <a href="gameinfomod.php" class="btn btn-secondary">新規イベント登録</a>
</p>
<h2>イベント一覧</h2>
<?php
$gameInfoPDO = new GameInfoDao();
$gameInfoList = $gameInfoPDO->getGameInfoList($year, $month);
?>
<ul id="event-list">
    <?php foreach ($gameInfoList as $gameInfo): ?>
        <hr>
        <li>
            <a href="gameinfomod.php?id=<?php echo $gameInfo['id']; ?>">
                <?php echo htmlspecialchars($gameInfo['title']); ?><br>
                日時：<?php echo htmlspecialchars($gameInfo['game_date']); ?>  <?php echo htmlspecialchars($gameInfo['start_time']); ?>～<?php echo htmlspecialchars($gameInfo['end_time']); ?><br>
                場所：<?php echo htmlspecialchars($gameInfo['place']); ?>
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
            event.preventDefault(),
            $.ajax({
                url:'../controller/EventList.php',
                type:'POST',
                data:{
                    'date':$('#year').text() + '/' + ('00' + $('#this-month').text()).slice(-2) + '/' +( '00' + $(this).text().trim()).slice(-2),
                    'type':'admin'
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
            })
            // Ajaxリクエストが成功・失敗どちらでも発動
            .always( (data) => {

            })
        }) 
    });
</script>
</body>
</html>