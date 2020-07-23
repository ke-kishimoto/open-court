<?php
use dao\DetailDao;

// 参加者情報取得
$participantList = null;
if(!empty($gameInfo['id'])) {
    $detailDao = new DetailDao();
    $participantList = $detailDao->getParticipantList($gameInfo['id']);
}
?>
<details>
<summary>参加者詳細</summary>
<?php foreach ((array)$participantList as $participant): ?>
    <?php if($participant['main'] === '1'): ?>
        <hr>
    <?php endif ?>
    <p>
        <?php echo htmlspecialchars($participant['waiting_name']); ?>
        <?php echo htmlspecialchars($participant['companion_name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['occupation_name']); ?>&nbsp;&nbsp;
        <?php echo htmlspecialchars($participant['sex_name']); ?>&nbsp;&nbsp;
    </p>
    <p>
        <a class="btn btn-secondary" href="participant.php?id=<?php echo $participant['id']; ?>&game_id=<?php echo $gameInfo['id']; ?>">修正</a>
    </p>
<?php endforeach; ?>
</details>