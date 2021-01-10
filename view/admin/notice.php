<h1>お知らせ登録</h1>
    <form id="" action="/admin/notice/regist" method="post" class="form-group">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            <select name="id" id="notice">
            <option value=""></option>
            <?php foreach ($noticeList as $notice): ?>
                <option value="<?php echo $notice['id'] ?>"><?php echo $notice['title'] ?></option>
            <?php endforeach ?>
            </select>
            <input type="checkbox" id="new" name="new" value="new">コピーして新規作成
        </p>
        <p>
            お知らせタイトル
            <input class="form-control" type="text" name="title" id="title" maxlength="30" value="">
        </p>
        <p>
            お知らせ内容
            <textarea class="form-control" name="content" id="content" rows="5" maxlength="2000"></textarea>
        </p>

        <button id="" class="btn btn-primary" type="submit" name="register">
            登録
        </button>

        <button id="" class="btn btn-danger" type="submit" name="delete">
            削除
        </button>
    </form>

