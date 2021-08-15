<div id="app">
    <vue-header></vue-header>

    <div v-if="mode !== 'new'">
        <p v-if="today > event.game_date" style="color: red;">※終了したイベントのため応募できません</p>
        <h3>イベント情報</h3>
        <p>{{ event.title }}</p>
        <p>日付：{{ event.game_date }}</p>
        <p>時間：{{ event.start_time }} 〜 {{ event.end_time }}</p>
        <p>場所：{{ event.place }}</p>
        <p>詳細：{{ event.detail }}</p>
        <p>参加費：
            社会人：{{ event.price1 }}円 
            大学・専門{{ event.price2 }}円 
            高校生：{{ event.price3 }}円 
        </p>
    </div>
    
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li v-if="admin" class="nav-item">
            <a class="nav-link active" id="event-info-tab" data-toggle="tab" href="#event-info" role="tab" aria-controls="home" v-bind:aria-selected="admin">
                イベント
            </a>
        </li>
        <li v-if="mode !== 'new'" class="nav-item">
            <a class="nav-link" id="add-tab" data-toggle="tab" href="#add" role="tab" aria-controls="contact" v-bind:aria-selected="!admin">
                <template v-if="admin">参加者追加</template>
                <template v-else>参加登録</template>
            </a>
        </li>
        <li v-if="mode !== 'new'" class="nav-item">
            <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="contact" aria-selected="false">
                参加者内訳
            </a>
        </li>
        <li v-if="mode !== 'new'" class="nav-item">
            <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="contact" aria-selected="false">
                参加者一覧
            </a>
        </li>
        
    </ul>
    
    <div class="tab-content" id="nav-tabContent">
    
        <div v-if="admin" class="tab-pane fade show active" id="event-info" role="tabpanel" aria-labelledby="event-info-tab">
            <event-regist></event-regist>
        </div>

        <div v-if="mode !== 'new'" class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
            <div v-if="today <= event.game_date">
                <br>
                <p>【応募フォーム】
                    <span v-if="registered" class="text-danger">
                    ※参加登録済みです
                    </span>
                </p>
                
                <participate></participate>
            </div>
        </div>
        
        <div v-if="mode !== 'new'" class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
            <participant-breakdown></participant-breakdown>
        </div>
    
        <div v-if="mode !== 'new'" class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
            <participant-list></participant-list>
        </div>
    </div>
    <vue-footer></vue-footer>
</div>


<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.js"></script>
<script src="/resource/js/participate.js"></script>
<script src="/resource/js/participant-breakdown.js"></script>
<script src="/resource/js/participant-list.js"></script>
<script src="/resource/js/event-regist.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",
        data: {
            mode: '',
            today: '',
            registered: false,
            user: {},
            event: {},
            admin: false,
        },
        methods: {
            getEventInfo() {
                if (this.getParam('gameid') === null) return
                let params = new URLSearchParams();
                params.append('tableName', 'gameInfo');
                params.append('id', this.getParam('gameid'));
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.event = data;
                    })
                )
                .catch(errors => console.log(errors))
            },
            getLoginUser() {
                fetch('/api/data/getLoginUser', {
                    method: 'post',
                }).then(res => res.json()
                    .then(data => {
                        this.user = data;
                        if (this.user.id == '') {
                            location.href = '/user/signIn'
                        } 
                        if(this.user.admin_flg == '1') {
                            this.admin = true
                        }
                        if(this.mode !== 'new') {
                            let params = new URLSearchParams();
                            params.append('tableName', 'Participant');
                            params.append('game_id', this.getParam('gameid'));
                            if(this.user.email !== null && this.user.email !== '') {
                                params.append('email', this.user.email);
                            } else if (this.user.line_id !== null && this.user.line_id !== '') {
                                params.append('line_id', this.user.line_id);
                            }
                            fetch('/api/event/existsCheck', {
                                method: 'post',
                                body: params
                            }).then(res => {res.json()
                                    .then(data => {
                                        this.registered = data.result
                                    })
                                }
                            )
                        }

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
            this.mode = this.getParam('mode')
            if(this.mode === 'new') {
                this.event.game_date = this.getParam('date')
            }
            this.getLoginUser()
            this.getEventInfo()
            let date = new Date()
            this.today = date.getFullYear() + '-' + ('00' + (date.getMonth()+1)).slice(-2) + '-' + ('00' + date.getDate()).slice(-2)
        }
    })
</script>
</body>
</html>