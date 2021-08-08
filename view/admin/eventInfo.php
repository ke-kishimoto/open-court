<div id="app">

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
    
                <event-regist />

            </div>
    
            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
    
                <participant-breakdown />
                    
            </div>
    
            <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                <div class="<?php echo $participantDisp ?>">
                    <br>
                    <a class="btn btn-primary" href="/admin/participant/participantInfo?game_id=<?php echo $gameInfo['id']; ?>">参加者追加</a>
                    <a class="btn btn-info" href="<?php echo $mailto ?>">参加者全員に連絡</a>

                    <div v-for="participant in participantList" v-bind:key="participant.id">
                        <p v-if="participant.main == 1">
                            <a class="btn btn-secondary" href="'/admin/participant/ParticipantInfo?id=' + participant.id + '&game_id=' + id ">修正</a>
                            <button type="button" v-bind:class="'waiting btn btn-' + (participant.waiting_flg == '1' ? 'warning' : 'success')" @click="changeWaitingFlg(participant)">
                                <template v-if="participant.waiting_flg == 1">
                                    キャンセル待ちを解除
                                </template>
                                <template v-else>
                                    キャンセル待ちに変更
                                </template>
                            </button>
                            <button type="button" class="btn btn-danger" @click="deleteParticipant(participant)">削除</button>
                        </p>
                        <p>
                            {{ participant.companion_name }} &nbsp;&nbsp;
                            {{ participant.name }} &nbsp;&nbsp;
                            {{ participant.occupation_name }} &nbsp;&nbsp;
                            {{ participant.sex_name }} &nbsp;&nbsp;
                        </p>
                        <p v-if="participant.main == 1">
                            <p>
                                連絡先：
                                <a v-bind:href="'mailto:' + participant.email">{{ participant.email }}</a>
                            </p>
                            <p>
                                {{ participant.remark }}
                            </p>
                        </p>
                        <hr>
                    </div>

                </div>
            </div>
    
        </div>
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/event-regist.js"></script>
<script src="/resource/js/participant-breakdown.js"></script>
<script>
    const app = new Vue({
        el:"#app",
        data: {
            id: -1,
            participantList: [],
        },
        methods: {
            getParticipantList() {
                if(this.getParam('gameid') !== null) {
                    this.id = this.getParam('gameid') 
                    let params = new URLSearchParams();
                    params.append('game_id', this.id);
                    fetch('/api/event/getParticipantList', {
                        method: 'post',
                        body: params
                    }).then(res => res.json()
                        .then(data => this.participantList = data))
                }
            },
            changeWaitingFlg(participant) {
                let params = new URLSearchParams();
                params.append('id', participant.id);
                params.append('game_id', this.id);
                fetch('/api/event/updateWaitingFlg', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => participant.waiting_flg = data.waiting_flg))
            },
            deleteParticipant(participant) {
                if (!confirm('削除してよろしいですか。')) return
                let params = new URLSearchParams();
                params.append('participant_id', participant.id);
                params.append('game_id', this.id);
                fetch('/api/event/deleteParticipant', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data = this.participantList = data))
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
            this.getParticipantList()
        }
    })
</script>
</body>
</html>