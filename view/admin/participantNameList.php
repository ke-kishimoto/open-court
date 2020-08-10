<?php if (!empty($participantList)): ?>
    <p>参加者リスト</p>
    <table>
        <tr>
            <th>名前</th><th>職種</th><th>性別</th>
        </tr>
        <?php foreach($participantList as $participant): ?>
            <tr>
                <th><?php echo $participant['name'] ?></th>
                <th><?php echo $participant['occupation_name'] ?></th>
                <th><?php echo $participant['sex_name'] ?></th>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p>参加者はいません</p>
<?php endif ?>
<a href="./EventInfo.php?id=<?php echo $gameId ?>">イベント詳細へ戻る</a>
<br>
<a href="./index.php">トップへ戻る</a>