<div id="app" v-cloak>
    <vue-header></vue-header>

    <h1>ユーザー一覧</h1>
    <div v-for="user in userList" v-bind:key="user.id">
        ユーザー名：{{ user.name }} <br>
        職種：{{ user.occupation_name }} <br>
        性別：{{ user.sex_name }} <br>
        メールアドレス：{{ user.email }} <br>
        権限：{{ user.authority_name }} <br>
        ステータス：{{ user.status }} <br>
        <br>
        <button class="change-authority btn btn-info" type="button" @click="changeAuthority(user)">権限の変更</button>
        <hr>
    </div>
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
            userList: [],
        },
        methods: {
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
            changeAuthority(user) {
                let params = new URLSearchParams();
                params.append('tableName', 'users');
                params.append('id', user.id);
                fetch('/api/data/updateFlg', {
                    method: 'post',
                    body: params
                })
                .then(() => {
                    params = new URLSearchParams();
                    params.append('tableName', 'users');
                    params.append('id', user.id);
                    fetch('/api/data/selectById', {
                        method: 'post',
                        body: params
                    }).then(res => res.json().then(data => user.authority_name = data.authority_name))
                })
                .catch(errors => console.log(errors))
            }
        },
        created: function() {
            this.getUserList()
        }
    })
</script>
</body>
</html>