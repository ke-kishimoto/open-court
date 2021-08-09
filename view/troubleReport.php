<div id="app">
    
    <vue-header></vue-header>

    <h1>改善目安箱</h1>
    <p>システムに関する不具合・及び要望がありましたら、こちらからご報告ください。</p>
        <form id="" action="/troubleReport/complete" method="post" class="form-group">
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
            <p>
                名前
                <input id="name" class="form-control" type="text" name="name" required maxlength="50" value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['name'] ?>">
            </p>
            <p>
                カテゴリ
                <select id="" name="category" class="custom-select mr-sm-2">
                    <option value="1">障害・不具合</option>
                    <option value="2">要望</option>
                    <option value="3">その他</option>
                </select>
            </p>
            <p>
                タイトル
                <input id="title" class="form-control" type="text" name="title" required maxlength="30" value="">
            </p>
            <p>
                詳細
                <textarea class="form-control" name="content" rows="5" maxlength="2000"></textarea>
            </p>
    
            <button id="" class="btn btn-primary" type="submit">
                送信
            </button>
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