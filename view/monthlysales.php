<div id="app" v-cloak>

<vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>

    <table>
        <tr>
            <td colspan="4">
                <div class="sales-head">
                    <a href="#" class="lastMonthLink" @click="lastMonth"><i class="fas fa-chevron-left"></i></a>
                    <a href="#" class="MonthLink"><span id="year">{{ year }}</span>年<span id="this-month">{{ month + 1 }}</span>月</a>
                    <a href="#" class="nextMonthLink" @click="nextMonth"><i class="fas fa-chevron-right"></i></a>
                </div>
            </td>
            <td>
                <div class="sales-head">
                    <a href="./month" class="sales-link">月別</a>
                </div>
            </td>
            <td>
                <div class="sales-head">
                    <a href="./year" class="sales-link">年別</a>
                </div>
            </td>
        </tr>
    </table>
        <table>
            <tr>
                <th>日付</th>
                <th>イベント名</th>
                <th>参加人数</th>
                <th>売上金額</th>
                <th>経費</th>
                <th>粗利</th>
            </tr>

            <tr v-for="event in eventList">
                <th>{{ event.date }}</th>
                <th><a v-bind:href="'./detail?gameid=' + event.game_id">{{ event.title }}</a></th>
                <th><input type="number" v-model="event.cnt" class="form-control"></th>
                <th><input type="number" v-model="event.amount" class="form-control"></th>
                <th><input type="number" v-model="event.expenses" class="form-control"></th>
                <th>{{ event.amount - event.expenses }}</th>
            </tr>
            <tr>
                <th colspan="2">合計</th>
                <th>{{ totals.total_cnt }}</th>
                <th>{{ totals.total_amount }}</th>
                <th>{{ totals.total_expenses }}</th>
                <th>{{ totals.total_amount - totals.total_expenses}}</th>
            </tr>

        </table>
        <p>
            <button type="button" class="btn btn-primary" @click="updateExpenses">更新</button>
        </p>

        <vue-footer></vue-footer>

</div>

<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el:"#app",
        data: {
            msg: '',
            year: '',
            month: '',
            eventList: [],
            totals: [],
        },
        methods: {
            getMonthSales() {
                let params = new URLSearchParams()
                params.append('year', this.year)
                params.append('month', this.month + 1)
                fetch('/api/sales/getMonthSales', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => {
                    this.eventList = data
                    this.totals = this.eventList.reduce((sum, event) => {
                         sum['total_cnt'] += Number(event.cnt)
                         sum['total_amount'] += Number(event.amount)
                         sum['total_expenses'] += Number(event.expenses)
                         return sum
                    }, {total_cnt: 0, total_amount: 0, total_expenses:0})
                }))
            },
            lastMonth() {
                this.year = this.month === 0 ? this.year - 1 : this.year;
                this.month = this.month === 0 ? 11 : this.month - 1;
                this.getMonthSales();
            },
            nextMonth() {
                this.year = this.month === 11 ? this.year + 1 : this.year;
                this.month = this.month === 11 ? 0 : this.month + 1;
                this.getMonthSales();
            },
            updateExpenses() {
                if (!confirm('更新してよろしいですか。')) return;

                fetch('/api/sales/updateExpenses', {
                    headers:{
                        'Content-Type': 'application/json',
                    },
                    method: 'post',
                    body: JSON.stringify(this.eventList)
                })
                .then(() => {
                    this.msg = '更新完了しました。'
                    this.getMonthSales()
                })
                .catch(errors => console.log(errors))
            }
            
        },
        created: function() {
            let date = new Date()
            this.year = date.getFullYear()
            this.month = date.getMonth()
            this.getMonthSales()
        }
    })
</script>
</body>
</html>