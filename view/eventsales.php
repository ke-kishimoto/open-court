<div id="app">

    <vue-header></vue-header>

    <p style="color:red; font-size:20px">{{ msg }}</p>

    <table>
        <tr>
            <td>
                <p class="sales-event-title">{{ title }}</p>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <th>名前</th>
            <th>出欠</th>
            <th>回収金額</th>
            <th>備考</th>
        </tr>

        <tr v-for="(participant, index) in participantList" v-bind:key="index">
            <th>{{ participant.name }}</th>
            <th>
                <select class="custom-select mr-sm-2" v-model="participant.attendance">
                    <option v-for="item in attendanceOptions" v-bind:value="item.value">{{ item.text }}</option>
                </select>
            </th>
            <th><input type="number" v-model="participant.amount"></th>
            <th><input type="text" v-model="participant.amount_remark"></th>
        </tr>
    </table>
    <p>
        <button type="button" class="btn btn-primary" @click="updateAmount">更新</button>
    </p>
    
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
            msg: '',
            participantList: [],
            title: '',
            attendanceOptions: [
                {text: '出席', value: '1'},
                {text: '欠席', value: '2'},
            ],

        },
        methods: {
            getParticipantList() {
                let params = new URLSearchParams()
                params.append('gameid', this.getParam('gameid'))
                fetch('/api/sales/getParticipantList', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => {
                    this.participantList = data
                    if(this.participantList.length > 0) {
                        this.title = this.participantList[0].title
                    }
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
            updateAmount() {
                if (!confirm('更新してよろしいですか。')) return;

                fetch('/api/sales/updateParticipantAmount', {
                    headers:{
                        'Content-Type': 'application/json',
                    },
                    method: 'post',
                    body: JSON.stringify(this.participantList)
                })
                .then(() => {
                    this.msg = '更新完了しました。'
                    this.getParticipantList()
                })
                .catch(errors => console.log(errors))
                            }
        },
        created: function(){
            this.getParticipantList()
        }
    })
</script>
</body>
</html>