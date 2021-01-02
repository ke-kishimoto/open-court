<h2></h2>
<?php if (!empty($participantList)): ?>
    <table>
        <tr>
            <th>名前</th>
            <th>出欠</th>
            <th>回収金額</th>
        </tr>
        <?php foreach($participantList as $participant): ?>
            <tr>
                <th><?php echo $participant['name'] ?></th>
                <th><button type="button" class="btn btn-primary"><?php echo $participant['attendance_name'] ?></button></th>
                <th><input type="number" name="amount" value="<?php echo $participant['amount'] ?>"></th>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p>参加者はいません。</p>
<?php endif ?>
<p>
    <a href="/admin/admin/index">トップに戻る</a>
</p>