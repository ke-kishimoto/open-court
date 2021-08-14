<div id="app">
    
    <vue-header></vue-header>

    <p style="color:red; font-size:20px;">{{ msg }}</p>

    <h1>改善目安箱</h1>
    <p>システムに関する不具合・及び要望がありましたら、こちらからご報告ください。</p>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            名前
            <input class="form-control" type="text" required maxlength="50" v-model="troubleReport.name">
        </p>
        <p>
            カテゴリ
            <select class="custom-select mr-sm-2" v-model="troubleReport.category">
                <option v-for="item in categories" v-bind:value="item.value">{{ item.text }}</option>
            </select>
        </p>
        <p>
            タイトル
            <input class="form-control" type="text" required maxlength="30" v-model="troubleReport.title">
        </p>
        <p>
            詳細
            <textarea class="form-control" rows="5" maxlength="2000" v-model="troubleReport.content"></textarea>
        </p>

        <button class="btn btn-primary" type="button" @click="register">
            送信
        </button>

    <vue-footer></vue-footer>

</div>

<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",
        data: {
            msg: '',
            troubleReport: {
                    name: '', 
                    category: '1',
                    title: '',
                    content: '',
                },
            categories: [
                {text: '障害・不具合', value: '1'},
                {text: '要望', value: '2'},
                {text: 'その他', value: '3'},
            ],
        },
        methods: {
            clear() {
                this.troubleReport = {}
            },
            getLoginUser() {
                fetch('/api/data/getLoginUser', {
                    method: 'post',
                }).then(res => res.json().then(data => {
                    this.troubleReport.name = data.name;
                }))
            },
            register() {
                if(this.troubleReport.name === '') {
                    this.msg = '名前を入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if(this.troubleReport.title === '') {
                    this.msg = 'タイトルを入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if(this.troubleReport.content === '') {
                    this.msg = '内容を入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if (!confirm('送信してよろしいですか。')) return;
                let params = new URLSearchParams();
                params.append('name', this.troubleReport.name);
                params.append('category', this.troubleReport.category);
                params.append('title', this.troubleReport.title);
                params.append('content', this.troubleReport.content);
                fetch('/api/contact/sendTroubleReport', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.clear()
                        this.msg = '送信完了しました。'
                    }
                })
            }
        },
        created: function() {
            this.getLoginUser()
        }
    })
</script>
</body>
</html>