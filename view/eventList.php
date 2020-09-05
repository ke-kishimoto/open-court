<h1>イベント一覧</h1>
<div id="event-list">
    <?php foreach ($gameInfoList as $gameInfo): ?>
        <hr>
        <?php if($adminFlg === '1'): ?>
            <a href="/admin/event/eventInfo?gameid=<?php echo htmlspecialchars($gameInfo['id']); ?>">
        <?php else: ?>
            <a href="/participant/eventInfo?id=<?php echo htmlspecialchars($gameInfo['id']); ?>">
        <?php endif; ?>
                <span class="event-end"><?php echo $gameInfo['game_date'] >= date('Y-m-d') ? '' : '※このイベントは終了しました<br>'  ?></span>
                <?php echo htmlspecialchars($gameInfo['title']); ?>
                <br>
                日時：<?php echo htmlspecialchars(date('n月d日（', strtotime($gameInfo['game_date'])) . $week[date('w', strtotime($gameInfo['game_date']))] . '）'); ?>  
                <?php echo htmlspecialchars($gameInfo['start_time']); ?> ～ <?php echo htmlspecialchars($gameInfo['end_time']); ?><br>
                場所：<?php echo htmlspecialchars($gameInfo['place']); ?><br>
                参加状況：【参加予定：現在<?php echo htmlspecialchars($gameInfo['participants_number']); ?>名】定員：<?php echo htmlspecialchars($gameInfo['limit_number']); ?> 人<br>
                空き状況：<?php echo htmlspecialchars($gameInfo['mark']); ?>
            </a>
    <?php endforeach; ?>
</div>
