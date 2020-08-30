<?php if(!empty($eventList)): ?>
    <h1>参加イベント一覧</h1>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#after" class="nav-link active" data-toggle="tab">予定</a>
        </li>
        <li class="nav-item">
            <a href="#before" class="nav-link" data-toggle="tab">過去</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="after" class="tab-pane active">
            <br>
            <?php foreach($eventList as $event): ?>
                <?php if($event['game_date'] >= date('Y-m-d')): ?>
                    <a href="/participant/eventInfo?id=<?php echo $event['id'] ?>"><?php echo $event['title'] ?></a><br>
                        開催日：<?php echo $event['game_date'] ?><br>
                        開始：<?php echo $event['start_time'] ?><br>
                        終了：<?php echo $event['end_time'] ?>
                    <hr>
                <?php endif; ?>
            <?php endforeach ?>
        </div>
        <div id="before" class="tab-pane">
        <br>
            <?php foreach($eventList as $event): ?>
                <?php if($event['game_date'] < date('Y-m-d')): ?>
                    <a href="/participant/eventInfo?id=<?php echo $event['id'] ?>"><?php echo $event['title'] ?></a><br>
                        開催日：<?php echo $event['game_date'] ?><br>
                        開始：<?php echo $event['start_time'] ?><br>
                        終了：<?php echo $event['end_time'] ?>
                    <hr>
                <?php endif; ?>
            <?php endforeach ?>
        </div>
    </div>

<?php else: ?>
    <p>現在参加登録されたイベントはありません。</p>
<?php endif ?>
<a href="./index.php">イベント一覧に戻る</a>