<p>参加者登録</p>
<form action="/admin/participant/ParticipantRegist" method="post" class="form-group">
    <input type="hidden" id="participant_id" name="id" value="<?php echo $participant['id'] ?>">
    <input type="hidden" name="game_id" value="<?php echo $_GET['game_id'] ?>">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
    <p class="<?php echo $userListClass ?>"> 
        <select name="user_id" id="user">
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
            <!-- <button id="companion-delete" class="btn btn-danger btn-companion-delete">削除</button> -->
            <input id="companion" name="companion" type="hidden" value="<?php echo count((array)$companionList) ?>">
            <button class="btn btn-secondary" id="btn-companion-add" type="button">同伴者追加</button>
            <!-- <button class="btn btn-danger" id="btn-companion-del" type="button">同伴者削除</button> -->
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
        <button id="btn-participant-regist" class="btn btn-primary" type="submit" name="register">登録</button>
        <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
    </p>

</form>
<p><a href="/admin/event/eventInfo?gameid=<?php echo $_GET['game_id'] ?>">イベント情報ページに戻る</a></p>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js">
</script>
</body>
</html>