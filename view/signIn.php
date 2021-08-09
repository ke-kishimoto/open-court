<div id="app">
    <vue-header></vue-header>

    <p style="color:red">{{ msg }}</p>

    <h1>ログイン</h1>
    <div class="login">
        <div class="mail-login">
            <p><a href="/user/passwordforget">パスワードを忘れた方はこちら</a></p>
            <!-- <form id="signIn_form" action="/user/signincheck" method="post" class="form-group"> -->
                <p>
                    メール
                    <input id="email" class="form-control" type="email" name="email" required maxlength="50" v-model="email">
                </p>
                <p>
                    パスワード
                    <input id="password" class="form-control" type="password" name="password" required maxlength="50" v-model="password">
                </p>
                <input type="checkbox" id="autoLogin" name="autoLogin" checked>
                <label for="autoLogin">ログインしたままにする</label><br><br>
                <button id="btn-login" class="btn btn-primary" type="button" @click="loginCheck">ログイン</button>
            <!-- </form> -->
        </div>
        <hr>
        <p>LINEでログイン</p>
        <div class="line-login">
            <a v-bind:href="'https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=' + clientId + '&redirect_uri=https%3A%2F%2Fopencourt.eventmanc.com%2Fuser%2Flinelogin&state=' + state + '&bot_prompt=aggressive&scope=profile%20openid'">
                <img id="btn-line" src="/resource/images/DeskTop/2x/20dp/btn_login_base.png">
            </a>
        </div>
    </div>

    <vue-footer></vue-footer>

</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",
        data: {
            msg: '',
            email: '',
            password: '',
            state: '',
            clientId: '',
        }, methods: {
            getLineParam() {
                fetch('/api/user/getLineParam')
                .then(res => res.json()
                    .then(data => {
                        this.clientId = data.clientId;
                        this.state = data.state
                    })
                )
                .catch(errors => console.log(errors))
            },
            loginCheck() {
                if(this.email === '') {
                    this.msg = 'メールアドレスを入力してください。'
                    return
                }
                if(this.password === '') {
                    this.msg = 'パスワードを入力してください。'
                    return
                }
                let params = new URLSearchParams()
                params.append('email', this.email)
                params.append('password', this.password)
                fetch('/api/user/signInCheck', {
                    method: 'post',
                    body: params,
                }).then(res => res.json().then(result => {
                    if(result.errMsg === '') {
                        location.href = "/"
                    } else {
                        this.msg = result.errMsg
                    }
                }))
            }
        }, created: function() {
            this.getLineParam()
        }
    })
</script>

<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
<script>
    // $(function() {
    //     $('#btn-login').on('click', function() {
    //         var userMail = $('#email').val();
    //         var password = $('#password').val();
    //         if(userMail === '' || password === '') {
    //             return true;
    //         }
    //         // if (typeof(Strage) === "undefined") {
    //         //     console.log('サポートされていません。')
    //         // }
    //         if($('#autoLogin').val() === 'on') {
    //             var strage = window.localStorage;
    //             var user = {
    //                 email: userMail,
    //                 pass: password,
    //             };
    //             strage.removeItem('eventScheduleUser');
    //             // ローカルストレージに保存
    //             strage.setItem('eventScheduleUser', JSON.stringify(user));
    //             // console.log(JSON.parse(strage.getItem('eventScheduleUser')));
    //         }
    //     });
    //     let strage = window.localStorage;
    //     let user = JSON.parse(strage.getItem('eventScheduleUser'));
    //     if(user !== null) {
    //         $('#email').val(user.email);
    //         $('#password').val(user.pass);
    //     }
    //     const linebtn = document.getElementById('btn-line');
    //     linebtn.addEventListener('mouseover', (e) => {
    //         e.toElement.src="/resource/images/DeskTop/2x/20dp/btn_login_hover.png"    
    //     });
    //     linebtn.addEventListener('mouseout', () => {
    //         linebtn.src="/resource/images/DeskTop/2x/20dp/btn_login_base.png"    
    //     });

    // })
</script>
</body>
</html>