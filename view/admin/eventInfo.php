<div id="app">

    <p style="color:red">{{ msg }}</p>
    <p>対象イベント：<?php echo $gameInfo['title'] ?></p>
    
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="event-info-tab" data-toggle="tab" href="#event-info" role="tab" aria-controls="home" aria-selected="true">イベント</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="contact" aria-selected="false">現在の状況</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="contact" aria-selected="false">参加者</a>
            </li>
        </ul>
    
        <div class="tab-content" id="nav-tabContent">
    
            <div class="tab-pane fade show active" id="event-info" role="tabpanel" aria-labelledby="event-info-tab">
    
                <br>
                <input type="hidden" id="game_id" name="game_id" value="<?php echo $gameInfo['id']; ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="<?php echo $templateAreaClass ?>">
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
    
    
            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
    
    
                <div class="<?php echo $participantDisp ?>">
                    <div>
                        <br>
                        <p>【参加予定 <span id="cnt"><?php echo $detail['cnt'] ?></span>人】【上限 <?php echo $gameInfo['limit_number'] ?>人】</p>
    
                        <table>
                            <tr>
                                <th>職種</th>
                                <th>男性</th>
                                <th>女性</th>
                                <th>全体</th>
                            </tr>
                            <tr>
                                <th>社会人</th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=1&waiting_flg=0">
                                        <span id="sya_men"><?php echo $detail['sya_men'] ?></span>人
                                    </a>
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=2&waiting_flg=0">
                                        <span id="sya_women"><?php echo $detail['sya_women'] ?></span>人
                                    </a>
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=0&waiting_flg=0">
                                        <span id="sya_all"><?php echo $detail['sya_all'] ?></span>人
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <th>大学・専門</th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=1&waiting_flg=0">
                                        <span id="dai_men"><?php echo $detail['dai_men'] ?></span>人
                                    </a>
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=2&waiting_flg=0">
                                        <span id="dai_women"><?php echo $detail['dai_women'] ?></span>人
                                    </a>
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=0&waiting_flg=0">
                                        <span id="dai_all"><?php echo $detail['dai_all'] ?></span>人
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <th>高校</th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=1&waiting_flg=0">
                                        <span id="kou_men"><?php echo $detail['kou_men'] ?></span>人
                                    </a>
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=2&waiting_flg=0">
                                        <span id="kou_women"><?php echo $detail['kou_women'] ?></span>人
                                    </a>
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=0&waiting_flg=0">
                                        <span id="kou_all"><?php echo $detail['kou_all'] ?></span>人
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <th>キャンセル待ち</th>
                                <th>
                                    -
                                </th>
                                <th>
                                    -
                                </th>
                                <th>
                                    <a href="/admin/participant/ParticipantNameList?gameid=<?php echo $gameInfo['id'] ?>&occupation=0&sex=0&waiting_flg=1">
                                        <span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人
                                    </a>
                                </th>
                            </tr>
                        </table>
    
                        <!-- <p>社会人：
                        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=2&waiting_flg=0">女性 <span id="sya_women"><?php echo $detail['sya_women'] ?></span>人</a>、
                        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=1&waiting_flg=0">男性 <span id="sya_men"><?php echo $detail['sya_men'] ?></span>人</a>
                    <p>大学・専門：
                        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=2&waiting_flg=0">女性 <span id="dai_women"><?php echo $detail['dai_women'] ?></span>人</a>、
                        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=1&waiting_flg=0">男性 <span id="dai_men"><?php echo $detail['dai_men'] ?></span>人</a>
                    </p>
                    <p>高校生：
                        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=2&waiting_flg=0">女性 <span id="kou_women"><?php echo $detail['kou_women'] ?></span>人</a>、
                        <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=1&waiting_flg=0">男性 <span id="kou_men"><?php echo $detail['kou_men'] ?></span>人</a>
                    </p>
                    <p><a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=0&sex=0&waiting_flg=1">キャンセル待ち：<span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人</a></p> -->
                    </div>
                </div>
            </div>
    
            <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                <div class="<?php echo $participantDisp ?>">
                    <br>
                    <a class="btn btn-primary" href="/admin/participant/participantInfo?game_id=<?php echo $gameInfo['id']; ?>">参加者追加</a>
                    <a class="btn btn-info" href="<?php echo $mailto ?>">参加者全員に連絡</a>
                    <?php foreach ((array)$participantList as $participant) : ?>
                        <?php if ($participant['main'] == '1') : ?>
                            <hr>
                            <div id="participant-<?php echo $participant['id'] ?>">
                                <p>
                                    <a class="btn btn-secondary" href="/admin/participant/ParticipantInfo?id=<?php echo $participant['id']; ?>&game_id=<?php echo $gameInfo['id']; ?>">修正</a>
                                    <button type="button" class="waiting btn btn-<?php echo $participant['waiting_flg'] == '1' ? 'warning' : 'success' ?>" value="<?php echo $participant['id'] ?>">
                                        <?php echo $participant['waiting_flg'] == '1' ? 'キャンセル待ちを解除' : 'キャンセル待ちに変更' ?></button>
                                    <span class="duplication"><?php echo $participant['chk'] ?></span>
                                    <button type="button" class="btn btn-danger btn-participant-delete" value="<?php echo $participant['id'] ?>">削除</button>
                                </p>
                            <?php endif ?>
    
                            <p>
                                <?php /* echo htmlspecialchars($participant['waiting_name']); */ ?>
                                <?php echo htmlspecialchars($participant['companion_name']); ?> &nbsp;&nbsp;
                                <?php echo htmlspecialchars($participant['name']); ?> &nbsp;&nbsp;
                                <?php echo htmlspecialchars($participant['occupation_name']); ?> &nbsp;&nbsp;
                                <?php echo htmlspecialchars($participant['sex_name']); ?> &nbsp;&nbsp;
                            </p>
                            <?php if ($participant['main'] == '1') : ?>
                                <p>
                                    連絡先：
                                    <a href="mailto:<?php echo htmlspecialchars($participant['email']); ?>"><?php echo htmlspecialchars($participant['email']); ?></a>
                                </p>
                                <p>
                                    備考：<?php echo htmlspecialchars($participant['remark']); ?>
                                </p>
                            </div>
                        <?php endif ?>
                    <?php endforeach; ?>
                </div>
            </div>
    
        </div>
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script>
    const app = new Vue({
        el:"#app",
        data: {
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
                        location.href = '/admin/admin/index'
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
        }
    })
</script>
</body>
</html>