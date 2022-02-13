Vue.component('participant-list', {
    data: function() {
        return {
            id: -1,
            participantList: [],
            user: {},
            admin: false,
        }
    },
    methods: {
        getLoginUser() {
            fetch('/api/data/getLoginUser', {
                method: 'post',
            }).then(res => res.json()
                .then(data => {
                    this.user = data;
                    if(this.user.admin_flg == '1') {
                        this.admin = true
                    }
                })
            )
        },
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
        this.getLoginUser()
        this.getParticipantList()
    },
    template: `
    <div>
        <br>
        <div v-for="(participant, index) in participantList" v-bind:key="index">
        <hr v-if="participant.main == 1">
            <p v-if="participant.main == 1 && user.admin_flg == '1'">
                <a class="btn btn-secondary" v-bind:href="'/admin/ParticipantInfo?editId=' + participant.id + '&game_id=' + id ">修正</a>
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
            <p v-if="participant.main == 1 && user.admin_flg == '1'">
                連絡先：<a v-bind:href="'mailto:' + participant.email">{{ participant.email }}</a>
                {{ participant.remark }}
            </p>
        </div>
    </div>
    `
})