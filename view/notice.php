<div id="app">
    <vue-header></vue-header>

    <h1>お知らせ登録</h1>
    <p style="color:red">{{ msg }}</p>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            <select @change="selectNotice($event)" v-model="selectedNotice">
                <option v-for="notice in noticeList" v-bind:key="notice.id" v-bind:value="notice.id">{{ notice.title }}</option>
            </select>
            <input type="checkbox" id="isnew" v-model="isnew">
            <label for="isnew">コピーして新規作成</label> 
        </p>
        <p>
            お知らせタイトル
            <input class="form-control" type="text" v-model="title" maxlength="30">
        </p>
        <p>
            お知らせ内容
            <textarea class="form-control" v-model="content" rows="5" maxlength="2000"></textarea>
        </p>

        <button class="btn btn-primary" type="button" @click="register">
            登録
        </button>

        <button class="btn btn-danger" type="button" @click="deleteNotice">
            削除
        </button>
    <vue-footer></vue-footer>
</div>
<script src="/resource/js/common_admin.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    const app = new Vue({
        el:"#app",
        data: {
            noticeId: -1,
            selectedNotice: '',
            msg: '',
            isnew: false,
            noticeList: [],
            title: '',
            content: '',
        },
        methods: {
            getNoticeList() {
                let params = new URLSearchParams();
                params.append('tableName', 'notice');
                fetch('/api/data/selectAll', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(json => {
                        this.noticeList = json;
                        this.noticeList.unshift({id:'', title: ''});
                    })
                )
                .catch(errors => console.log(errors))
            },
            clear() {
                this.isnew = false;
                this.noticeId = -1;
                this.selectedNotice = '';
                this.title = '';
                this.content = '';
            },
            selectNotice() {
                let params = new URLSearchParams();
                params.append('tableName', 'notice');
                params.append('id', event.target.value);
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.noticeId = data.id;
                        this.title = data.title;
                        this.content = data.content;
                    })
                )
                .catch(errors => console.log(errors))
            },
            register() {
                if (!confirm('登録してよろしいですか。')) return;
                let type = 'insert';
                if(this.noticeId != -1 && this.isnew === false) {
                    type = 'update'
                }
                let params = new URLSearchParams();
                params.append('tableName', 'notice');
                params.append('type', type);
                params.append('id', this.noticeId);
                params.append('title', this.title);
                params.append('content', this.content);
                fetch('/api/data/updateRecord', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.getNoticeList()
                        this.clear()
                        this.msg = '登録完了しました。'
                    }
                })
            },
            deleteNotice() {
                if (!confirm('削除してよろしいですか。')) return;
                let params = new URLSearchParams();
                params.append('tableName', 'notice');
                params.append('id', this.noticeId);
                fetch('/api/data/deleteById', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.getNoticeList()
                        this.clear()
                        this.msg = '削除完了しました。'
                    }
                })
            }
        },
        created: function() {
            this.getNoticeList()
        }
    })
</script>
</body>
</html>