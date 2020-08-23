<h1>ログイン</h1>
<div>
    <form id="signIn_form" action="./SignInController.php" method="post" class="form-group">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            メール
            <input id="email" class="form-control" type="email" name="email" required maxlength="50">
        </p>
        <p>
            パスワード
            <input id="password" class="form-control" type="password" name="password" required maxlength="50">
        </p>
        <input type="checkbox" id="autoLogin" name="autoLogin" checked>
        <label for="autoLogin">ログインしたままにする</label><br><br>
        <button id="btn-login" class="btn btn-primary" type="submit">ログイン</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(function() {
        $('#btn-login').on('click', function() {
            var userMail = $('#email').val();
            var password = $('#password').val();
            if(userMail === '' || password === '') {
                return true;
            }
            // if (typeof(Strage) === "undefined") {
            //     console.log('サポートされていません。')
            // }
            if($('#autoLogin').val() === 'on') {
                var strage = window.localStorage;
                var user = {
                    email: userMail,
                    pass: password,
                };
                strage.removeItem('eventScheduleUser');
                // ローカルストレージに保存
                strage.setItem('eventScheduleUser', JSON.stringify(user));
                // console.log(JSON.parse(strage.getItem('eventScheduleUser')));
            }
        });
        let strage = window.localStorage;
        let user = JSON.parse(strage.getItem('eventScheduleUser'));
        if(user !== null) {
            $('#email').val(user.email);
            $('#password').val(user.pass);
        }
    })

</script>
</body>
</html>