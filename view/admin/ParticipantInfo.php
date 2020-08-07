<p>参加者登録</p>
<form action="ParticipantComplete.php" method="post" class="form-group">
    <input type="hidden" id="id" name="id" value="<?php echo $participant['id'] ?>">
    <input type="hidden" name="game_id" value="<?php echo $_GET['game_id'] ?>">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p class="<?php echo $userListClass ?>">
        <select name="id" id="user">
        <option value=""></option>
        <?php foreach ($userList as $user): ?>
            <option value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
        <?php endforeach ?>
        </select>
    </p>
    <p>
    職種
    <select id="occupation" name="occupation" class="custom-select mr-sm-2">
        <option value="1" <?php echo $participant['occupation'] == '1' ? 'selected' : '' ?> >社会人</option>
        <option value="2" <?php echo $participant['occupation'] == '2' ? 'selected' : '' ?> >大学・専門学校</option>
        <option value="3" <?php echo $participant['occupation'] == '3' ? 'selected' : '' ?> >高校</option>
      </select>
    </p>
    <p>
    性別
    <select id="sex" name="sex" class="custom-select mr-sm-2">
        <option value="1" <?php echo $participant['sex'] == '1' ? 'selected' : '' ?> >男性</option>
        <option value="2" <?php echo $participant['sex'] == '2' ? 'selected' : '' ?> >女性</option>
    </select>
    </p>
    <p>
        名前
        <input id="name" class="form-control" type="text" name="name" value="<?php echo $participant['name'] ?>" required>
    </p>
    <p>
        メール
        <input id="email" class="form-control" type="email" name="email" value="<?php echo $participant['email'] ?>">
    </p>
    <p>
        備考
        <textarea id="remark" class="form-control" name="remark"><?php echo $participant['remark'] ?></textarea>
    <div id="douhan-0">
        </p>
            <input id="companion" name="companion" type="hidden" value="<?php echo count((array)$companionList) ?>">
            <button class="btn btn-secondary" id="btn-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-del" type="button">同伴者削除</button>
        </p>
    </div>
    <?php for($i = 0;$i < count($companionList); $i++): ?>
        <div id="douhan-<?php echo $i+1 ?>"> 
            <select id="occupation-<?php echo $i+1 ?>" name="occupation-<?php echo $i+1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companionList[$i]['occupation'] == '1' ? 'selected' : '' ?> >社会人</option>
                <option value="2" <?php echo $companionList[$i]['occupation'] == '2' ? 'selected' : '' ?> >大学・専門学校</option>
                <option value="3" <?php echo $companionList[$i]['occupation'] == '3' ? 'selected' : '' ?> >高校</option>
            </select>
        
            <select id="sex-<?php echo $i ?>" name="sex-<?php echo $i+1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companionList[$i]['sex'] == '1' ? 'selected' : '' ?> >男性</option>
                <option value="2" <?php echo $companionList[$i]['sex'] == '2' ? 'selected' : '' ?> >女性</option>
            </select>
            <input id="name-<?php echo $i+1 ?>" class="form-control" type="text" name="name-<?php echo $i+1 ?>" required value="<?php echo $companionList[$i]['name'] ?>">
        </div>
    <?php endfor ?>
    <p>
        <button id="btn-regist" class="btn btn-primary" type="submit" name="register">登録</button>
        <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
    </p>

</form>
<p><a href="./EventInfo.php?id=<?php echo $_GET['game_id'] ?>">イベント情報ページに戻る</a></p>
<p><a href="./index.php">イベント一覧に戻る</a></p>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(function() {
        $('#btn-delete').on('click', function() {
            return confirm('削除してもよろしいですか');
        });
        $('#btn-add').on('click', function() {
            var num = Number($('#companion').val());
            var current = $('#douhan-' + num);
            num++;
            var div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目');
            div.append($('#occupation').clone().attr('id', 'occupation-' + num).attr('name', 'occupation-' + num));
            div.append($('#sex').clone().attr('id', 'sex-' + num).attr('name', 'sex-' + num));
            div.append($('#name').clone().attr('id', 'name-' + num).attr('name', 'name-' + num).attr('placeholder', '名前').val(''));
            div.append($('<br>'));
            current.after(div);
            $('#companion').val(num);
        });
        $('#btn-del').on('click', function() {
            var num = Number($('#companion').val());
            if(num > 0) {
                $('#douhan-' + num).remove();
                num--;
            }
            $('#companion').val(num);
        });
        $('#btn-regist').on('click', function() {
            if($('#name').val() === '') {
                return true;
            }
            var msg = '以下の内容で登録します\n' + 
            '名前：' + $('#name').val() + '\n';
            // '職種：' + $('#companion').val() + '\n' +
            // '性別：' + $('#sex').val();
            var num = Number($('#companion').val());
            for(let i = 0; i < num; i++) {
                msg += '同伴者' + (i + 1) + '：' + $('#name-' + i).val() + '\n';
            }
            return confirm(msg);
        });
        $('#user').change(function() {
                $.ajax({
                    url:'../../controller/api/GetUserInfo.php',
                    type:'POST',
                    data:{
                        'id':$('#user').val()
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (user) => {
                    // 同伴者削除
                    for(let i = Number($('#companion').val()); i > 0; i--) {
                        $('#douhan-' + i).remove();
                    }
                    $('#companion').val(0);

                    // ユーザー情報セット
                    $('#name').val(user.name);
                    $('#occupation').val(user.occupation);
                    $('#sex').val(user.sex);
                    $('#email').val(user.email);
                    $('#remark').val(user.remark);
                    // 同伴者情報追加
                    $.ajax({
                        url:'../../controller/api/GetDefaultCompanionList.php',
                        type:'POST',
                        data:{
                            'id':user.id
                        }
                    })
                    .done((conpanionList) => {
                        // console.log(conpanionList);
                        
                        for(let i = 0; i < conpanionList.length; i++) {
                            var current = $('#douhan-' + i);
                            let num = i + 1;
                            var div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目');
                            div.append($('#occupation').clone().attr('id', 'occupation-' + num).attr('name', 'occupation-' + num).val(conpanionList[i].occupation));
                            div.append($('#sex').clone().attr('id', 'sex-' + num).attr('name', 'sex-' + num).val(conpanionList[i].sex));
                            div.append($('#name').clone().attr('id', 'name-' + num).attr('name', 'name-' + num).attr('placeholder', '名前').val(conpanionList[i].name));
                            div.append($('<br>'));
                            current.after(div);
                            $('#companion').val(num);
                        }
                    })
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {
                })
            })
    })
</script>
</body>
</html>