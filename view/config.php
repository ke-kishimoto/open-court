<div id="app" v-cloak>
    <vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>
    
    <h1>システム設定</h1>

    <hr>
    <p>システム名設定</p>
    <p>
        システム名<input class="form-control" type="text" required v-model="systemTitle">
    </p>
    <hr>
    <p>LINE通知設定</p>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
        参加者からの予約があった際にLINEへ通知を送るための設定画面です。<br> 
        1. <a href="https://notify-bot.line.me/ja/" target="_blank">LINE notify</a>へアクセスし、ログインしてください。<br>
        2. ログイン後は「マイページ」⇒「トークンの発行」を選択します。<br>
        3. 通知設定が来るようにしたいグループを選択し、トークン名を発行ボタンを押下します。<br>
        4. 発行されたトークンをコピーし、入力フォームに貼り付けて更新を押下します。
    </p>
    <p>
        LINE notify トークン<input class="form-control" type="text" required v-model="lineToken">
    </p>
    <p>
        通知設定
        <select class="form-control" v-model="lineNotifyFlg">
        <option v-for="item in notifyOptions" v-bind:value="item.value">
            {{ item.text }}
        </option>
        </select>
    </p>
    <hr>
    <p>背景色</p>
    <p>
        <select id="bg_color" v-model="bgColor" class="custom-select mr-sm-2">
        <option v-for="item in colors" v-bind:value="item.value">
            {{ item.text }}
        </option>
        </select>
    </p>
    <hr>
    <p>キャンセル待ちの更新</p>
    <p>
        <select id="waiting_flg_auto_update" v-model="waitingFlgAutoUpdate" class="custom-select mr-sm-2">
        <option v-for="item in waithingOptions" v-bind:value="item.value">
            {{ item.text }}
        </option>
        </select>
    </p>
    <hr>
    <p>
        SendGrid APIキー<input class="form-control"  type="text"  v-model="sendgridApiKey">
    </p>
    <hr>
    <p>
        LINE API用
    </p>
    <p>
        LINE ログイン：クライアントID<input class="form-control"  type="text" v-model="clientId">
    </p>
    <p>
        LINE ログイン：クライアントシークレット<input class="form-control"  type="text" v-model="clientSecret">
    </p>
    <p>
        LINE ログイン：コールバックURL<input class="form-control"  type="text" v-model="callbackURL">
    </p>
    <p>
        Messaging API：チャネルアクセストークン<input class="form-control"  type="text" v-model="channelAccessToken">
    </p>
    <p>
        Messaging API：チャネルシークレット<input class="form-control"  type="text" v-model="channelSecret">
    </p>
    <p>
        <button class="btn btn-primary" type="button" @click="register">登録</button>
    </p>
    
    <vue-footer></vue-footer>
</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    const app = new Vue({
        el:"#app",
        data: {
            msg: '',
            systemTitle: '',
            lineToken: '',
            lineNotifyFlg: '1',
            bgColor: 'white',
            waitingFlgAutoUpdate: '1',
            sendgridApiKey: '',
            clientId: '',
            clientSecret: '',
            channelAccessToken: '',
            channelSecret: '',
            callbackURL: '',
            notifyOptions: [
                {text: 'する', value: '1'},
                {text: 'しない', value: '0'}
            ],
            colors: [
                {text: '白', value: "white"},
                {text: 'オレンジ', value: "orange"},
                {text: 'ピンク', value: "pink"},
            ],
            waithingOptions: [
                {text: '手動', value: '0'},
                {text: '自動', value: '1'}
            ]
        },
        methods: {
            getConfig() {
                let params = new URLSearchParams();
                params.append('tableName', 'Config');
                params.append('id', 1);
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.systemTitle = data.system_title;
                        this.lineToken = data.line_token;
                        this.lineNotifyFlg = data.line_notify_flg;
                        this.bgColor = data.bg_color;
                        this.waitingFlgAutoUpdate = data.waiting_flg_auto_update;
                        this.sendgridApiKey = data.sendgrid_api_key;
                        this.clientId = data.client_id;
                        this.clientSecret = data.client_secret;
                        this.channelAccessToken = data.channel_access_token;
                        this.channelSecret = data.channel_secret;
                        this.callbackURL = data.callback_url;
                    })
                )
                .catch(errors => console.log(errors))
            },
            register() {
                if (!confirm('登録してよろしいですか。')) return;
                let params = new URLSearchParams();
                params.append('tableName', 'Config');
                params.append('type', 'update');
                params.append('id', 1);
                params.append('system_title', this.systemTitle);
                params.append('line_token', this.lineToken);
                params.append('line_notify_flg', this.lineNotifyFlg);
                params.append('bg_color', this.bgColor);
                params.append('waiting_flg_auto_update', this.waitingFlgAutoUpdate);
                params.append('sendgrid_api_key', this.sendgridApiKey);
                params.append('client_id', this.clientId);
                params.append('client_secret', this.clientSecret);
                params.append('channel_access_token', this.channelAccessToken);
                params.append('channel_secret', this.channelSecret);
                params.append('callback_url', this.callbackURL);
                fetch('/api/data/updateRecord', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.msg = '登録完了しました。'
                        scrollTo(0, 0)
                    }
                })
            }
        },
        created: function() {
            this.getConfig()
        }
    })
</script>
</body>
</html>