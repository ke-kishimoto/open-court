
<p>
    <a href="EventInfo.php" class="btn btn-info">新規イベント登録</a>
</p>
<p>
    <a href="EventTemplate.php" class="btn btn-info">テンプレート設定</a>
</p>
<p>
    <a href="Config.php" class="btn btn-info">システム設定</a>
</p>
<p>
    <a href="UserList.php" class="btn btn-info">ユーザーリスト</a>
</p>
<p>イベント一覧</p>

<ul id="event-list">
    <?php foreach ($gameInfoList as $gameInfo): ?>
        <hr>
        <li>
            <a href="EventInfo.php?id=<?php echo $gameInfo['id']; ?>">
                <span class="event-end"><?php echo $gameInfo['game_date'] > date('Y-m-d') ? '' : '※このイベントは終了しました<br>'  ?></span>
                <?php echo htmlspecialchars($gameInfo['title']); ?><br>
                日時：<?php echo htmlspecialchars(date('n月d日（', strtotime($gameInfo['game_date'])) . $week[date('w', strtotime($gameInfo['game_date']))] . '）'); ?>  
                <?php echo htmlspecialchars($gameInfo['start_time']); ?>～<?php echo htmlspecialchars($gameInfo['end_time']); ?><br>
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
        // 日付をクリックした場合
        $('.link').on('click', function(event) {
            event.preventDefault(),
            $.ajax({
                url:'../../controller/api/EventList.php',
                type:'POST',
                data:{
                    'date':$('#year').text() + '/' + ('00' + $('#this-month').text()).slice(-2) + '/' +( '00' + $(this).text().trim()).slice(-2),
                    'type':'admin'
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
        });
    });
</script>
</body>
</html>