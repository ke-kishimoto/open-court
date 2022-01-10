<div id="app" v-cloak>
    <vue-header></vue-header>

    <p style="color:red">{{ msg }}</p>

    <p>参加者登録</p>
        <p> 
            <select v-model="selectedUser" @change="selectUser($event)">
                <option v-for="user in userList" v-bind:key="user.id" v-bind:value="user.id">
                    {{ user.name }}
                </option>
            </select>

        </p>
        <p>
        職種
        <select v-model="occupation" class="custom-select mr-sm-2" disabled>
            <option v-for="item in occupationOptions" v-bind:value="item.value">{{ item.text }}</option>
        </select>
        </p>
        <p>
        性別
        <select v-model="sex" class="custom-select mr-sm-2" disabled>
            <option v-for="item in sexOptions" v-bind:value="item.value">{{ item.text }}</option>
        </select>
        </p>
        <p>名前<input class="form-control" type="text" v-model="name" required disabled></p>
        <p>メール<input class="form-control" type="email" v-model="email" disabled></p>
        <p>備考<textarea class="form-control" v-model="remark"></textarea></p>
        
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
        
        <p>
            <button class="btn btn-primary" type="button" @click="register">登録</button>
            <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
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
            csrf_token: '',
            msg: '',
            selectedUser: '',
            userList: [],
            occupation: '1',
            occupationOptions: [
                {text: '社会人', value: '1'},
                {text: '大学生', value: '2'},
                {text: '高校生', value: '3'},
            ],
            sex: '1',
            sexOptions: [
                {text: '男性', value: '1'},
                {text: '女性', value: '2'},
            ],
            name: '',
            email: '',
            remark: '',
            companions: [],

        },
        methods: {
            clear() {
                this.selectedUser = ''
                this.occupation = '1'
                this.sex = '1'
                this.name = ''
                this.email = ''
                this.remark = ''
                this.companions = []
            },
            selectUser(event) {
                let params = new URLSearchParams();
                params.append('tableName', 'Users');
                params.append('id', event.target.value);
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.name = data.name;
                        this.occupation = data.occupation;
                        this.sex = data.sex;
                        this.remark = data.remark;
                        this.email = data.email;
                        params = new URLSearchParams();
                        params.append('id', event.target.value);
                        fetch('/api/user/getDefaultCompanion', {
                            method: 'post',
                            body: params
                        })
                        .then(response => response.json()
                            .then(con => this.companions = con))
                    })
                )
                .catch(errors => console.log(errors))
            },
            getUserList() {
                let params = new URLSearchParams();
                params.append('tableName', 'Users');
                fetch('/api/data/selectAll', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.userList = data;
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
                    game_id: this.getParam('game_id'),
                    register: true,
                    csrf_token: this.csrf_token,
                    name: this.name,
                    occupation: this.occupation,
                    sex: this.sex,
                    email: this.email,
                    remark: this.remark,
                    companion: this.companions
                }
                fetch('/api/event/participantRegist', {
                    headers:{
                        'Content-Type': 'application/json',
                    },
                    method: 'post',
                    body: JSON.stringify(data)
                })
                .then(() => {
                    this.msg = '登録完了しました。'
                    this.clear()
                })
                .catch(errors => console.log(errors))

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
            this.getUserList()
        }
    })
</script>
</body>
</html>