<div id="app">

    <h1>問い合わせ一覧</h1>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#incomplete" class="nav-link active" data-toggle="tab">未対応</a>
        </li>
        <li class="nav-item">
            <a href="#complete" class="nav-link" data-toggle="tab">対応済</a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="incomplete" class="tab-pane active">
            <br>
    
            <div v-for="inquiry in inquiryList" v-bind:key="inquiry.id">
                <template v-if="inquiry.status_flg === '0'">
                    名前：{{ inquiry.name }} <br>
                    対象イベント：{{ inquiry.title }} <br>
                    連絡先：{{ inquiry.email }} <br>
                    問い合わせ内容：{{ inquiry.content }} <br>
                    <button class="btn btn-primary btn-inquiry-status" @click="changeFlg(inquiry)">対応済みにする</button><br>
                    <hr>
                </template>
            </div>
    
        </div>
        <div id="complete" class="tab-pane">
            <br>
            <div v-for="inquiry in inquiryList" v-bind:key="inquiry.id">
                <template v-if="inquiry.status_flg !== '0'">
                    名前：{{ inquiry.name }} <br>
                    対象イベント：{{ inquiry.title }} <br>
                    連絡先：{{ inquiry.email }} <br>
                    問い合わせ内容：{{ inquiry.content }} <br>
                    <button class="btn btn-secondary btn-inquiry-status" @click="changeFlg(inquiry)">未対応にする</button><br>
                    <hr>
                </template>
            </div>
        </div>
    </div>
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script>
    const app = new Vue({
        el:"#app",
        data: {
            inquiryList: []
        },
        methods: {
            getInquiryList() {
                let params = new URLSearchParams();
                params.append('tableName', 'inquiry');
                fetch('/api/data/selectAll', {
                    method: 'post',
                    body: params
                })
                .then(res => res.json()
                    .then(data => {
                        this.inquiryList = data;
                    })
                )
                .catch(errors => console.log(errors))
            },
            changeFlg(data) {
                let params = new URLSearchParams();
                params.append('tableName', 'inquiry');
                params.append('id', data.id);
                fetch('/api/data/updateFlg', {
                    method: 'post',
                    body: params
                })
                .then(() => this.getInquiryList())
                .catch(errors => console.log(errors))
            }
        },
        created: function() {
            this.getInquiryList()
        }

    })
</script>
</body>
</html>