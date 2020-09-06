    <p>対象イベント：<?php echo $gameInfo['title'] ?></p>

    <details>
        <summary>イベント情報登録</summary>
        <br>
        <form action="EventComplete.php" method="post" class="form-group">
            <input type="hidden" id="game_id" name="game_id" value="<?php echo $gameInfo['id']; ?>">
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

                <table>
                    <tr>
                        <th>職種</th><th>男性</th><th>女性</th><th>全体</th>
                    </tr>
                    <tr>
                        <th>社会人</th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=1&waiting_flg=0">
                                <span id="sya_men"><?php echo $detail['sya_men'] ?></span>人
                            </a>        
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=2&waiting_flg=0">
                                <span id="sya_women"><?php echo $detail['sya_women'] ?></span>人
                            </a>        
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=1&sex=0&waiting_flg=0">
                                <span id="sya_all"><?php echo $detail['sya_all'] ?></span>人
                            </a>        
                        </th>
                    </tr>
                    <tr>
                        <th>大学・専門</th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=1&waiting_flg=0">
                                <span id="dai_men"><?php echo $detail['dai_men'] ?></span>人
                            </a>        
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=2&waiting_flg=0">
                                <span id="dai_women"><?php echo $detail['dai_women'] ?></span>人
                            </a>        
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=2&sex=0&waiting_flg=0">
                                <span id="dai_all"><?php echo $detail['dai_all'] ?></span>人
                            </a>        
                        </th>
                    </tr>
                    <tr>
                        <th>高校</th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=1&waiting_flg=0">
                                <span id="kou_men"><?php echo $detail['kou_men'] ?></span>人
                            </a>        
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=2&waiting_flg=0">
                                <span id="kou_women"><?php echo $detail['kou_women'] ?></span>人
                            </a>        
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=3&sex=0&waiting_flg=0">
                                <span id="kou_all"><?php echo $detail['kou_all'] ?></span>人
                            </a>        
                        </th>
                    </tr>
                    <tr>
                        <th>キャンセル待ち</th>
                        <th>
                            -       
                        </th>
                        <th>
                            -    
                        </th>
                        <th>
                            <a href="<?php dirname(__FILE__) ?>./ParticipantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=0&sex=0&waiting_flg=1">
                                <span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人
                            </a>        
                        </th>
                    </tr>
                </table>

                <!-- <p>社会人：
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
                <p><a href="<?php dirname(__FILE__) ?>./participantNameList.php?gameid=<?php echo $gameInfo['id'] ?>&occupation=0&sex=0&waiting_flg=1">キャンセル待ち：<span id="waiting_cnt"><?php echo $detail['waiting_cnt'] ?></span>人</a></p> -->
            </details>
        </div>
    </div>
    <hr>
    <details class="<?php echo $participantDisp ?>">
        <summary>参加者リスト</summary>
        <br>
        <a class="btn btn-primary" href="ParticipantInfo.php?game_id=<?php echo $gameInfo['id']; ?>">参加者追加</a>
        <a class="btn btn-info" href="<?php echo $mailto ?>">参加者全員に連絡</a>
        <?php foreach ((array)$participantList as $participant): ?>
            <?php if($participant['main'] === '1'): ?>
                <hr>
                <div id="participant-<?php echo $participant['id'] ?>">
                <p>
                    <a class="btn btn-secondary" href="ParticipantInfo.php?id=<?php echo $participant['id']; ?>&game_id=<?php echo $gameInfo['id']; ?>">修正</a>
                    <button type="button" class="waiting btn btn-<?php echo $participant['waiting_flg'] == '1' ? 'warning' : 'success' ?>" value="<?php echo $participant['id'] ?>">
                    <?php echo $participant['waiting_flg'] == '1' ? 'キャンセル待ちを解除' : 'キャンセル待ちに変更' ?></button>
                    <span class="duplication"><?php echo $participant['chk'] ?></span>
                    <button type="button" class="btn btn-danger btn-participant-delete" value="<?php echo $participant['id'] ?>">削除</button>
                </p>
            <?php endif ?>
        
            <p>
                <?php /* echo htmlspecialchars($participant['waiting_name']); */ ?>
                <?php echo htmlspecialchars($participant['companion_name']); ?>  &nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['name']); ?>  &nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['occupation_name']); ?>  &nbsp;&nbsp;
                <?php echo htmlspecialchars($participant['sex_name']); ?>  &nbsp;&nbsp;
            </p>
            <?php if($participant['main'] == '1'): ?>
                <p>
                    連絡先：
                    <a href="mailto:<?php echo htmlspecialchars($participant['email']); ?>"><?php echo htmlspecialchars($participant['email']); ?></a>
                </p>
                <p>
                    備考：<?php echo htmlspecialchars($participant['remark']); ?>
                </p>
            </div>
            <?php endif ?>
        <?php endforeach; ?>
    </details>
