<div id="app" v-cloak>
    <vue-header></vue-header>

    <h1>{{ notice.title }}</h1>
    <p>
        {{ notice.date }}
    </p>
    <p>
        詳細
    </p>
    <p>
        {{ notice.content }}
    </p>

    <vue-footer></vue-footer>

</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'

    const vue = new Vue({
        el:"#app", 
        data: {
            notice: {}
        },
        methods: {
            getNotice() {
                let params = new URLSearchParams()
                params.append('tableName', 'Notice')
                params.append('id', this.getParam('id'))
                fetch('/api/data/selectById', {
                    method: 'post',
                    body: params
                }).then(res => res.json().then(data => this.notice = data))
            },
            getParam(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            }
        },
        created: function() {
            this.getNotice()
        }

    })
</script>
</body>
</html>