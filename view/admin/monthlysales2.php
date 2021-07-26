<table>
    <tr>
        <td colspan=2>
            <div class="sales-head">
                <a href="./month?year=<?php echo htmlspecialchars($year - 1); ?>" class="lastMonthLink"><i class="fas fa-chevron-left"></i></a>
                <a href="./month?year=<?php echo htmlspecialchars($year); ?>" class="MonthLink"><span id="year"><?php echo htmlspecialchars($year); ?></span>年</a>
                <a href="./month?year=<?php echo htmlspecialchars($year + 1); ?>" class="nextMonthLink"><i class="fas fa-chevron-right"></i></a>
            </div>
        </td>
        <td>
            <div class="sales-head">
                <a href="./index">イベント別</a>
            </div>
        </td>
        <td>
            <div class="sales-head">
                <a href="./year">年別</a>
            </div>
        </td>
    </tr>
</table>
<?php if (!empty($salesMonthList)) : ?>
    <table>
        <tr>
            <th>月</th>
            <th>参加人数</th>
            <th>売上金額</th>
        </tr>
        <?php foreach ($salesMonthList as $month) : ?>
            <tr>
                <th><?php echo $month['month'] ?></th>
                <th><?php echo $month['cnt'] ?></th>
                <th><?php echo $month['amount'] ?></th>
            </tr>
        <?php endforeach ?>
        <tr>
            <th colspan="1">合計</th>
            <th><?php echo $total_cnt ?></th>
            <th><?php echo $total_amount ?></th>
        </tr>
    </table>
<?php else : ?>
    <p>イベントはありません。</p>
<?php endif ?>
<p>
    <a href="/admin/admin/index">トップに戻る</a>
</p>