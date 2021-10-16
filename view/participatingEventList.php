<div id="app" v-cloak>
    <vue-header></vue-header>

    <h1>参加イベント一覧</h1>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#after" class="nav-link active" data-toggle="tab">予定</a>
        </li>
        <li class="nav-item">
            <a href="#before" class="nav-link" data-toggle="tab">過去</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="after" class="tab-pane active">
            <br>

            <div v-for="event in eventList" v-bind:key="event.id">
                <template v-if="event.game_date >= today">
                    <a v-bind:href="'/participant/eventInfo?gameid=' + event.id">{{ event.title }}</a> <br>
                    日付：{{ event.game_date }} <br>
                    開始時間：{{ event.start_date }} <br>
                    終了時間：{{ event.end_date }} <br>
                    場所：{{ event.place }} <br>
                    <span v-if="event.waiting_flg == 1" class="text-danger">
                    ※キャンセル待ち
                    <br>
                    </span>
                    <hr>
                </template>
            </div>

        </div>
        <div id="before" class="tab-pane">
        <br>
        
            <div v-for="event in eventList" v-bind:key="event.id">
                <template v-if="event.game_date < today">
                    <a v-bind:href="'/participant/eventInfo?gameid=' + event.id">{{ event.title }}</a> <br>
                    日付：{{ event.game_date }} <br>
                    開始時間：{{ event.start_date }} <br>
                    終了時間：{{ event.end_date }} <br>
                    場所：{{ event.place }} <br>
                    <hr>
                </template>
            </div>
        </div>
    </div>

    <vue-footer></vue-footer>

</div>

<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'

    const app = new Vue({
        el:"#app",
        data: {
            today: '',
            eventList: [],
        },
        methods: {
            getEventList() {
                fetch('/api/event/getParticipantEventList', {
                    method: 'post',
                }).then(res => res.json().then(data => this.eventList = data))
            }
        }, 
        created: function() {
            this.getEventList()
            let date = new Date()
            this.today = date.getFullYear() + '-' + ('00' + (date.getMonth()+1)).slice(-2) + '-' + ('00' + date.getDate()).slice(-2)
        }
    })

</script>
</body>
</html>