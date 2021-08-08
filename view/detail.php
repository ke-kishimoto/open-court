<div id="app">

<p v-if="today > event.game_date" style="color: red;">※終了したイベントのため応募できません</p>
    
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

            <p>{{ event.title }}</p>
            <p>日付：{{ event.game_date }}</p>
            <p>時間：{{ event.start_time }} 〜 {{ event.end_time }}</p>
            <p>場所：{{ event.place }}</p>
            <p>詳細：{{ event.detail }}</p>
            <p>参加費：<br>
                社会人：{{ event.price1 }}円 <br>
                大学・専門{{ event.price2 }}円 <br>
                高校生：{{ event.price3 }}円 <br>
            </p>
    
            <div v-if="today <= event.game_date">
                <p>【応募フォーム】
                    <span v-if="registered" class="text-danger">
                    <!-- <?php echo $Registered ? '※参加登録済みです' : '' ?> -->
                    ※参加登録済みです
                    </span>
                </p>
                
                <participate/>

                <!-- <input type="hidden" name="title" value="<?php echo htmlspecialchars($gameInfo['title']) ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($gameInfo['game_date']) ?>">
                <input type="hidden" name="participantId" value="<?php echo htmlspecialchars($participantId) ?>">
                <button id="btn-partisipant-regist" name="<?php echo $Registered ? 'update' : 'insert' ?>" class="btn btn-primary" type="submit" value="regist"><?php echo $Registered ? '修正' : '登録' ?></button>
                <a class="btn btn-danger <?php echo $Registered ? '' : 'hidden' ?>" href="/participant/cancel?gameid=<?php echo htmlspecialchars($gameInfo['id']) ?>">参加のキャンセル</a> -->
            </div>
        </div>
        
        <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
            <participant-breakdown/>
        </div>
    
        <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
            <participant-list/>
        </div>
    </div>
</div>

<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/participate.js"></script>
<script src="/resource/js/participant-breakdown.js"></script>
<script src="/resource/js/participant-list.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",
        data: {
            today: '',
            registered: false,
            user: {},
            event: {},
        },
        methods: {
            getEventInfo() {
                let params = new URLSearchParams();
                params.append('tableName', 'gameInfo');
                params.append('id', this.getParam('gameid'));
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.event = data;
                    })
                )
                .catch(errors => console.log(errors))
            },
            getLoginUser() {
                fetch('/api/data/getLoginUser', {
                    method: 'post',
                }).then(res => res.json()
                    .then(data => {
                        this.user = data;
                        if (this.user.id == '') return 
                        let params = new URLSearchParams();
                        params.append('tableName', 'Participant');
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
                                    console.log(data)
                                    this.registered = data.result
                                })
                            }
                        )

                }))
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
            this.getEventInfo()
            let date = new Date()
            this.today = date.getFullYear() + '-' + ('00' + (date.getMonth()+1)).slice(-2) + '-' + ('00' + date.getDate()).slice(-2)
        }
    })
</script>
</body>
</html>