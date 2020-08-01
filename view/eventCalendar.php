
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
<p>イベント一覧</p>

<ul id="event-list">
    <?php foreach ($gameInfoList as $gameInfo): ?>
        <hr>
        <li>
            <a href="./EventDetail.php?id=<?php echo htmlspecialchars($gameInfo['id']); ?>">
                <?php echo htmlspecialchars($gameInfo['title']); ?><br>
                日時：<?php echo htmlspecialchars(date('n月d日（', strtotime($gameInfo['game_date'])) . $week[date('w', strtotime($gameInfo['game_date']))] . '）'); ?>  
                <?php echo htmlspecialchars($gameInfo['start_time']); ?> ～ <?php echo htmlspecialchars($gameInfo['end_time']); ?><br>
                場所：<?php echo htmlspecialchars($gameInfo['place']); ?><br>
                参加状況：【参加予定：現在<?php echo htmlspecialchars($gameInfo['participants_number']); ?>名】定員：<?php echo htmlspecialchars($gameInfo['limit_number']); ?> 人<br>
                空き状況：<?php echo htmlspecialchars($gameInfo['mark']); ?>
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
        $('.link').on('click', function(event) {
            event.preventDefault(),
            $.ajax({
                url:'../controller/EventList.php',
                type:'POST',
                data:{
                    'date':$('#year').text() + '/' + ('00' + $('#this-month').text()).slice(-2) + '/' +( '00' + $(this).text().trim()).slice(-2),
                    'type':''
                    // 'date':$(this).attr('href')
                }
            })
             // Ajaxリクエストが成功した時発動
            .done( (data) => {
                $('#event-list').html(data);
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