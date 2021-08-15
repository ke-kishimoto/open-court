<div id="app" v-cloak>

<vue-header></vue-header>

    <table>
        <tr>
            <td colspan=2>
                <div class="sales-head">
                    <a href="#" class="lastMonthLink" @click="lastYear"><i class="fas fa-chevron-left"></i></a>
                    <a href="#" class="MonthLink"><span id="year">{{ year }}</span>年</a>
                    <a href="#" class="nextMonthLink" @click="nextYear"><i class="fas fa-chevron-right"></i></a>
                </div>
            </td>
            <td>
                <div class="sales-head">
                    <a href="./index">イベント別</a>
                </div>
            </td>
            <td>
                <div class="sales-head">
                    <a href="./year">年別</a>
                </div>
            </td>
        </tr>
    </table>
        <table>
            <tr>
                <th>月</th>
                <th>参加人数</th>
                <th>売上金額</th>
            </tr>
            <tr v-for="sales in salesList">
                <th>{{ sales.month }}</th>
                <th>{{ sales.cnt }}</th>
                <th>{{ sales.amount }}</th>
            </tr>
            <tr>
                <th colspan="1">合計</th>
                <th>{{ totals.cnt }}</th>
                <th>{{ totals.amount }}</th>
            </tr>
        </table>

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
            salesList: [],
            totals: [],
        },
        methods: {
            getSalesList() {
                let params = new URLSearchParams()
                params.append('year', this.year)
                fetch('/api/sales/getYearSales', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => {
                    this.salesList = data
                    this.totals = this.salesList.reduce((sum, sales) => {
                        sum['amount'] += Number(sales.amount)
                        sum['cnt'] += Number(sales.cnt)
                        return sum
                    }, {amount: 0, cnt: 0})
                }))
            },
            lastYear() {
                this.year = this.month === 0 ? this.year - 1 : this.year
                this.getSalesList()
            },
            nextYear() {
                this.year = this.month === 11 ? this.year + 1 : this.year
                this.getSalesList()
            },
        },
        created: function() {
            let date = new Date()
            this.year = date.getFullYear()
            this.getSalesList()
        }
    })
</script>
</body>
</html>