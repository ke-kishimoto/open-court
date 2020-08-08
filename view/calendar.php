<p>イベントカレンダー【<span id="year"><?php echo htmlspecialchars($year); ?></span>年<span id="this-month"><?php echo htmlspecialchars($month); ?></span>月】</p>
<div  class="month">
<a href=".?year=<?php echo htmlspecialchars($pre_year); ?>&month=<?php echo htmlspecialchars($lastmonth); ?>"><?php echo htmlspecialchars($lastmonth); ?>月</a>
<a href=".?year=<?php echo htmlspecialchars($year); ?>&month=<?php echo htmlspecialchars($month); ?>"><?php echo htmlspecialchars($month); ?>月</a>
<a href=".?year=<?php echo htmlspecialchars($next_year); ?>&month=<?php echo htmlspecialchars($nextmonth); ?>"><?php echo htmlspecialchars($nextmonth); ?>月</a>
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

        <?php $cnt++; ?>
        <?php if($value['link']): ?>
            <td class="link">
                <a class="days__link" href="detail_date.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
                    <?php echo htmlspecialchars($value['day']); ?>
                </a>
            </td>
        <?php else: ?>
            <td>
            <?php echo htmlspecialchars($value['day']); ?>
            </td>
        <?php endif ?>
 
    <?php if ($cnt == 7): ?>
    </tr>
    <tr>
    <?php $cnt = 0; ?>
    <?php endif; ?>
 
    <?php endforeach; ?>
    </tr>
</table>