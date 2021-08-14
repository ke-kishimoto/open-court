<div id="app">
    <vue-header></vue-header>

    <table>
        <tr>
            <td colspan= 7>
                <div class="month">
                    <a href="#" class="lastMonthLink" @click="lastMonth"><i class="fas fa-chevron-left"></i></a>
                    <a href="#" class="MonthLink"><span id="year">{{ year }}</span>年<span id="this-month">{{ month + 1 }}</span>月</a>
                    <a href="#"class="nextMonthLink" @click="nextMonth"><i class="fas fa-chevron-right"></i></a>
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
        
        <tr v-for="weekDay in days">
            <td v-for="day in weekDay">
                <div class="day" @click="newEvent(day)">
                    <div class="day-header">
                        <span class="nolink">{{ day }}</span>
                    </div>
                    <template v-if="day !== ''">
                        <template v-for="e in calData[day]">
                            <a v-bind:href="'/participant/eventInfo?gameid=' + e.id" v-bind:class="'event ' + e.class_name + ' event-apply-' + e.apply ">{{ e.short_title }}</a>
                        </template> 
                    </template>
                </div>
            </td>
        </tr>
        
    </table>
    参加状況
    <span class="guide event-apply-Yes">参加登録済み</span>
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
            user: {},
            admin: false,
            year: '',
            month: '',
            date: '',
            lastDate: -1,
            days: [],
            calData: [], // 日付ごとのイベントを格納する２次元配列
            eventList: [],
        },
        methods: {
            getEventList() {
                let params = new URLSearchParams();
                params.append('year', this.year);
                params.append('month', this.month + 1);
                if(this.user.email !== null && this.user.email !== '') {
                    params.append('email', this.user.email);
                }
                if(this.user.line_id !== null && this.user.line_id !== '') {
                    params.append('line_id', this.user.line_id);
                }
                fetch('/api/event/getEventListAtMonth', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.eventList = data;
                        this.createCalendar(this.year, this.month);
                        this.createCalData();
                    })
                )
                .catch(errors => console.log(errors))
            },
            createCalendar(year, month) {
                const start = new Date(year, month, 1);     // 月初
                const last = new Date(year, month + 1, 0);  // 月末
                const startDate = start.getDate();          // 月初
                const lastDate = last.getDate();            // 月末
                const startDay = start.getDay();            // 月初の曜日
                const lastDay = last.getDay();
                
                this.lastDate = lastDate;
                this.days = [];
                let weekDay = [];
                let dayCount = 0; // 曜日カウント用

                for (let i = startDate; i <= lastDate; i++) {
                    if (i === startDate) {
                        for (let j = 0; j < startDay; j++) {
                            weekDay.push('');
                            dayCount++;
                        }
                    }
                    weekDay.push(i);
                    dayCount++;
                    if (dayCount === 7) {
                        this.days.push(weekDay);
                        dayCount = 0;
                        weekDay = [];
                    }
                }
                for (let i = lastDay; i < 6; i++) {
                    weekDay.push('');
                }
                this.days.push(weekDay);
            },
            createCalData() {
                let index = 0;
                this.calData = {};
                for(let i=1; i<=this.lastDate; i++) {
                    this.calData[i] = [];
                    for(let j=index; j<=this.eventList.length; j++) {
                        if(this.eventList[index] === undefined) {
                            break;
                        } else if(i < Number(this.eventList[index].day)) {
                            break;
                        } else if (i === Number(this.eventList[index].day)) {
                            this.calData[i].push(this.eventList[index]);
                            index++;
                        } else {
                            index++;
                        }
                    }
                }
            },
            lastMonth() {
                this.year = this.month === 0 ? this.year - 1 : this.year;
                this.month = this.month === 0 ? 11 : this.month - 1;
                this.getEventList();
            },
            nextMonth() {
                this.year = this.month === 11 ? this.year + 1 : this.year;
                this.month = this.month === 11 ? 0 : this.month + 1;
                this.getEventList();
            },
            newEvent(day) {
                if(!this.admin) return
                location.href = "/participant/eventInfo?date=" + this.year + '-' + ('00' + (this.month+1)).slice(-2) + '-' + ('00' + day).slice(-2) + "&mode=new"
            },
            getLoginUser() {
                fetch('/api/data/getLoginUser', {
                    method: 'post',
                }).then(res => res.json()
                    .then(data => {
                        this.user = data
                        this.getEventList()
                        if (this.user.id == '') return 
                        if(this.user.admin_flg == '1') {
                            this.admin = true
                        }
                }))
            },
        },

        created: function(){
            let date = new Date()
            this.year = date.getFullYear()
            this.month = date.getMonth()
            this.getLoginUser()
        }

    })

</script>
</body>
</html>