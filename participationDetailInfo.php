<?php
use dao\DetailDao;

// 参加者情報取得
$participantList = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
}
?>
<h4>参加者詳細</h4>
<?php foreach ((array)$participantList as $participant): ?>
    <p>
        <?php echo htmlspecialchars($participant['name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['email']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['occupation_name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['sex_name']); ?>&nbsp;&nbsp;
        同伴：<?php echo htmlspecialchars($participant['companion']); ?>人
    </p>
    <p>
        <?php echo htmlspecialchars($participant['remark']); ?>
    </p>
    <p>
        <a class="btn btn-secondary" href="participant.php?id=<?php echo $participant['id']; ?>&game_id=<?php echo $gameInfo['id']; ?>">修正</a>
    </p>
    <hr>
<?php endforeach; ?>
<a  class="btn btn-primary" href="participant.php?game_id=<?php echo $gameInfo['id']; ?>">参加者追加</a>