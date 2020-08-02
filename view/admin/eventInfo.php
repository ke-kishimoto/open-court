    <p>対象イベント：<?php echo $gameInfo['title'] ?></p>

    <details>
        <summary>イベント情報登録</summary>
        <br>
        <form action="EventComplete.php" method="post" class="form-group">
            <input type="hidden" id="id" name="id" value="<?php echo $gameInfo['id']; ?>">
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
            <div class="<?php echo $templateAreaClass ?>">
                <p>
                    テンプレート：
                    <select name="template" id="template">
                    <option value=""></option>
                    <?php foreach ($eventTemplateList as $eventTemplate): ?>
                        <option value="<?php echo $eventTemplate['id'] ?>"><?php echo $eventTemplate['template_name'] ?></option>
                    <?php endforeach ?>
                    </select>
                </p>
            </div>
            <p>
                タイトル<input class="form-control" type="text" id="title" name="title"  required value="<?php echo $gameInfo['title'] ?>">
            </p>
            <p>
                タイトル略称<input class="form-control" type="text" id="short_title" name="short_title"  required value="<?php echo $gameInfo['short_title'] ?>">
            </p>
            <p>
                日程<input class="form-control" type="date" name="game_date" required value="<?php echo $gameInfo['game_date'] ?>">
            </p>
            <p>
                開始時間<input class="form-control" type="time" step="600" name="start_time" required value="<?php echo $gameInfo['start_time'] ?>">
            </p>
            <p>
                終了時間<input class="form-control" type="time" step="600" name="end_time" required value="<?php echo $gameInfo['end_time'] ?>">
            </p>
            <p>
                場所<input class="form-control" type="text" id="place" name="place" required value="<?php echo $gameInfo['place'] ?>">
            </p>
            <p>
                人数上限<input class="form-control" type="number" id="limit_number" name="limit_number" min="1" required value="<?php echo $gameInfo['limit_number'] ?>">
            </p>
            <p>
                詳細<textarea class="form-control" id="detail" name="detail"><?php echo $gameInfo['detail'] ?></textarea>
            </p>
            <p>
                <button class="btn btn-primary" type="submit" name="register">登録</button>
                <button id="btn-delete" class="btn btn-secondary" type="submit" name="delete">削除</button>
            </p>
        </form>
    </details>

    <hr>
    <div class="<?php echo $participantDisp ?>">
        <div>
            <details>
            <summary>現在の状況</summary>
            <br>
            <p>【参加予定  <span id="cnt"><?php echo $detail['cnt'] ?></span>人】【上限  <?php echo $gameInfo['limit_number'] ?>人】</p>
            <p>社会人：
                <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=2&waiting_flg=0">女性 <span id="sya_women"><?php echo $detail['sya_women'] ?></span>人</a>、
                <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=1&waiting_flg=0">男性 <span id="sya_men"><?php echo $detail['sya_men'] ?></span>人</a>
            <p>大学・専門：
                <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=2&waiting_flg=0">女性 <span id="dai_women"><?php echo $detail['dai_women'] ?></span>人</a>、
                <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=1&waiting_flg=0">男性 <span id="dai_men"><?php echo $detail['dai_men'] ?></span>人</a>
            </p>
            <p>高校生：
                <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=2&waiting_flg=0">女性 <span id="kou_women"><?php echo $detail['kou_women'] ?></span>人</a>、
                <a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=1&waiting_flg=0">男性 <span id="kou_men"><?php echo $detail['kou_men'] ?></span>人</a>
            </p>
            <p><a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=0&sex=0&waiting_flg=1">キャンセル待ち：<span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人</a></p>
            </details>
        </div>
    </div>
    <hr>
    <details class="<?php echo $participantDisp ?>">
        <summary>参加者リスト</summary>
        <br>
        <a class="btn btn-primary" href="ParticipantInfo.php?game_id=<?php echo $gameInfo['id']; ?>">参加者追加</a>
        <?php foreach ((array)$participantList as $participant): ?>
            <?php if($participant['main'] == '1'): ?>
                <hr>
                <p>
                    <a class="btn btn-secondary" href="ParticipantInfo.php?id=<?php echo $participant['id']; ?>&game_id=<?php echo $gameInfo['id']; ?>">修正</a>
                    <button type="button" class="waiting btn btn-<?php echo $participant['waiting_flg'] == '1' ? 'warning' : 'success' ?>" value="<?php echo $participant['id'] ?>">
                    <?php echo $participant['waiting_flg'] == '1' ? 'キャンセル待ちを解除' : 'キャンセル待ちに変更' ?></button>
                    <span class="duplication"><?php echo $participant['chk'] ?></span>
                </p>
            <?php endif ?>
        
            <p>
                <?php echo htmlspecialchars($participant['waiting_name']); ?>
                <?php echo htmlspecialchars($participant['companion_name']); ?>  &nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['name']); ?>  &nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['occupation_name']); ?>  &nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['sex_name']); ?>  &nbsp;&nbsp;
            </p>
            <?php if($participant['main'] == '1'): ?>
                <p>
                    連絡先：<?php echo htmlspecialchars($participant['email']); ?>
                </p>
                <p>
                    備考：<?php echo htmlspecialchars($participant['remark']); ?>
                </p>
                
            <?php endif ?>
        <?php endforeach; ?>
    </details>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        'use strict';
        $(function(){ 
            let id = $('#id').val();
            if(id === '') {
                // 新規の時は削除ボタンは非表示に
                $('#btn-delete').addClass('hidden');
            } else {
                // 修正の時は削除ボタンに確認処理のイベント追加
                $('#btn-delete').on('click', function() {
                    return confirm('削除してもよろしいですか');
                });
            }
            $('#template').change(function() {
                $.ajax({
                url:'../../controller/api/GetEventTemplate.php',
                type:'POST',
                data:{
                    'id':$('#template').val(),
                }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
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
            // キャンセル待ち⇔解除の処理
            $('.waiting').on('click', function() {
               $.ajax({
                url:'../../controller/api/UpdateWaitingFlg.php',
                type:'POST',
                data:{
                    'id':$(this).val(),
                    'game_id':$('#id').val(),
                }
               })
               .done( (data) => {
                    $('#cnt').text(data.cnt);
                    $('#sya_women').text(data.sya_women);
                    $('#sya_men').text(data.sya_men);
                    $('#dai_women').text(data.dai_women);
                    $('#dai_men').text(data.dai_men);
                    $('#kou_women').text(data.kou_women);
                    $('#kou_men').text(data.kou_men);
                    $('#waiting_cnt').text(data.waiting_cnt);
                    if(data.waiting_flg == '0') {
                        $(this).attr('class', 'warning btn btn-success').text('キャンセル待ちに変更');
                    } else {
                        $(this).attr('class', 'warning btn btn-warning').text('キャンセル待ちを解除');
                    }

                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {
                })
            });
        });
    
    </script>
</body>
</html>