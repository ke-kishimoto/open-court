<div id="app">
    <p style="color:red">{{ msg }}</p>

    <h1>イベント一括登録</h1>
    下記の内容で一括登録できます。
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            職種
            <select v-model="user.occupation" class="custom-select mr-sm-2">
                <option v-for="item in occupationOptions" v-bind:value="item.value">{{ item.text }}</option>
            </select>
        </p>
        <p>
            性別
            <select v-model="user.sex" class="custom-select mr-sm-2">
                <option v-for="item in sexOptions" v-bind:value="item.value">{{ item.text }}</option>
            </select>
        </p>
        <p>名前<input class="form-control" type="text" v-model="user.name" required></p>
        <?php if(!(isset($_SESSION['user']) && !empty($_SESSION['user']['line_id'] ?? ''))): ?>
            <p>
                メールアドレス
                <p>メール<input class="form-control" type="email" v-model="user.email"></p>
            </p>
        <?php endif; ?>
        <p>備考<textarea class="form-control" v-model="user.remark"></textarea></p>

        <button class="btn btn-secondary" type="button" @click="addCompanion">同伴者追加</button>

        <div v-for="(companion, index) in companions" v-bind:key="index">
            {{ index + 1 }}人目 
            <button class="btn btn-danger" type="button" @click="deleteCompanion(index)">同伴者削除</button>
            <p>名前<input class="form-control" type="text" v-model="companion.name" required></p>
            <p>
            職種
            <select class="custom-select mr-sm-2" v-model="companion.occupation">
                <option v-for="item in occupationOptions" v-bind:value="item.value">{{ item.text }}</option>
            </select>
            </p>
            <p>
            性別
            <select class="custom-select mr-sm-2" v-model="companion.sex">
                <option v-for="item in sexOptions" v-bind:value="item.value">{{ item.text }}</option>
            </select>
            </p>
        </div>
    
        <br>
        イベント一覧<br>
        ※予約済みのイベントは表示されません。<br>
        <hr>

        <div v-for="event in eventList" class="eventList-checkbox">
            <div class="eventList-item">
                <input type="checkbox" class="form-check-input" v-model="idList" v-bind:value="event.id">
                <label class="form-check-label">参加</label>
            </div>
            <div class="eventList-item">
                <br>
                {{ event.title }} <br>
                日付：{{ event.game_date }} </br>
                時間：{{ event.start_time }} ～ {{ event.end_time }} </br>
                場所：{{ event.place }}</br>
            </div>
        </div>
    
        <button class="btn btn-primary" type="button" @click="register">
            一括登録
        </button>
</div>

<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el: "#app",
        data: {
            msg: '',
            user: {},
            eventList: [],
            occupationOptions: [
                {text: '社会人', value: '1'},
                {text: '大学生', value: '2'},
                {text: '高校生', value: '3'},
            ],
            sexOptions: [
                {text: '男性', value: '1'},
                {text: '女性', value: '2'},
            ],
            companions: [],
            idList: [],
        },
        methods: {
            getEventList() {
                let params = new URLSearchParams();
                params.append('email', this.user.email);
                params.append('line_id', this.user.line_id);
                fetch('/api/event/getGameInfoListByAfterDate', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.eventList = data;
                    })
                )
                .catch(errors => console.log(errors))
            },
            addCompanion() {
                this.companions.push({name: '', occupation: '1', sex: '1'})
            },
            deleteCompanion(index) {
                this.companions.splice(index, 1)
            },
            register() {
                if (!confirm('登録してよろしいですか。')) return;
                let data = {
                    register: true,
                    csrf_token: this.csrf_token,
                    name: this.user.name,
                    occupation: this.user.occupation,
                    sex: this.user.sex,
                    email: this.user.email,
                    remark: this.user.remark,
                    companion: this.companions,
                    idList: this.idList
                }
                fetch('/api/event/participantBatchRegist', {
                    headers:{
                        'Content-Type': 'application/json',
                    },
                    method: 'post',
                    body: JSON.stringify(data)
                })
                .then(() => {
                    this.msg = '登録完了しました。'
                    this.getEventList()
                })
                .catch(errors => console.log(errors))
            }
        },
        created: function() {
            fetch('/api/data/getLoginUser', {
                    method: 'post',
                })
                .then(res => res.json()
                    .then(data => {
                        this.user = data;
                        let params = new URLSearchParams();
                        params.append('id', this.user.id);
                        fetch('/api/user/getDefaultCompanion', {
                            method: 'post',
                            body: params
                        })
                        .then(response => response.json()
                            .then(con => this.companions = con));

                        this.getEventList()
                    })
                )
                .catch(errors => console.log(errors))
        }

    })
</script>
</body>
</html>