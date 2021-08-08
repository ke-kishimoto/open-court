Vue.component('participate', {
    data: function() {
        return {
            csrf_token: '',
            msg: '',
            selectedUser: '',
            userList: [],
            occupationOptions: [
                {text: '社会人', value: '1'},
                {text: '大学生', value: '2'},
                {text: '高校生', value: '3'},
            ],
            sexOptions: [
                {text: '男性', value: '1'},
                {text: '女性', value: '2'},
            ],
            // name: '',
            // occupation: '1',
            // sex: '1',
            // email: '',
            // remark: '',
            companions: [],
            user: {},
        }
    }, 
    methods: {
        clear() {
            this.selectedUser = ''
            this.user.occupation = '1'
            this.user.sex = '1'
            this.user.name = ''
            this.user.email = ''
            this.user.remark = ''
            this.user.admin_flg = '0'
            this.companions = []
        },
        getLoginUser() {
            fetch('/api/data/getLoginUser', {
                method: 'post',
            }).then(res => res.json()
                .then(data => {
                    if(data.admin_flg == '0') {
                        this.user = data
                    }
                })
            )
        },
        selectUser(event) {
            let params = new URLSearchParams();
            params.append('tableName', 'users');
            params.append('id', event.target.value);
            fetch('/api/data/selectById', {
                method: 'post',
                body: params
            })
            .then(res => res.json()
                .then(data => {
                    // this.name = data.name;
                    // this.occupation = data.occupation;
                    // this.sex = data.sex;
                    // this.remark = data.remark;
                    // this.email = data.email;
                    this.user = data
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
            params.append('tableName', 'users');
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
            console.log(this.companions)
        },
        register() {
            
            if (!confirm('登録してよろしいですか。')) return;
            
            let data = {
                gameid: this.getParam('gameid'),
                csrf_token: this.csrf_token,
                user: this.user,
                companion: this.companions
            }
            fetch('/api/event/participantRegist', {
                headers:{
                    'Content-Type': 'application/json',
                },
                method: 'post',
                body: data,
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
        this.getLoginUser()
        this.getUserList()
    }, 
    template: `
    <div id="app">
        <p style="color:red">{{ msg }}</p>

        <p>参加者登録</p>
            <input type="hidden" id="participant_id" name="id" value="<?php echo $participant['id'] ?>">
            <input type="hidden" name="game_id" value="<?php echo $_GET['game_id'] ?>">
            <input type="hidden" name="csrf_token" v-model="csrf_token" value="<?=$csrf_token?>">
            <p v-if="user.admin_flg == '1'"> 
                <select v-model="selectedUser" @change="selectUser($event)">
                    <option v-for="user in userList" v-bind:key="user.id" v-bind:value="user.id">
                        {{ user.name }}
                    </option>
                </select>
            </p>
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
            <p>メール<input class="form-control" type="email" v-model="user.email"></p>
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
            
            <p>
                <button class="btn btn-primary" type="button" @click="register">登録</button>
                <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
            </p>
        
        <p><a href="/admin/event/eventInfo?gameid=<?php echo $_GET['game_id'] ?>">イベント情報ページに戻る</a></p>
    </div>`
})