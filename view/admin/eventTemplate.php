    <p>テンプレート</p>
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
            <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
        </p>
    </form>

    <a href="index.php">イベント一覧ページに戻る</a>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        'use strict';
        $(function(){ 
            $('#btn-delete').on('click', function() {
                return confirm('削除してもよろしいですか');
            });
            $('#template').change(function() {
                $.ajax({
                url:'../../controller/GetEventTemplate.php',
                type:'POST',
                data:{
                    'id':$('#template').val()
                }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    console.log(data);
                    $('#template_name').val(data.template_name);
                    $('#title').val(data.title);
                    $('#short_title').val(data.short_title);
                    $('#place').val(data.place);
                    $('#limit_number').val(data.limit_number);
                    $('#detail').val(data.detail);
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {
                })
            })
        });
    
    </script>
</body>
</html>