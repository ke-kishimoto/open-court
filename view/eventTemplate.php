<div id="app">
    <vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>

    <h1>テンプレート設定</h1>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p>
        <select @change="selectTemplate($event)" v-model="selectedTemplate">
            <option v-for="template in templateList" v-bind:key="template.id" v-bind:value="template.id">{{ template.template_name }}</option>
        </select>
        <input type="checkbox" id="isnew" v-model="isnew">
        <label for="isnew">コピーして新規作成</label> 
    </p>
    <p>
        テンプレート名<input class="form-control" type="text" v-model="template_name" required >
    </p>
    <p>
        タイトル<input class="form-control" type="text" v-model="title" required >
    </p>
    <p>
        タイトル略称<input class="form-control" type="text" v-model="short_title" required >
    </p>
    <p>
        場所<input class="form-control" type="text" v-model="place" required >
    </p>
    <p>
        人数上限<input class="form-control" type="number" v-model="limit_number" min="1" required>
    </p>
    <p>
        詳細<textarea class="form-control" v-model="detail"></textarea>
    </p>
    <p>
        参加費<br>
        <label>社会人　<input type="text" type="number" class="form-control form-price" v-model="price1" required value="0">円</label><br>
        <label>大学・専門　<input type="text" type="number" class="form-control form-price" v-model="price2"  required value="0">円</label><br>
        <label>高校　<input type="text" type="number" class="form-control form-price" v-model="price3" required value="0">円</label>
    </p>
    <p>
        <button class="btn btn-primary" type="button" @click="register">登録</button>
        <button class="btn btn-secondary" type="button" @click="deleteById">削除</button>
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
            templateId: -1,
            selectedTemplate: '',
            templateList: [],
            msg: '',
            isnew: false,
            template_name: '',
            title: '',
            short_title: '',
            place: '',
            limit_number: 1,
            detail: '',
            price1: 0,
            price2: 0,
            price3: 0,
        },
        methods: {
            clear() {
                this.isnew = false;
                this.templateId = -1;
                this.selectedTemplate = '';
                this.template_name = '';
                this.title = '';
                this.short_title = '';
                this.place = '';
                this.limit_number = 1;
                this.detail = '';
                this.price1 = 0;
                this.price2 = 0;
                this.price3 = 0;
            },
            getTemplateList() {
                let params = new URLSearchParams();
                params.append('tableName', 'eventTemplate');
                fetch('/api/data/selectAll', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(json => {
                        this.templateList = json;
                        this.templateList.unshift({id:'', template_name: ''});
                    })
                )
                .catch(errors => console.log(errors))
            },
            selectTemplate(){
                let params = new URLSearchParams();
                params.append('tableName', 'eventTemplate');
                params.append('id', event.target.value);
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.templateId = data.id;
                        this.template_name = data.template_name;
                        this.title = data.title;
                        this.short_title = data.short_title;
                        this.place = data.place;
                        this.limit_number = data.limit_number;
                        this.detail = data.detail;
                        this.price1 = data.price1;
                        this.price2 = data.price2;
                        this.price3 = data.price3;
                    })
                )
                .catch(errors => console.log(errors))
            },
            register() {
                if(this.template_name === '') {
                    this.msg = 'テンプレート名を入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if(this.title === '') {
                    this.msg = 'タイトルを入力してください。'
                    scrollTo(0, 0)
                    return
                }
                if(this.short_title === '') {
                    this.msg = '略称を入力してください。'
                    scrollTo(0, 0)
                    return
                }

                if (!confirm('登録してよろしいですか。')) return;
                let type = 'insert';
                if(this.templateId != -1 && this.isnew === false) {
                    type = 'update'
                }
                let params = new URLSearchParams();
                params.append('tableName', 'eventTemplate');
                params.append('type', type);
                params.append('id', this.templateId);
                params.append('template_name', this.template_name);
                params.append('title', this.title);
                params.append('short_title', this.short_title);
                params.append('place', this.place);
                params.append('limit_number', this.limit_number);
                params.append('detail', this.detail);
                params.append('price1', this.price1 ?? 0);
                params.append('price2', this.price2 ?? 0);
                params.append('price3', this.price3 ?? 0);
                fetch('/api/data/updateRecord', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.getTemplateList()
                        this.clear()
                        this.msg = '登録完了しました。'
                        scrollTo(0, 0)
                    }
                })
            },
            deleteById() {
                if (!confirm('削除してよろしいですか。')) return;
                let params = new URLSearchParams();
                params.append('tableName', 'eventTemplate');
                params.append('id', this.templateId);
                fetch('/api/data/deleteById', {
                    method: 'post',
                    body: params
                })
                .then(res => {
                    if(res.status !== 200) {
                        console.log(res);
                    } else {
                        this.getTemplateList()
                        this.clear()
                        this.msg = '削除完了しました。'
                        scrollTo(0, 0)
                    }
                })
            }
        },
        created: function(){
            this.getTemplateList()
        }
    })
</script>
</body>
</html>