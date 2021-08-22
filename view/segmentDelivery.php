<div id="app">

    <vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>

    <h1>セグメント配信</h1>

    <p>配信対象者 {{ targetUser.length }} 人</p>
    <p>
        職種
        <select v-model="occupation" class="custom-select mr-sm-2" @change="getTargetUser">
            <option v-for="item in occupationOptions" v-bind:value="item.value">{{ item.text }}</option>
        </select>
        </p>
    <p>
        性別
        <select v-model="sex" class="custom-select mr-sm-2" @change="getTargetUser">
            <option v-for="item in sexOptions" v-bind:value="item.value">{{ item.text }}</option>
        </select>
    </p>
    <p>
        イベント
        <select v-model="selectedevent" class="custom-select mr-sm-2" @change="getTargetUser">
            <option v-for="event in eventList" v-bind:key="event.id" v-bind:value="event.id">{{ event.title }}</option>
        </select>
    </p>
    <p>
        メッセージ
        <textarea class="form-control" name="content" rows="5" maxlength="2000" v-model="content"></textarea>
    </p>
    
    <button class="btn btn-primary" type="button" @click="sendMessage">
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
    const vue = new Vue({
        el:"#app",
        data: {
            msg: '',
            occupation: '',
            sex: '',
            selectedevent: '',
            occupationOptions: [
                {text: '全て', value: ''},
                {text: '社会人', value: '1'},
                {text: '大学生', value: '2'},
                {text: '高校生', value: '3'},
            ],
            sexOptions: [
                {text: '全て', value: ''},
                {text: '男性', value: '1'},
                {text: '女性', value: '2'},
            ],
            eventList: [],
            content: '',
            targetUser: [],
            
        },
        methods: {
            clear() {
                this.occupation = ''
                this.sex = ''
                this.selectedevent = ''
                this.content = ''
            },
            getEventList() {
                fetch('/api/event/getGameInfoListByAfterDate', {
                    method: 'post',
                })
                .then(res => res.json()
                    .then(json => {
                        this.eventList = json
                        this.eventList.unshift({id:'', template_name: ''})
                        this.getTargetUser()
                    })
                )
                .catch(errors => console.log(errors))
            },
            getTargetUser() {
                let params = new URLSearchParams()
                params.append('occupation', this.occupation)
                params.append('sex', this.sex)
                params.append('eventId', this.selectedevent)
                fetch('/api/line/getTargetUser', {
                    method: 'post',
                    body: params,
                }).then(res => res.json().then(data => this.targetUser = data))
                .catch(errors => console.log(errors))

            },
            sendMessage() {
                if(!confirm('送信してよろしいですか。')) return
                if(this.targetUser.length === 0) {
                    this.msg = '配信対象者が存在しません。'
                    return
                }
                let data = {
                    users: this.targetUser,
                    message: this.content,
                }
                fetch('/api/line/sendMessage', {
                    headers:{
                        'Content-Type': 'application/json',
                    },
                    method: 'post',
                    body: JSON.stringify(data)
                }).then(() => {
                    this.msg = 'メッセージを送信しました。'
                    this.clear()
                })
                .catch(errors => console.log(errors))
            }
        },
        created: function() {
            this.getEventList()
        }
    })
</script>