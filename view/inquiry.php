<h1>お問い合わせ</h1>
    <form id="" action="/inquiry/InquiryComplete" method="post" class="form-group">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            名前
            <input id="name" class="form-control" type="text" name="name" required maxlength="50" value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['name'] ?>">
        </p>
        <p>
            メール
            <input class="form-control" type="email" name="email" maxlength="50" value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['email'] ?>">
        </p>
        <p>
            対象イベント
            <select id="" name="game_id" class="custom-select mr-sm-2">
                <option value="0"></option>
                <?php foreach($gameInfoList as $gameInfo): ?>
                    <option value="<?php echo $gameInfo['id'] ?>"><?php echo $gameInfo['title'] ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            お問い合わせ内容
            <textarea class="form-control" name="content" rows="5" maxlength="2000"></textarea>
        </p>

        <button id="" class="btn btn-primary" type="submit">
            送信
        </button>
    </form>

