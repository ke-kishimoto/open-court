<p>イベントカレンダー</p>
<div  class="month">
<a href=".?year=<?php echo htmlspecialchars($pre_year); ?>&month=<?php echo htmlspecialchars($lastmonth); ?>">＜</a>
<a href=".?year=<?php echo htmlspecialchars($next_year); ?>&month=<?php echo htmlspecialchars($nextmonth); ?>">＞</a>
<a href=".?year=<?php echo htmlspecialchars($year); ?>&month=<?php echo htmlspecialchars($month); ?>">【<span id="year"><?php echo htmlspecialchars($year); ?></span>年<span id="this-month"><?php echo htmlspecialchars($month); ?></span>月】</a>
</div>
<table>
    <tr>
        <th class="sunday">日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th class="saturday">土</th>
    </tr>
 
    <tr>
    <?php $cnt = 0; ?>
    <?php foreach ($calendar as $key => $value): ?>
        <?php 
            if($cnt % 7 === 0 && $value['day'] !== '') {
                $week = 'sunday';
            }elseif($cnt % 7 === 6 && $value['day'] !== '') {
                $week = 'saturday';
            }elseif ($value['day'] === '') {
                $week = 'blank';
            } else {  
                $week = '';
            }
        ?>
        <?php $cnt++; ?>
        <td class="<?php echo $week; ?>">
            <div class="day">
                <?php if($value['link']): ?>    
                    <div class="day-header">   
                        <a class="link <?php echo $value['today'] ?>" href="detail_date.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
                            <?php echo htmlspecialchars($value['day']); ?>
                        </a>
                        <?php if($adminFlg === '1' && $value['day'] !== ''): ?>
                            <a class="link-add" href="./EventInfo.php?date=<?php echo $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $value['day'])?>">
                                追加
                            </a>
                        <?php endif; ?>
                    </div>
                        <?php foreach($value['info'] as $info): ?>
                            <a class="event" href="./EventInfo.php?id=<?php echo htmlspecialchars($info['id']); ?>"><?php echo $info['short_title'] ?></a>
                        <?php endforeach; ?>
                        </span>
                <?php else: ?>
                    <div class="day-header">
                        <span class="nolink <?php echo $value['today']?>">
                            <?php echo htmlspecialchars($value['day']); ?>
                        </span>
                        <?php if($adminFlg === '1' && $value['day'] !== ''): ?>
                            <a class="link-add" href="./EventInfo.php?date=<?php echo $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $value['day'])?>">
                                追加
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
            </div>
        </td>
 
    <?php if ($cnt == 7): ?>
    </tr>
    <tr>
    <?php $cnt = 0; ?>
    <?php endif; ?>
 
    <?php endforeach; ?>
    </tr>
</table>