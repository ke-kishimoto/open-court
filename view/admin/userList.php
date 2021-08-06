<?php if (!empty($userList)): ?>
    <h1>ユーザー一覧</h1>
        <?php foreach($userList as $user): ?>
            <?php if($user['id'] <> $_SESSION['user']['id']): ?>
                        ユーザー名：<?php echo $user['name'] ?><br>
                        職種：<?php echo $user['occupation_name'] ?><br>
                        性別：<?php echo $user['sex_name'] ?><br>
                        連絡先：<a href="mailto:<?php echo $user['email'] ?>"><?php echo $user['email'] ?></a><br>
                        権限：<span id="authority-name-<?php echo $user['id'] ?>"><?php echo $user['authority_name'] ?></span><br>
                        <p>状態：<span id="statud-<?php echo $user['id'] ?>"><?php echo $user['status'] ?></span><p>
                        <p><button class="change-authority btn btn-info" type="button" value="<?php echo $user['id'] ?>">権限の変更</button></p>
                    <hr>
            <?php endif; ?>
        <?php endforeach ?>

<?php else: ?>
    <p>現在登録したユーザーはいません</p>
<?php endif ?>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js">
</script>
</body>
</html>