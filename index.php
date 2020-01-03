<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>オープンコートイベントカレンダー</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<?php require("calendar.php"); ?>
<?php echo $year; ?>年<?php echo $month; ?>月のカレンダー
<br>
<br>
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
        <a href="detail.php?date=<?php echo $year . sprintf('%02d', $month) . sprintf('%02d', $value['day']); ?>">
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
</html>