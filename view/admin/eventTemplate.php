    <h1>テンプレート設定</h1>
    <form action="EventTempleteComplete.php" method="post" class="form-group">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            <select name="id" id="template">
            <option value=""></option>
            <?php foreach ($eventTemplateList as $eventTemplate): ?>
                <option value="<?php echo $eventTemplate['id'] ?>"><?php echo $eventTemplate['template_name'] ?></option>
            <?php endforeach ?>
            </select>
            <input type="checkbox" id="new" name="new" value="new">コピーして新規作成
        </p>
        <p>
            テンプレート名<input class="form-control" type="text" id="template_name" name="template_name" required >
        </p>
        <p>
            タイトル<input class="form-control" type="text" id="title" name="title" required >
        </p>
        <p>
            タイトル略称<input class="form-control" type="text" id="short_title" name="short_title" required >
        </p>
        <p>
            場所<input class="form-control" type="text" id="place" name="place" required >
        </p>
        <p>
            人数上限<input class="form-control" type="number" id="limit_number" name="limit_number" min="1" required>
        </p>
        <p>
            詳細<textarea class="form-control" id="detail" name="detail"></textarea>
        </p>
        <p>
            <button class="btn btn-primary" type="submit" name="register">登録</button>
            <button id="btn-event-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
        </p>
    </form>