<div id="app-eventlist">
    <h1>イベント一覧</h1>
    <!-- <div id="event-list">
         <?php foreach ($eventCalendar->gameInfoList as $gameInfo): ?>
            <hr>
            <?php if($adminFlg === '1'): ?>
                <a href="/admin/event/eventInfo?gameid=<?php echo htmlspecialchars($gameInfo['id']); ?>">
            <?php else: ?>
                <a href="/participant/eventInfo?id=<?php echo htmlspecialchars($gameInfo['id']); ?>">
            <?php endif; ?>
                    <span class="event-end"><?php echo $gameInfo['game_date'] >= date('Y-m-d') ? '' : '※このイベントは終了しました<br>'  ?></span>
                    <?php echo htmlspecialchars($gameInfo['title']); ?>
                    <br>
                    日時：<?php echo htmlspecialchars(date('n月d日（', strtotime($gameInfo['game_date'])) . $eventCalendar->week[date('w', strtotime($gameInfo['game_date']))] . '）'); ?>  
                    <?php echo htmlspecialchars($gameInfo['start_time']); ?> ～ <?php echo htmlspecialchars($gameInfo['end_time']); ?><br>
                    場所：<?php echo htmlspecialchars($gameInfo['place']); ?><br>
                    参加状況：【参加予定：現在<?php echo htmlspecialchars($gameInfo['participants_number']); ?>名】定員：<?php echo htmlspecialchars($gameInfo['limit_number']); ?> 人<br>
                    空き状況：<?php echo htmlspecialchars($gameInfo['mark']); ?>
                </a>
        <?php endforeach; ?> 
    </div> -->

    <div v-for="event in eventList" v-bind:key="event.index">
        {{ event.title }} <br>
        日時：{{ event.game_date }} {{ event.start_time }} 〜 {{ event.end_time }} <br>
        参加状況：【参加予定：現在{{ event.participants_number }}名】定員：{{ event.limit_number }} 名 <br>
        空き状況：{{ event.mark }} 
        <hr>
    </div>


</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el:"#app-eventlist",
        data: {
            year: '',
            month: '',
            eventList: [],
        },
        methods: {
            getEventList() {
                let params = new URLSearchParams();
                params.append('year', this.year);
                params.append('month', this.month);
                fetch('/api/event/getEventListAtMonth', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.eventList = data;
                    })
                )
                .catch(errors => console.log(errors))
            }
        },
        created: function(){
            let date = new Date()
            this.year = date.getFullYear()
            this.month = date.getMonth() + 1
            this.getEventList()
        }

    })

</script>
</body>
</html>