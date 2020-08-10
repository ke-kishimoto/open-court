<?php if (!empty($userList)): ?>
    <p>ユーザー一覧</p>
    <table>
        <tr>
            <th>ユーザー名</th><th>職種</th><th>性別</th><th>連絡先</th>
        </tr>
    <?php foreach($userList as $user): ?>
        <tr>
            <th><?php echo $user['name'] ?></th>
            <th><?php echo $user['occupation_name'] ?></th>
            <th><?php echo $user['sex_name'] ?></th>
            <th><?php echo $user['email'] ?></th>
        </tr>
    <?php endforeach ?>
    </table>
<?php else: ?>
    <p>現在登録したユーザーはいません</p>
<?php endif ?>

<a href="./index.php">イベント一覧に戻る</a>
