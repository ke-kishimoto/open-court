<form action="ConfigComplete.php" method="post" class="form-group">
    <p>システム名設定</p>
    <p>
        システム名<input class="form-control" type="text" name="system_title"  required value="<?php echo $config['system_title'] ?>">
    </p>
    <hr>
    <p>LINE通知設定</p>
    <!-- その内ユーザーIDに修正 -->
    <input type="hidden" name="id" value="1">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
        参加者からの予約があった際にLINEへ通知を送るための設定画面です。<br> 
        1. <a href="https://notify-bot.line.me/ja/" target="_blank">LINE notify</a>へアクセスし、ログインしてください。<br>
        2. ログイン後は「マイページ」⇒「トークンの発行」を選択します。<br>
        3. 通知設定が来るようにしたいグループを選択し、トークン名を発行ボタンを押下します。<br>
        4. 発行されたトークンをコピーし、入力フォームに貼り付けて更新を押下します。
    </p>
    <p>
        LINEトークン<input class="form-control" type="text" name="line_token"  required value="<?php echo $config['line_token'] ?>">
    </p>
    <p>
        <button class="btn btn-primary" type="submit" name="register">登録</button>
    </p>
</form>
</body>
</html>