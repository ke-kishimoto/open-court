<h2></h2>
<?php if (!empty($participantList)): ?>
    <form action="/admin/sales/update" method="post">
        <table>
            <tr>
                <th>名前</th>
                <th>出欠</th>
                <th>回収金額</th>
                <th>備考</th>
            </tr>
            <?php $count = 0; ?>
            <?php foreach($participantList as $participant): ?>
                <tr>
                    <input type="hidden" name="id-<?php echo $count ?>" value="<?php echo $participant['id'] ?>">
                    <th><?php echo $participant['name'] ?></th>
                    <th>
                        <select name="attendance-<?php echo $count ?>">
                            <option value="1" <?php echo $participant['attendance'] == '1' ? 'selected' : '' ?>>出席</option>
                            <option value="2" <?php echo $participant['attendance'] == '2' ? 'selected' : '' ?>>欠席</option>
                        </select>
                    </th>
                    
                    <th><input type="number" name="amount-<?php echo $count ?>" value="<?php echo $participant['amount'] ?>"></th>
                    <th><input type="text" name="amount_remark-<?php echo $count ?>" value="<?php echo $participant['amount_remark'] ?>"></th>
                </tr>
                <?php $count++; ?>
            <?php endforeach ?>
        </table>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <input type="hidden" name="count" value="<?php echo $count ?>">
        <p>
            <button type="submit" class="btn btn-primary">更新</button>
        </p>
    </form>
<?php else: ?>
    <p>参加者はいません。</p>
<?php endif ?>
<p>
    <a href="/admin/admin/index">トップに戻る</a>
</p>