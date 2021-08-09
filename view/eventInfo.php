<div id="app">
    <vue-header></vue-header>

    <!-- <p>対象イベント：<?php echo $gameInfo['title'] ?></p> -->

            <!-- <p>{{ event.title }}</p>
            <p>日付：{{ event.game_date }}</p>
            <p>時間：{{ event.start_time }} 〜 {{ event.end_time }}</p>
            <p>場所：{{ event.place }}</p>
            <p>詳細：{{ event.detail }}</p>
            <p>参加費：<br>
                社会人：{{ event.price1 }}円 <br>
                大学・専門{{ event.price2 }}円 <br>
                高校生：{{ event.price3 }}円 <br>
            </p> -->
    
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="event-info-tab" data-toggle="tab" href="#event-info" role="tab" aria-controls="home" aria-selected="true">
                    イベント
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="contact" aria-selected="false">
                    参加者内訳
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="contact" aria-selected="false">
                    参加者一覧
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="add-tab" data-toggle="tab" href="#add" role="tab" aria-controls="contact" aria-selected="false">
                    参加者追加
                </a>
            </li>
        </ul>
    
        <div class="tab-content" id="nav-tabContent">
    
            <div class="tab-pane fade show active" id="event-info" role="tabpanel" aria-labelledby="event-info-tab">
                <event-regist />
            </div>
    
            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                <participant-breakdown />
            </div>
    
            <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                <participant-list />
            </div>

            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
                <participate />
            </div>
    
        </div>
    <vue-footer></vue-footer>
</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script src="/resource/js/event-regist.js"></script>
<script src="/resource/js/participant-breakdown.js"></script>
<script src="/resource/js/participant-list.js"></script>
<script src="/resource/js/participate.js"></script>
<script>
    const app = new Vue({
        el:"#app",
        data: {
        },
        methods: {
        },
        created: function() {
        }
    })
</script>
</body>
</html>