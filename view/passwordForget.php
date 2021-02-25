<h1>パスワード再発行</h1>
<div>
    <form id="signIn_form" action="/user/passreset" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            登録時に使用したメールアドレスを入力してください。
            <input id="email" class="form-control" type="email" name="email" required maxlength="50">
        </p>
        <button id="btn-login" class="btn btn-primary" type="submit">送信</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(function() {
        
    })

</script>
</body>
</html>