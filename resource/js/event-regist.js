Vue.component('event-regist', {
    data: function() {
        return {
            msg: '',
            selectedTemplate: '',
            templateList: [],
            title: '',
            short_title: '',
            game_date: '',
            start_time: '',
            end_time: '',
            place: '',
            limit_number: '',
            detail: '',
            price1: 0,
            price2: 0,
            price3: 0,
        }
    },
    methods: {
        clear() {
            this.selectedTemplate = ''
            this.title = ''
            this.short_title = ''
            this.game_date = ''
            this.start_time = ''
            this.end_time = ''
            this.place = ''
            this.limit_number = ''
            this.detail = ''
            this.price1 = 0
            this.price2 = 0
            this.price3 = 0
        },
        getGameInfo(id) {
            let params = new URLSearchParams();
            params.append('tableName', 'gameInfo');
            params.append('id', id);
            fetch('/api/data/selectById', {
                method: 'post',
                body: params
            })
            .then(res => res.json()
                .then(data => {
                    this.title = data.title
                    this.short_title = data.short_title
                    this.game_date = data.game_date
                    this.start_time = data.start_time
                    this.end_time = data.end_time
                    this.place = data.place
                    this.limit_number = data.limit_number
                    this.detail = data.detail
                    this.price1 = data.price1
                    this.price2 = data.price2
                    this.price3 = data.price3
                })
            )
            .catch(errors => console.log(errors))
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
            if (!confirm('登録してよろしいですか。')) return;
            let type = 'insert';
            if(this.getParam('gameid') !== null) {
                type = 'update'
            }
            let params = new URLSearchParams();
            params.append('tableName', 'gameInfo');
            params.append('type', type);
            params.append('id', this.getParam('gameid'));
            params.append('title', this.title);
            params.append('short_title', this.short_title);
            params.append('game_date', this.game_date);
            params.append('start_time', this.start_time);
            params.append('end_time', this.end_time);
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
                    this.clear()
                    this.msg = '登録完了しました。'
                }
            })
        },
        deleteGame() {
            if (!confirm('削除してよろしいですか。')) return;
            let params = new URLSearchParams();
            params.append('tableName', 'gameInfo');
            params.append('id', this.getParam('gameid'));
            fetch('/api/data/deleteById', {
                method: 'post',
                body: params
            })
            .then(res => {
                if(res.status !== 200) {
                    console.log(res);
                } else {
                    location.href = '/index'
                }
            })
        },
        getParam(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    },
    created: function(){
        this.getTemplateList()
        if(this.getParam('date') !== null) {
            this.game_date = this.getParam('date')
        }
        if(this.getParam('gameid') !== null) {
            this.getGameInfo(this.getParam('gameid'))
        }
    },
    template: 
    `
    <div>
        <br>
        <p style="color:red">{{ msg }}</p>
        <div>
            <p>
                テンプレート：
                <select @change="selectTemplate($event)" v-model="selectedTemplate">
                    <option v-for="template in templateList" v-bind:key="template.id" v-bind:value="template.id">{{ template.template_name }}</option>
                </select>
            </p>
        </div>
        <p>タイトル<input class="form-control" type="text" v-model="title" required></p>
        <p>タイトル略称<input class="form-control" type="text" v-model="short_title" required></p>
        <p>日程<input class="form-control" type="date" v-model="game_date" required></p>
        <p>開始時間<input class="form-control" type="time" step="600" v-model="start_time" required></p>
        <p>終了時間<input class="form-control" type="time" step="600" v-model="end_time" required></p>
        <p>場所<input class="form-control" type="text" v-model="place" required></p>
        <p>人数上限<input class="form-control" type="number" v-model="limit_number" min="1" required></p>
        <p>詳細<textarea class="form-control" v-model="detail"></textarea></p>
        <p>
            参加費<br>
            <label>社会人　<input type="text" type="number" class="form-control form-price" v-model="price1" required>円</label><br>
            <label>大学・専門　<input type="text" type="number" class="form-control form-price" v-model="price2" required>円</label><br>
            <label>高校　<input type="text" type="number" class="form-control form-price" v-model="price3" required>円</label>
        </p>
        <p>
            <button class="btn btn-primary" type="button" @click="register">登録</button>
            <button class="btn btn-secondary" type="button" @click="deleteGame">削除</button>
        </p>
    </div>
    `
})