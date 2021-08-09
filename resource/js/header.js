Vue.component('vue-header', {
    data: function() {
        return {
            user:{},
            logind: false,
            admin: false,
            systemTitle: 'systemTitle',
        }
    },
    methods: {
        getLoginUser() {
            // ログインユーザーの取得
            fetch('/api/data/getLoginUser', {
                method: 'post',
            }).then(res => res.json()
                .then(data => {
                    this.user = data
                    if(this.user.id !== '') this.logind = true
                    if(this.user.admin_flg == '1') this.admin = true
                })
            )
        },
        getConfig() {
            let params = new URLSearchParams();
            params.append('tableName', 'config');
            params.append('id', 1);
            fetch('/api/data/selectById', {
                method: 'post',
                body: params
            })
            .then(res => res.json().then(data => this.systemTitle = data.system_title))
        },
    },
    created: function() {
        this.getLoginUser()
    },
    template: `
    <div>
        <header id="header" class="" role="banner">
            <!-- ハンバーガーボタン -->
            <button type="button" class="drawer-toggle drawer-hamburger">
                <span class="sr-only">toggle navigation</span>
                <span class="drawer-hamburger-icon"></span>
            </button>
            <!-- ナビゲーションの中身 -->
            <nav class="drawer-nav" role="navigation">
                <ul class="drawer-menu">
                    <li v-if="admin"><a class="drawer-brand" href="#">管理者メニュー</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/admin/config">システム設定</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/event/eventTemplate">テンプレート設定</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/admin/userList">ユーザーリスト</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/admin/inquiryList">問い合わせ管理</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/admin/notice">お知らせ登録</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/sales/index">売上管理</a></li>
                    <li v-if="admin"><a class="drawer-menu-item" href="/admin/delivery/index">セグメント配信</a></li>
                    <li><a class="drawer-brand" href="#">一般ユーザーメニュー</a></li>
                    <li v-if="logind"><a class="drawer-menu-item" href="/user/participatingEventList">参加イベント一覧</a></li>
                    <li v-if="logind"><a class="drawer-menu-item" href="/participant/eventBatchRegist">イベント一括参加</a></li>
                    <li v-if="logind"><a class="drawer-menu-item" v-bind:href="'/user/edit?id=' + user.id">アカウント情報</a></li>
                    <li v-if="logind"><a class="drawer-menu-item" href="/user/signout">ログアウト</a></li>
                </ul>
            </nav>
            <div class="system-header">
                <div class="dummy">
                    {{user.name}}
                </div>
                <div class="system-name">
                    <a class="logo" href="/">{{ systemTitle }}</a>
                </div>
                <div class="user-name">
                    <span>{{ user.name }}さん</span>
                    <span class="participant-header-menu">
                        <a class="btn btn-sm btn-outline-dark" href="/user/signup" role="button" style="margin-right:5px;">新規登録</a>
                        <a class="btn btn-sm btn-outline-dark" href="/user/signin" role="button">ログイン</a>
                        <a class="btn btn-sm btn-outline-dark" v-bind:href="'/user/edit?id=' + user.id" role="button" style="margin-right:5px;">アカウント情報</a>
                        <a class="btn btn-sm btn-outline-dark" href="/user/signout" role="button">ログアウト</a>
                    </span>
                <div>
        </header>
    </div>`
})