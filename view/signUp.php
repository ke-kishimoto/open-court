<div id="app">
    <p style="color:red">{{ msg }}</p>

    <div class="explain-box">
        <span class="explain-tit">新規登録</span>
        <p>イベントへ応募時、以下の入力項目がデフォルトで設定されます</p>
    </div>
    <a v-if="editId !== -1 && (user.line_id === '' || user.line_id === null)" class="btn btn-sm btn-outline-dark" href="/user/passwordchange" role="button">
      パスワード変更
    </a>
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

        <p v-if="(user.line_id === '' || user.line_id === null)">
            メール<input class="form-control" type="email" v-model="user.email">
        </p>

        <div v-if="editId === -1">
            <p>
                パスワード
                <input class="form-control" type="password" v-model="user.password" required maxlength="50">
            </p>
            <p>
                パスワード(再入力)
                <input class="form-control" type="password" v-model="rePassword" required maxlength="50">
            </p>
        </div>
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

        <button class="btn btn-primary" type="button" @click="register">登録</button>
    <br>
    <div v-if="editId !== -1">
        <form action="/user/delete">
            <button class="btn btn-danger" type="submit">退会</button>
        </form>
    </div>
    <hr>
    <div v-if="editId === -1">
        <p>LINEでログイン</p>
        <div class="line-login">
            <a v-bind:href="'https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=' + clientId + '&redirect_uri=https%3A%2F%2Fopencourt.eventmanc.com%2Fuser%2Flinelogin&state=' + state + '&bot_prompt=aggressive&scope=profile%20openid'">
                <img id="btn-line" src="/resource/images/DeskTop/2x/20dp/btn_login_base.png">
            </a>
        </div>
    </div>
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",
        data: {
            msg: '',
            user: {},
            rePassword: '',
            companions: [],
            occupationOptions: [
                {text: '社会人', value: '1'},
                {text: '大学生', value: '2'},
                {text: '高校生', value: '3'},
            ],
            sexOptions: [
                {text: '男性', value: '1'},
                {text: '女性', value: '2'},
            ],
            editId: -1,
            state: '',
            clientId: '',
        },
        methods: {
            getLoginUser() {
            fetch('/api/data/getLoginUser', {
                method: 'post',
            }).then(res => res.json()
                .then(data => {
                    this.user = data
                    if(this.user.id != null && this.user.id != '') {
                        this.editId = this.user.id
                        let params = new URLSearchParams()
                        params.append('id', this.user.id)
                        fetch('/api/user/getDefaultCompanionList', {
                            method: 'post',
                            body: params
                        }).then(res => res.json().then(companion => this.companions))
                    }
                }))
            },
            addCompanion() {
                this.companions.push({name: '', occupation: '1', sex: '1'})
            },
            deleteCompanion(index) {
                this.companions.splice(index, 1)
            },
            getLineParam() {
                fetch('/api/user/getLineParam')
                .then(res => res.json()
                    .then(data => {
                        this.clientId = data.clientId;
                        this.state = data.state
                    })
                )
                .catch(errors => console.log(errors))
            },
            register() {
                if (!confirm('登録してよろしいですか。')) return;
                
                if(this.editId === -1) {
                    if(this.rePassword !== this.user.password) {
                        this.msg = '入力されたパスワードが異なります'
                        return
                    }
                }
                let data = {
                    user: this.user,
                    companion: this.companions,
                    editId: this.editId,
                }
                fetch('/api/user/userRegist', {
                    headers:{
                        'Content-Type': 'application/json',
                    },
                    method: 'post',
                    body: JSON.stringify(data),
                })
                .then(res => {
                    res.json().then(data => {
                        if(data.errMsg === '') {
                            this.msg = '登録完了しました。'
                        } else {
                            this.msg = data.errMsg
                        }
                        location.href = '#header'
                    })
                })
                .catch(errors => console.log(errors))

            }
        },
        created: function() {
            this.getLoginUser()
            this.getLineParam()
        }
    })
</script>
</body>
</html>