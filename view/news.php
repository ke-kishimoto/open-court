<h1>お知らせ一覧</h1>
<div id="notivce-list">
    <?php foreach ($noticeList as $notice): ?>
        <p>
            <?php echo $notice['date']; ?>&nbsp;&nbsp;&nbsp;
            <a href="/notice/detail?id=<?php echo $notice['id'] ?>">
                <?php echo $notice['title'] ?>
            </a>
        </p>
    <?php endforeach; ?>
</div>
