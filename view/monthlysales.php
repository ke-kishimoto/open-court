<table>
    <tr>
        <td colspan="4">
            <div class="sales-head">
                <a href="./index?year=<?php echo htmlspecialchars($lastYear); ?>&month=<?php echo htmlspecialchars($lastmonth); ?>" class="lastMonthLink"><i class="fas fa-chevron-left"></i></a>
                <a href="./index?year=<?php echo htmlspecialchars($year); ?>&month=<?php echo htmlspecialchars($month); ?>" class="MonthLink"><span id="year"><?php echo htmlspecialchars($year); ?></span>年<span id="this-month"><?php echo htmlspecialchars($month); ?></span>月</a>
                <a href="./index?year=<?php echo htmlspecialchars($nextYear); ?>&month=<?php echo htmlspecialchars($nextmonth); ?>" class="nextMonthLink"><i class="fas fa-chevron-right"></i></a>
            </div>
        </td>
        <td>
            <div class="sales-head">
                <a href="./month" class="sales-link">月別</a>
            </div>
        </td>
        <td>
            <div class="sales-head">
                <a href="./year" class="sales-link">年別</a>
            </div>
        </td>
    </tr>
</table>
<?php if (!empty($eventList)) : ?>
    <form action="/sales/updateExpenses" method="post">
        <table>
            <tr>
                <th>日付</th>
                <th>イベント名</th>
                <th>参加人数</th>
                <th>売上金額</th>
                <th>経費</th>
                <th>粗利</th>
            </tr>
            <?php $count = 0; 
                  $total_cnt = 0;
                  $total_amount = 0;
                  $total_expenses = 0; ?>
            <?php foreach ($eventList as $event) : ?>
                <tr>
                    <input type="hidden" name="id-<?php echo $count ?>" value="<?php echo $event['game_id'] ?>">
                    <th><?php echo $event['date'] ?></th>
                    <th><a href="./detail?gameid=<?php echo $event['game_id'] ?>"><?php echo $event['title'] ?></a></th>
                    <th><input type="number" name="cnt-<?php echo $count ?>" value="<?php echo $event['cnt'] ?>" class="form-control"></th>
                    <th><input type="number" name="amount-<?php echo $count ?>" value="<?php echo $event['amount'] ?>" class="form-control"></th>
                    <th><input type="number" name="expenses-<?php echo $count ?>" value="<?php echo $event['expenses'] ?>" class="form-control"></th>
                    <th><?php echo ($event['amount']-$event['expenses']) ?></th>
                </tr>
                <?php $count++; 
                      $total_cnt += (int)$event['cnt'];
                      $total_amount += (int)$event['amount'];
                      $total_expenses += (int)$event['expenses']; ?>
            <?php endforeach ?>
            <tr>
                <th colspan="2">合計</th>
                <th><?php echo $total_cnt ?></th>
                <th><?php echo $total_amount ?></th>
                <th><?php echo $total_expenses ?></th>
                <th><?php echo $total_amount - $total_expenses ?></th>
            </tr>
        </table>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="count" value="<?php echo $count ?>">
        <p>
            <button type="submit" class="btn btn-primary">経費更新</button>
        </p>
    </form>
<?php else : ?>
    <p>対象月にイベントはありません。</p>
<?php endif ?>
    <p>
        <a href="/admin/index">トップに戻る</a>
    </p>
    <?php include('common/footer.php') ?>
    <script src="/resource/js/common_admin.js">
</script>
</body>
</html>