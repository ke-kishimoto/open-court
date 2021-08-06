<div>
    <form id="signUp_form" action="/user/passwordchangecomplete" method="post" class="form-group">
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
        <button class="btn btn-primary" type="submit">登録</button>
    </form>
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
</body>
</html>