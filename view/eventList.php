<div id="app">
    <vue-header></vue-header>

    <table>
        <tr>
            <td colspan= 7>
                <div class="month">
                    <a href="./index?year=<?php echo htmlspecialchars($eventCalendar->lastYear); ?>&month=<?php echo htmlspecialchars($eventCalendar->lastmonth); ?>" class="lastMonthLink"><i class="fas fa-chevron-left"></i></a>
                    <a href="./index?year=<?php echo htmlspecialchars($eventCalendar->year); ?>&month=<?php echo htmlspecialchars($eventCalendar->month); ?>" class="MonthLink"><span id="year"><?php echo htmlspecialchars($eventCalendar->year); ?></span>年<span id="this-month"><?php echo htmlspecialchars($eventCalendar->month); ?></span>月</a>
                    <a href="./index?year=<?php echo htmlspecialchars($eventCalendar->nextYear); ?>&month=<?php echo htmlspecialchars($eventCalendar->nextmonth); ?>"class="nextMonthLink"><i class="fas fa-chevron-right"></i></a>
                </div>
            </td>
        </tr>
        <tr class="weekTit">
            <th class="sunday">日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th class="saturday">土</th>
        </tr>
        <tr>
        <?php foreach ($eventCalendar->calendar as $key => $value): ?>
            <td class="<?php echo $value['weekName']; ?>">
                <div class="day">
                    <?php if($value['link']): ?>    
                        <div class="day-header">   
                            <a class="link <?php echo $value['today'] ?>" href="detail_date.php?date=<?php echo $year . '/' . sprintf('%02d', $month) . '/' . sprintf('%02d', $value['day']); ?>">
                                <?php echo htmlspecialchars($value['day']); ?>
                            </a>
                        </div>
                            <?php foreach($value['info'] as $info): ?>
                                <?php
                                    if($info['mark'] === '○') {
                                        $availabilityClass = 'availability-OK';
                                    } elseif($info['mark'] === '△') {
                                        $availabilityClass = 'availability-COUTION';
                                    } elseif($info['mark'] === '✖️') {
                                        $availabilityClass = 'availability-NG';
                                    }
                                ?>
                               
                                <a class="event <?php echo $availabilityClass; ?>" href="/participant/eventInfo?gameid=<?php echo htmlspecialchars($info['id']); ?>"><?php echo $info['short_title'] ?></a>
                            <?php endforeach; ?>
                            </span>
                    <?php else: ?>
                        <div class="day-header">
                            <span class="nolink <?php echo $value['today']?>">
                                <?php echo htmlspecialchars($value['day']); ?>
                            </span>
                        </div>
                    <?php endif ?>
                </div>
            </td>
    
        <?php if ($value['weekName'] === 'saturday'): ?>
            </tr>
        <?php endif; ?>
    
        <?php endforeach; ?>
    </table>    
    空き状況
    <span class="guide availability-OK">空きあり</span>
    <span class="guide availability-COUTION">残り僅か</span>
    <span class="guide availability-NG">キャンセル待ち</span>

    <h1>イベント一覧</h1>

    <div v-for="event in eventList" v-bind:key="event.index">
        <a v-bind:href="'/participant/eventInfo?gameid=' + event.id">
            {{ event.title }} <br>
            日時：{{ event.game_date }} {{ event.start_time }} 〜 {{ event.end_time }} <br>
            参加状況：【参加予定：現在{{ event.participants_number }}名】定員：{{ event.limit_number }} 名 <br>
            空き状況：{{ event.mark }} 
            <hr>
        </a>
    </div>

    <vue-footer></vue-footer>

</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el:"#app",
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