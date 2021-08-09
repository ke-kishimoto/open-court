<div id="app">
    <h1>お知らせ一覧</h1>
    <div id="notivce-list">
        <!-- <?php foreach ($noticeList as $notice): ?>
            <p>
                <?php echo $notice['date']; ?>&nbsp;&nbsp;&nbsp;
                <a href="/notice/detail?id=<?php echo $notice['id'] ?>">
                    <?php echo $notice['title'] ?>
                </a>
            </p>
        <?php endforeach; ?> -->
        <template v-for="notice in noticeList" v-bind:key="notice.id">
            <p>
            {{ notice.date }} &nbsp;&nbsp;&nbsp;
            <a v-bind:href="'/notice/detail?id=' + notice.id">{{notice.title}}</a>
        </p>
        </template>
    </div>
    <vue-footer />
</div>

<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'

    const vue = new Vue({
        el:"#app",
        data: {
            noticeList: []
        },
        methods: {
            getNoticeList() {
                let params = new URLSearchParams()
                params.append('tableName', 'Notice')
                fetch('/api/data/selectAll', {
                    method: 'post',
                    body: params,
                }).then(res => res.json().then(data => this.noticeList = data))
            }
        }, created: function() {
            this.getNoticeList()
        }
    })
</script>
</body>
</html>