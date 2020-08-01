<div>
    <form id="signUp_form" action="./PasswordChangeComplete.php" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <div id="password-area">
            <p>
                パスワード
                <input class="form-control" type="password" name="password" required maxlength="50">
            </p>
            <p>
                パスワード(再入力)
                <input class="form-control" type="password" name="rePassword" required maxlength="50">
            </p>
        </div>
        <button class="<?php echo htmlspecialchars($btnClass) ?>" type="submit"><?php echo htmlspecialchars($btnLiteral) ?></button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    
</script>
</body>
</html>