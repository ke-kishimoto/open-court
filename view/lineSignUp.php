<div>
    <div class="explain-box">
        <span class="explain-tit"><?php echo $title ?></span>
        <p>イベントへ応募時、以下の入力項目がデフォルトで設定されます</p>
    </div>
    <form id="signUp_form" action="/user/linesignupcomplete" method="post" class="form-group">
        <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
        <p style="color: red;"><?php if(!empty($errMsg)){echo $errMsg;};?></p>
        <p>
            職種
            <select id="occupation" name="occupation" class="custom-select mr-sm-2">
                <option value="1" <?php echo $user['occupation'] == '1' ? 'selected' : '' ?>>社会人</option>
                <option value="2" <?php echo $user['occupation'] == '2' ? 'selected' : '' ?>>大学・専門学校</option>
                <option value="3" <?php echo $user['occupation'] == '3' ? 'selected' : '' ?>>高校</option>
            </select>
        </p>
        <p>
            性別
            <select id="sex" name="sex" class="custom-select mr-sm-2">
                <option value="1" <?php echo $user['sex'] == '1' ? 'selected' : '' ?>>男性</option>
                <option value="2" <?php echo $user['sex'] == '2' ? 'selected' : '' ?>>女性</option>
            </select>
        </p>
        <p>
            名前
            <input id="name" class="form-control" type="text" name="name" required maxlength="50" value="<?php echo $user['name'] ?>">
        </p>
        <p>
            備考
            <textarea class="form-control" name="remark" maxlength="200"><?php echo $user['remark'] ?></textarea>
        </p>
        <p id="douhan-0">
            <input id="companion" name="companion" type="hidden" value="<?php echo count($companions); ?>">
            <p id="douhanErrMsg" style="color: red; display: none;">同伴者は10人までです</p>
            <button class="btn btn-secondary" id="btn-companion-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-companion-del" type="button">同伴者削除</button>
        </p>
        <?php for($i = 0;$i < count($companions); $i++): ?>
            <div id="douhan-<?php echo $i + 1 ?>">
            <?php echo $i+1 ?>人目
            <select id="occupation-<?php echo $i + 1 ?>" name="occupation-<?php echo $i + 1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companions[$i]['occupation'] == '1' ? 'selected' : ''; ?>>社会人</option>
                <option value="2" <?php echo $companions[$i]['occupation'] == '2' ? 'selected' : ''; ?>>大学・専門学校</option>
                <option value="3" <?php echo $companions[$i]['occupation'] == '3' ? 'selected' : ''; ?>>高校</option>
            </select>
            <select id="sex-<?php echo $i + 1 ?>" name="sex-<?php echo $i + 1 ?>" class="custom-select mr-sm-2">
                <option value="1" <?php echo $companions[$i]['sex'] == '1' ? 'selected' : ''; ?>>男性</option>
                <option value="2" <?php echo $companions[$i]['sex'] == '2' ? 'selected' : ''; ?>>女性</option>
            </select>
            <input id="name-<?php echo $i + 1 ?>" class="form-control" type="text" name="name-<?php echo $i + 1 ?>" required maxlength="50" value="<?php echo $companions[$i]['name']; ?>">
            </div>
            <br>
        <?php endfor ?>
        <button class="btn btn-primary" type="submit">登録</button>
    </form>
    <br>
</div>
