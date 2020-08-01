<?php if (!empty($userList)): ?>
    <table>
        <caption>ユーザー一覧</caption>
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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>
</html>