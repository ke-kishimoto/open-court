<div id="app" v-cloak>
    <vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>

    <div class="explain-box">
        <span class="explain-tit">新規登録</span>
        <p>イベントへ応募時、以下の入力項目がデフォルトで設定されます</p>
    </div>
        <input type="hidden" v-model="user.id" value="<?php echo $user['id'] ?>">
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
        <p>
            名前
            <input class="form-control" type="text" v-model="user.name" required maxlength="50" value="<?php echo $user['name'] ?>">
        </p>
        <p>
            備考
            <textarea class="form-control" v-model="user.remark" maxlength="200"></textarea>
        </p>
        <button class="btn btn-primary" type="button" @click="userUpdate">登録</button>
    <br>

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
            user: {},
            occupationOptions: [
                {text: '社会人', value: '1'},
                {text: '大学生', value: '2'},
                {text: '高校生', value: '3'},
            ],
            sexOptions: [
                {text: '男性', value: '1'},
                {text: '女性', value: '2'},
            ],
        },
        methods: {
            userUpdate() {
                let params = new URLSearchParams()
                params.append('tableName', 'Users')
                params.append('type', 'update')
                params.append('id', this.user.id)
                params.append('name', this.user.name)
                params.append('occupation', this.user.occupation)
                params.append('sex', this.user.sex)
                params.append('remark', this.user.remark)
                fetch('/api/data/updateRecord')
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                        this.msg = 'エラーが発生しました。'
                        scrollTo(0, 0)
                    } else {
                        location.href = '/'
                    }
                })
            }
        }
    })
</script>
</body>
</html>