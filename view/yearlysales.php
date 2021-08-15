<div id="app" v-cloak>
    <vue-header></vue-header>

    <table>
        <tr>
            <td>
                <div class="sales-head">
                    <p>売上集計表(年)</p>
                </div>
            </td>
            <td>
                <div class="sales-head">
                    <a href="./index">イベント別</a>
                </div>
            </td>
            <td>
                <div class="sales-head">
                    <a href="./month">月別</a>
                </div>
            </td>
        </tr>
    </table>
        <table>
            <tr>
                <th>年</th>
                <th>参加人数</th>
                <th>売上金額</th>
            </tr>
            <tr v-for="sales in salesList">
                <td>{{ sales.date }}</td>
                <td>{{ sales.cnt }}</td>
                <td>{{ sales.amount }}</td>
            </tr>

            <tr>
                <td>合計</td>
                <td>{{ totals.cnt }}</td>
                <td>{{ totals.amount }}</td>
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
            salesList: [],
            totals: [],
        },
        created: function() {
            fetch('/api/sales/getAllSales')
            .then(res => res.json().then(data => {
                this.salesList = data
                this.totals = this.salesList.reduce((sum, sales) => {
                    sum['amount'] += Number(sales.amount)
                    sum['cnt'] += Number(sales.cnt)
                    return sum
                }, {amount: 0, cnt: 0})
            }))
        }
    })
</script>
</body>
</html>