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
            companions: [],
            user: {},
            adminFlg: '0',
            registered: false,
            editId: -1,
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
            // ログインユーザーの取得
            fetch('/api/data/getLoginUser', {
                method: 'post',
            }).then(res => res.json()
                .then(data => {
                    this.user = data;
                    this.adminFlg = this.user.admin_flg;
                    if (this.user.id == '') return 
                    
                    // イベントに参加登録済みか
                    let params = new URLSearchParams();
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
                                this.editId = data.id
                                if(this.registered) {
                                    // 登録時の情報を取得
                                    params = new URLSearchParams();
                                    params.append('tableName', 'Participant');
                                    params.append('id', this.editId);
                                    fetch('/api/data/selectById', {
                                        method: 'post',
                                        body: params
                                    }).then(res => res.json().then(participant => {
                                        this.user = participant;
                                        // 同伴者取得
                                        params = new URLSearchParams();
                                        params.append('participant_id', participant.id);
                                        fetch('/api/event/getCompanionList', {
                                            method: 'post',
                                            body: params
                                        }).then(res => res.json().then(companipn => {
                                            this.companions = companipn
                                        }))
                                    }))
                                } else {
                                    // デフォルトの同伴者取得
                                    let params = new URLSearchParams()
                                    params.append('id', this.user.id)
                                    fetch('/api/user/getDefaultCompanion', {
                                        method: 'post',
                                        body: params
                                    }).then(res => res.json().then(companion => this.companions = companion))
                                }
                            })
                        }
                    )
                })
            )
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
            this.companions.push({name: '', occupation: this.user.occupation, sex: this.user.sex})
        },
        deleteCompanion(index) {
            this.companions.splice(index, 1)
        },
        register() {
            if(this.user.occupation === '') {
                this.msg = '職種を入力してください。'
                scrollTo(0, 0)
                return 
            }
            if(this.user.sex === '') {
                this.msg = '性別を入力してください。'
                scrollTo(0, 0)
                return 
            }
            if(this.user.name === '') {
                this.msg = '名前を入力してください。'
                scrollTo(0, 0)
                return 
            }
            if(this.user.line_id === '' && this.user.email === '') {
                this.msg = 'メールアドレスを入力してください。'
                scrollTo(0, 0)
                return
            }

            if (!confirm('登録してよろしいですか。')) return;
            
            let data = {
                gameid: this.getParam('gameid'),
                csrf_token: this.csrf_token,
                user: this.user,
                companion: this.companions,
                editId: this.editId,
            }
            fetch('/api/event/participantRegist', {
                headers:{
                    'Content-Type': 'application/json',
                },
                method: 'post',
                body: JSON.stringify(data),
            })
            .then(() => {
                this.msg = '登録完了しました。'
                this.clear()
                scrollTo(0, 0)
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
            <p v-if="adminFlg == '1'"> 
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
            
            <p><button class="btn btn-secondary" type="button" @click="addCompanion">同伴者追加</button></p>

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
                <button class="btn btn-primary" type="button" @click="register">
                    <template v-if="registered">修正</template>
                    <template v-else>登録</template>
                </button>
                <a v-if="registered" class="btn btn-danger" v-bind:href="'/participant/cancel?gameid=' + getParam('gameid')">参加のキャンセル</a>
            </p>
        
    </div>`
})