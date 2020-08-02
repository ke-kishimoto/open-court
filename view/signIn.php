<div>
    <form id="signIn_form" action="./SignInController.php" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            メール
            <input class="form-control" type="email" name="email" required maxlength="50">
        </p>
        <p>
            パスワード
            <input class="form-control" type="password" name="password" required maxlength="50">
        </p>
        <input type="checkbox" id="autoLogin" name="autoLogin" value="true">
        <label for="autoLogin">ログインしたままにする</label><br><br>
        <button class="btn btn-primary'" type="submit">ログイン</button>
    </form>
</div>
</body>
</html>