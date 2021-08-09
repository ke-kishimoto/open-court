<div id="app">
    <p style="color:red">{{ msg }}</p>

    <!-- <form id="signUp_form" action="/user/passwordchangecomplete" method="post" class="form-group"> -->
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <div id="password-area">
            <p>
                パスワード
                <input class="form-control" type="password" v-model="password" required maxlength="50">
            </p>
            <p>
                パスワード(再入力)
                <input class="form-control" type="password" v-model="rePassword" required maxlength="50">
            </p>
        </div>
        <button class="btn btn-primary" type="button" @click="changePassword">登録</button>
    <!-- </form> -->
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",
        data: {
            msg: '',
            password: '',
            rePassword: '',
        }, 
        methods: {
            changePassword() {
                if(this.password === '') {
                    this.msg = 'パスワードを入力してください。'
                    return
                }

                if(this.password !== this.rePassword) {
                    this.msg = '入力されたパスワードが異なります。'
                    return
                }
                let params = new URLSearchParams()
                params.append('password', this.password)
                fetch('/api/user/changePassword', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(result => {
                    if(result.errMsg === '') {
                        this.msg = 'パスワードを変更しました。'
                    }
                })
                )
            },

        }
    })
</script>
</body>
</html>