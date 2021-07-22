<h1>ログイン</h1>
<div class="login">
    <div class="mail-login">
        <p><a href="/user/passwordforget">パスワードを忘れた方はこちら</a></p>
        <form id="signIn_form" action="/user/signincheck" method="post" class="form-group">
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
    <hr>
    <p>LINEでログイン</p>
    <div class="line-login">
        <a href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1656224816&redirect_uri=https%3A%2F%2Fopencourt.eventmanc.com%2Fuser%2Flinelogin&state=12345abcde&bot_prompt=aggressive&scope=profile%20openid">
            <img id="btn-line" src="/resource/images/DeskTop/2x/20dp/btn_login_base.png">
        </a>
    </div>
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
        const linebtn = document.getElementById('btn-line');
        linebtn.addEventListener('mouseover', (e) => {
            e.toElement.src="/resource/images/DeskTop/2x/20dp/btn_login_hover.png"    
        });
        linebtn.addEventListener('mouseout', () => {
            linebtn.src="/resource/images/DeskTop/2x/20dp/btn_login_base.png"    
        });

    })

</script>
</body>
</html>