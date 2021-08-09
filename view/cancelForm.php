
<div id="app">

    <vue-header></vue-header>

    <form action="/participant/cancelComplete" method="post">
            <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($gameId) ?>">
            <input type="hidden" name="mode" id="user-mode" value="<?php echo $mode ?>">
            <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
            <?php if(!(isset($_SESSION['user']) && !empty($_SESSION['user']['line_id'] ?? ''))): ?>
                <p>
                    メールアドレス
                    <input class="form-control" type="email" name="email" required value="<?php echo $email ?>">
                </p>
                <div id="password-area">
                    <p>
                        パスワード
                        <input class="form-control" type="password" name="password" required>
                    </p>
                </div>
            <?php endif; ?>
            <button id="btn-cancel" class="btn btn-primary" type="submit">参加キャンセル</button>
        </form>

    <vue-footer></vue-footer>

</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const vue = new Vue({
        el:"#app",

    })
</script>

</body>
</html>