<table>
    <tr>
        <td colspan= 7>
            <div class="month">
                <a href="./index?year=<?php echo htmlspecialchars($lastYear); ?>&month=<?php echo htmlspecialchars($lastmonth); ?>" class="lastMonthLink"><i class="fas fa-chevron-left"></i></a>
                <a href="./index?year=<?php echo htmlspecialchars($year); ?>&month=<?php echo htmlspecialchars($month); ?>" class="MonthLink"><span id="year"><?php echo htmlspecialchars($year); ?></span>年<span id="this-month"><?php echo htmlspecialchars($month); ?></span>月</a>
                <a href="./index?year=<?php echo htmlspecialchars($nextYear); ?>&month=<?php echo htmlspecialchars($nextmonth); ?>"class="nextMonthLink"><i class="fas fa-chevron-right"></i></a>
            </div>
        </td>
    </tr>
</table>
<?php if (!empty($eventList)): ?>
    <table>
        <tr>
            <th>日付</th>
            <th>イベント名</th>
            <th>参加人数</th>
            <th>売上金額</th>
        </tr>
        <?php foreach($eventList as $event): ?>
            <tr>
                <th><?php echo $event['date'] ?></th>
                <th><a href="./detail?gameid=<?php echo $event['game_id'] ?>"><?php echo $event['title'] ?></a></th>
                <th><?php echo $event['cnt'] ?></th>
                <th><?php echo $event['amount'] ?></th>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p>対象月にイベントはありません。</p>
<?php endif ?>
<p>
    <a href="/admin/admin/index">トップに戻る</a>
</p>