<?php if (!empty($participantList)): ?>
    <h1>参加者リスト</h1>
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
<p>
    <a href="./EventInfo.php?id=<?php echo $gameId ?>">イベント詳細へ戻る</a>
</p>