<div id="app">
    <vue-header></vue-header>
    <p style="color:red; font-size:20px">{{ msg }}</p>

    <h1>お問い合わせ</h1>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
        名前
        <input class="form-control" type="text" v-model="user.name" required maxlength="50">
    </p>
    <p v-if="user.line_id === null || user.line_id === ''">
        メール
        <input class="form-control" type="email" v-model="user.email" maxlength="50">
    </p>
    <p>
        対象イベント
        <select v-model="selectedevent">
            <option v-for="event in eventList" v-bind:key="event.id" v-bind:value="event.id">{{ event.title }}</option>
        </select>
    </p>
    <p>
        お問い合わせ内容
        <textarea class="form-control" name="content" rows="5" maxlength="2000" v-model="content"></textarea>
    </p>

    <button class="btn btn-primary" type="button" @click="register">
        送信
    </button>
    <vue-footer></vue-footer>

</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el:"#app",
        data: {
            selectedevent: '',
            msg: '',
            eventList: [],
            user: {},
            gameId: '',
            content: '',
        },
        methods: {
            clear() {
                this.content = ''
            },
            getLoginUser() {
                fetch('/api/data/getLoginUser', {
                    method: 'post',
                }).then(res => res.json().then(data => {
                    this.user = data;
                }))
            },
            getEventList() {
                fetch('/api/event/getGameInfoListByAfterDate', {
                    method: 'post',
                })
                .then(res => res.json()
                    .then(json => {
                        this.eventList = json;
                        this.eventList.unshift({id:'', template_name: ''});
                    })
                )
                .catch(errors => console.log(errors))
            },
            register() {
                if(this.user.name === '') {
                    this.msg = '名前を入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if((this.user.line_id === null || this.user.line_id === '') && this.user.email === '') {
                    this.msg = 'メールアドレスを入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if(this.content === '') {
                    this.msg = '問い合わせ内容を入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if (!confirm('送信してよろしいですか。')) return;
                let params = new URLSearchParams();
                params.append('game_id', 'selectedevent');
                params.append('name', this.user.name);
                params.append('email', this.user.email);
                params.append('content', this.content);
                fetch('/api/contact/sendInquiry', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.clear()
                        this.msg = '送信完了しました。'
                    }
                })
            }
        }, 
        created: function() {
            this.getEventList()
            this.getLoginUser()
        }
    });

</script>
</body>
</html>