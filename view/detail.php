<?php if ($pastEvent) {
    echo ('<p style="color: red;">※終了したイベントのため応募できません</p>');
}
?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="event-info-tab" data-toggle="tab" href="#event-info" role="tab" aria-controls="home" aria-selected="true">イベント</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="contact" aria-selected="false">現在の状況</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="contact" aria-selected="false">参加者</a>
    </li>
</ul>

<div class="tab-content" id="nav-tabContent">

    <div class="tab-pane fade show active" id="event-info" role="tabpanel" aria-labelledby="event-info-tab">
        <br>

        <p><?php echo htmlspecialchars($gameInfo['title']) ?></p>
        <p>日付：<?php echo htmlspecialchars($gameInfo['game_date']) ?></p>
        <p>時間：<?php echo htmlspecialchars($gameInfo['start_time']) ?>～<?php echo htmlspecialchars($gameInfo['end_time']) ?></p>
        <p>場所：<?php echo htmlspecialchars($gameInfo['place']) ?></p>
        <p>詳細：<?php echo htmlspecialchars($gameInfo['detail']) ?></p>
        <p>社会人：<?php echo htmlspecialchars($gameInfo['price1']) ?>円<br>
            大学・専門：<?php echo htmlspecialchars($gameInfo['price2']) ?>円<br>
            高校生：<?php echo htmlspecialchars($gameInfo['price3']) ?>円</p>

        <div class="<?php echo $pastEvent === true ? 'hidden' : '' ?>">
            <form id="join_form" action="/participant/participation" method="post" class="form-group">
                <p>【応募フォーム】<span class="text-danger"><?php echo $Registered ? '※参加登録済みです' : '' ?></span></p>
                <input type="hidden" id="game_id" name="game_id" value="<?php echo htmlspecialchars($gameInfo['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
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
                    名前 ※必須
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
                    <input id="companion" name="companion" type="hidden" value="<?php echo count($companions); ?>">
                    <p id="douhanErrMsg" style="color: red; display: none;">同伴者は10人までです</p>
                    <button class="btn btn-secondary" id="btn-companion-add" type="button">同伴者追加</button>
                    <button class="btn btn-danger" id="btn-companion-del" type="button">同伴者削除</button>
                </p>
                <?php for ($i = 0; $i < count($companions); $i++) : ?>
                    <div id="douhan-<?php echo $i + 1 ?>">
                        <?php echo ($i + 1) . '人目' ?><br>
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
                <input type="hidden" name="title" value="<?php echo htmlspecialchars($gameInfo['title']) ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($gameInfo['game_date']) ?>">
                <input type="hidden" name="participantId" value="<?php echo htmlspecialchars($participantId) ?>">
                <button id="btn-partisipant-regist" name="<?php echo $Registered ? 'update' : 'insert' ?>" class="btn btn-primary" type="submit" value="regist"><?php echo $Registered ? '修正' : '登録' ?></button>
                <a class="btn btn-danger <?php echo $Registered ? '' : 'hidden' ?>" href="/participant/cancel?gameid=<?php echo htmlspecialchars($gameInfo['id']) ?>">参加のキャンセル</a>
            </form>
        </div>
    </div>
    
    <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">

        <br>
        <p>【参加予定 <span id="cnt"><?php echo htmlspecialchars($detail['cnt']) ?></span>人】【上限 <?php echo htmlspecialchars($gameInfo['limit_number']) ?>人】</p>
        <p>社会人：
            女性 <span id="sya_women"><?php echo htmlspecialchars($detail['sya_women']) ?></span>人、
            男性 <span id="sya_men"><?php echo htmlspecialchars($detail['sya_men']) ?></span>人
            <p>大学・専門：
                女性 <span id="dai_women"><?php echo htmlspecialchars($detail['dai_women']) ?></span>人、
                男性 <span id="dai_men"><?php echo htmlspecialchars($detail['dai_men']) ?></span>人
            </p>
            <p>高校生：
                女性 <span id="kou_women"><?php echo htmlspecialchars($detail['kou_women']) ?></span>人、
                男性 <span id="kou_men"><?php echo htmlspecialchars($detail['kou_men']) ?></span>人
            </p>
            <p>キャンセル待ち：<span id="waiting_cnt"><?php echo htmlspecialchars($detail['waiting_cnt']) ?></span>人</p>
    </div>

    <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">

        <?php foreach ((array)$participantList as $participant) : ?>
            <?php if ($participant['main'] === '1') : ?>
                <hr>
            <?php endif ?>
            <p>
                <?php echo htmlspecialchars($participant['waiting_name']); ?>
                <?php echo $participant['waiting_flg'] === '1' ? '<br>' : ''; ?>
                <?php echo htmlspecialchars($participant['companion_name']); ?>&nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['name']); ?>&nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['occupation_name']); ?>&nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['sex_name']); ?>&nbsp;&nbsp;
            </p>
        <?php endforeach; ?>
    </div>
</div>
<?php include('common/footer.php') ?>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/vue.min.js"></script>
</body>
</html>