<div id="app">
    <h1>お問い合わせ</h1>
    <p style="color:red">{{ msg }}</p>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
        名前
        <input id="name" class="form-control" type="text" v-model="name" required maxlength="50">
    </p>
    <p>
        メール
        <input class="form-control" type="email" v-model="email" maxlength="50">
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
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el:"#app",
        data: {
            selectedevent: '',
            msg: '',
            eventList: [],
            name: '',
            email: '',
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
                    this.name = data.name;
                    this.email = data.email;
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
                if (!confirm('送信してよろしいですか。')) return;
                let params = new URLSearchParams();
                params.append('game_id', 'selectedevent');
                params.append('name', this.name);
                params.append('email', this.email);
                params.append('content', this.content);
                fetch('/inquiry/inquiryComplete', {
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