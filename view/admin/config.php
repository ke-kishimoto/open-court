<h1>システム設定</h1>
<hr>
<form action="/admin/config/configComplete" method="post" class="form-group">
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
        LINE notify トークン<input class="form-control" type="text" name="line_token"  required value="<?php echo $config['line_token'] ?>">
    </p>
    <p>
        通知設定
        <select class="form-control" name="line_notify_flg">
            <option value="1" <?php echo $config['line_notify_flg'] == '1' ? 'selected' : '' ?>>通知する</option>
            <option value="0" <?php echo $config['line_notify_flg'] == '0' ? 'selected' : '' ?>>通知しない</option>
        </select>
    </p>
    <hr>
    <p>背景色</p>
    <p>
        <select id="bg_color" name="bg_color" class="custom-select mr-sm-2">
            <option value="white" <?php echo $config['bg_color'] == 'white' ? 'selected' : '' ?> >白</option>
            <option value="orange" <?php echo $config['bg_color'] == 'orange' ? 'selected' : '' ?> >オレンジ</option>
            <option value="pink" <?php echo $config['bg_color'] == 'pink' ? 'selected' : '' ?> >ピンク</option>
        </select>
    </p>
    <hr>
    <p>キャンセル待ちの更新</p>
    <p>
        <select id="waiting_flg_auto_update" name="waiting_flg_auto_update" class="custom-select mr-sm-2">
            <option value="0" <?php echo $config['waiting_flg_auto_update'] == '0' ? 'selected' : '' ?> >手動</option>
            <option value="1" <?php echo $config['waiting_flg_auto_update'] == '1' ? 'selected' : '' ?> >自動</option>
        </select>
    </p>
    <hr>
    <p>
        SendGrid APIキー<input class="form-control"  type="text" name="sendgrid_api_key" value="<?php echo $config['sendgrid_api_key'] ?>">
    </p>
    <hr>
    <p>
        LINE API用
    </p>
    <p>
        クライアントID<input class="form-control"  type="text" name="client_id" value="<?php echo $config['client_id'] ?>">
    </p>
    <p>
        クライアントシークレット<input class="form-control"  type="text" name="client_secret" value="<?php echo $config['client_secret'] ?>">
    </p>
    <p>
        チャネルアクセストークン<input class="form-control"  type="text" name="channel_access_token" value="<?php echo $config['channel_access_token'] ?>">
    </p>
    <p>
        チャネルシークレット<input class="form-control"  type="text" name="channel_secret" value="<?php echo $config['channel_secret'] ?>">
    </p>
    <p>
        <button class="btn btn-primary" type="submit" name="register">登録</button>
    </p>
</form>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js">
</script>
</body>
</html>