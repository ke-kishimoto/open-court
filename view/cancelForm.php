
<div id="app">

    <vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>

    <div v-if="user.line_id === null || user.line_id === ''">
        <p>
            メールアドレス
            <input class="form-control" type="email" v-model="user.email">
        </p>
        <div id="password-area">
            <p>
                パスワード
                <input class="form-control" type="password" v-model="user.password">
            </p>
        </div>
    </div>
    <button class="btn btn-primary" type="button" @click="cancel">参加キャンセル</button>

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
            user: {},
        },
        methods: {
            getLoginUser() {
                fetch('/api/data/getLoginUser', {
                    method: 'post',
                }).then(res => res.json()
                    .then(data => {
                        this.user = data
                        this.user.password = ''
                }))
            },
            cancel() {
                if(this.user.line_id === null || this.user.line_id === '') {
                    if(this.user.email === '') {
                        this.msg = 'メールアドレスを入力してください。'
                        scrollTo(0, 0)
                        return
                    }
                    if(this.user.password === '') {
                        this.msg = 'パスワードを入力してください。'
                        scrollTo(0, 0)
                        return
                    }
                }
                if (!confirm('キャンセルしてよろしいですか？')) return
                let params = new URLSearchParams()
                params.append('game_id', this.getParam('gameid'))
                params.append('email', this.user.email)
                params.append('password', this.user.password)
                fetch('/api/event/cancel', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => {
                    this.msg = data.msg
                    scrollTo(0, 0)
                }))
            },
            getParam(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            },
        },
        created: function() {
            this.getLoginUser()
        }
    })
</script>

</body>
</html>