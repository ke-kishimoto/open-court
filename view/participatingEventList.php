<?php if(!empty($eventList)): ?>
    <p>参加イベント一覧</p>
    <table>
        <tr>
            <th>タイトル</th>
            <th>開催日</th>
            <th>開始</th>
            <th>終了</th>
        </tr>
        <?php foreach($eventList as $event): ?>
            <tr>
                <th><a href="EventDetail.php?id=<?php echo $event['id'] ?>"><?php echo $event['title'] ?></a></th>
                <th><?php echo $event['game_date'] ?></th>
                <th><?php echo $event['start_time'] ?></th>
                <th><?php echo $event['end_time'] ?></th>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p>現在参加登録されたイベントはありません。</p>
<?php endif ?>