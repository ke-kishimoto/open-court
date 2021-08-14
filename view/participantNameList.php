<div id="list">
    <vue-header></vue-header>
    <p>{{ msg }}</p>
    <h1>参加者リスト</h1>
    <table>
        <tr>
            <th>名前</th><th>職種</th><th>性別</th>
        </tr>
        <template v-for="participant in participantList" v-bind:key="participant.id">
            <tr>
                <th>{{ participant.name }}</th>
                <th>{{ participant.occupation_name}}</th>
                <th>{{ participant.sex_name }}</th>
            </tr>
        </template>
    </table>

    <vue-footer></vue-footer>

</div>

<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'

    const vue = new Vue({
        el:'#list',
        data: {
            msg: '',
            participantList: []
        },
        methods: {
            getParticipantNameList() {
                let params = new URLSearchParams();
                params.append('game_id', this.getParam('gameid'));
                params.append('occupation', this.getParam('occupation'));
                params.append('sex', this.getParam('sex'));
                params.append('waiting_flg', this.getParam('waiting_flg'));
                fetch('/api/event/getParticipantNameList', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => this.participantList = data))
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
            this.getParticipantNameList()
        }
    })
</script>
</body>
</html>