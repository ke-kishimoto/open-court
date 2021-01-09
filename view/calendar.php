<table>
    <tr>
        <td colspan= 7>
            <div class="month">
                <a href="./index?year=<?php echo htmlspecialchars($eventCalendar->lastYear); ?>&month=<?php echo htmlspecialchars($eventCalendar->lastmonth); ?>" class="lastMonthLink"><i class="fas fa-chevron-left"></i></a>
                <a href="./index?year=<?php echo htmlspecialchars($eventCalendar->year); ?>&month=<?php echo htmlspecialchars($eventCalendar->month); ?>" class="MonthLink"><span id="year"><?php echo htmlspecialchars($eventCalendar->year); ?></span>年<span id="this-month"><?php echo htmlspecialchars($eventCalendar->month); ?></span>月</a>
                <a href="./index?year=<?php echo htmlspecialchars($eventCalendar->nextYear); ?>&month=<?php echo htmlspecialchars($eventCalendar->nextmonth); ?>"class="nextMonthLink"><i class="fas fa-chevron-right"></i></a>
            </div>
        </td>
    </tr>
    <tr class="weekTit">
        <th class="sunday">日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th class="saturday">土</th>
    </tr>
    <tr>
    <?php foreach ($eventCalendar->calendar as $key => $value): ?>
        <td class="<?php echo $value['weekName']; ?>">
            <div class="day">
                <?php if($value['link']): ?>    
                    <div class="day-header">   
                        <a class="link <?php echo $value['today'] ?>" href="detail_date.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
                            <?php echo htmlspecialchars($value['day']); ?>
                        </a>
                        <?php if($adminFlg === '1' && $value['day'] !== ''): ?>
                            <a class="link-add" href="/admin/event/eventInfo?date=<?php echo $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $value['day'])?>">
                                ＋
                            </a>
                        <?php endif; ?>
                    </div>
                        <?php foreach($value['info'] as $info): ?>
                            <?php
                                if($info['mark'] === '○') {
                                    $availabilityClass = 'availability-OK';
                                } elseif($info['mark'] === '△') {
                                    $availabilityClass = 'availability-COUTION';
                                } elseif($info['mark'] === '✖️') {
                                    $availabilityClass = 'availability-NG';
                                }
                            ?>
                            <?php if($adminFlg === '1'): ?>
                                <a class="event <?php echo $availabilityClass; ?>" href="/admin/event/eventInfo?gameid=<?php echo htmlspecialchars($info['id']); ?>"><?php echo $info['short_title'] ?></a>
                            <?php else: ?>
                                <a class="event <?php echo $availabilityClass; ?>" href="/participant/eventInfo?id=<?php echo htmlspecialchars($info['id']); ?>"><?php echo $info['short_title'] ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </span>
                <?php else: ?>
                    <div class="day-header">
                        <span class="nolink <?php echo $value['today']?>">
                            <?php echo htmlspecialchars($value['day']); ?>
                        </span>
                        <?php if($adminFlg === '1' && $value['day'] !== ''): ?>
                            <a class="link-add" href="/admin/event/eventInfo?date=<?php echo $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $value['day'])?>">
                                ＋
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
            </div>
        </td>
 
    <?php if ($value['weekName'] === 'saturday'): ?>
        </tr>
    <?php endif; ?>
 
    <?php endforeach; ?>
</table>    
空き状況
<span class="guide availability-OK">空きあり</span>
<span class="guide availability-COUTION">残り僅か</span>
<span class="guide availability-NG">キャンセル待ち</span>