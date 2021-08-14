Vue.component('vue-footer', {
    data: function() {
        return {

        }
    },
    template: `
    <div>
        <hr>
        <footer class="<?php echo $_SESSION['bgColor'] ?> ">
            <a href="/help/privacyPolicy/">プライバシーポリシー</a>
            <a href="/inquiry/inquiry">お問い合わせ</a>
            <a href="/help/notice">お知らせ</a>
        </footer>
    </div>`
})