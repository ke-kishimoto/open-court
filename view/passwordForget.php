<div id="app">
    
    <vue-header></vue-header>

    <p style="color:red">{{ msg }}</p>

    <h1>パスワード再発行</h1>
    <div>
        <p>
            登録時に使用したメールアドレスを入力してください。
            <input class="form-control" type="email" required maxlength="50" v-model="email">
        </p>
        <button class="btn btn-primary" type="button" @click="passReset">送信</button>
    </div>

    <vue-footer></vue-footer>

</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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
        },
        methods: {
            passReset() {
                if(this.email === '') {
                    this.msg = 'メールアドレスを入力してください。'
                    return
                }
                if (!confirm('送信してよろしいですか？')) return

                let params = new URLSearchParams()
                params.append('email', this.email)
                fetch('/api/user/passReset', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => this.msg = data.msg))
            }
        }
    })

</script>
</body>
</html>