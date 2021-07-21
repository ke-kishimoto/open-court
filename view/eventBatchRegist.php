<h1>イベント一括登録</h1>
    下記の内容で一括登録できます。
    <form id="" action="/participant/eventBatchRegistComplete" method="post" class="form-group">
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
        <p>
            職種
            <select id="occupation" name="occupation" class="custom-select mr-sm-2">
                <option value="1" <?php echo $occupation == '1' ? 'selected' : '' ?>>社会人</option>
                <option value="2" <?php echo $occupation == '2' ? 'selected' : '' ?>>大学・専門学校</option>
                <option value="3" <?php echo $occupation == '3' ? 'selected' : '' ?>>高校</option>
            </select>
        </p>
        <p>
            性別
            <select id="sex" name="sex" class="custom-select mr-sm-2">
                <option value="1" <?php echo $sex == '1' ? 'selected' : '' ?>>男性</option>
                <option value="2" <?php echo $sex == '2' ? 'selected' : '' ?>>女性</option>
            </select>
        </p>
        <p>
            名前
            <input id="name" class="form-control" type="text" name="name" required maxlength="50" value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['name'] ?>">
        </p>
        <?php if(!(isset($_SESSION['user']) && !empty($_SESSION['user']['line_id'] ?? ''))): ?>
                    <p>
                        メールアドレス ※必須
                        <input id="email" class="form-control" type="email" name="email" maxlength="50" required value="<?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['email'] ?>">
                    </p>
                <?php endif; ?>
                <input type="hidden" id="line_id" name="line_id" value="<?php echo htmlspecialchars(!isset($_SESSION['user']) ? '' : $_SESSION['user']['line_id'] ?? '') ?>">
        <p>
            備考
            <textarea class="form-control" name="remark" maxlength="200"><?php echo !isset($_SESSION['user']) ? '' : $_SESSION['user']['remark'] ?></textarea>
        </p>
        <p id="douhan-0">
            <!-- 同伴者
            <input class="form-control" type="number" name="companion" required min="0"> 
            -->
            <input id="companion" name="companion" type="hidden" value="<?php echo count($companions); ?>">
            <p id="douhanErrMsg" style="color: red; display: none;">同伴者は10人までです</p>
            <button class="btn btn-secondary" id="btn-companion-add" type="button">同伴者追加</button>
            <button class="btn btn-danger" id="btn-companion-del" type="button">同伴者削除</button>
        </p>
        <?php for($i = 0;$i < count($companions); $i++): ?>
            <div id="douhan-<?php echo $i + 1 ?>">
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
        <?php endfor ?>

        <br>
        イベント一覧<br>
        ※予約済みのイベントは表示されません。<br>
        <hr>

        <?php foreach($gameInfoList as $gameInfo): ?>

            <div class="eventList-checkbox">
                <div class="eventList-item">
                    <input type="checkbox" class="form-check-input" id="check-<?php echo $gameInfo['id'] ?>" name="game_id[]" value="<?php echo $gameInfo['id'] ?>">
                    <label class="form-check-label" for="check-<?php echo $gameInfo['id'] ?>">参加</label>
                </div>
                <div class="eventList-item">
                    <?php echo htmlspecialchars($gameInfo['title']) ?><br>
                    日付：<?php echo htmlspecialchars($gameInfo['game_date']) ?></br>
                    時間：<?php echo htmlspecialchars($gameInfo['start_time']) ?>～<?php echo htmlspecialchars($gameInfo['end_time']) ?></br>
                    場所：<?php echo htmlspecialchars($gameInfo['place']) ?></br>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>

        <button id="btn-partisipant-regist" class="btn btn-primary" type="submit">
            一括登録
        </button>
    </form>

