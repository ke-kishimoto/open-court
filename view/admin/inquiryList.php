<h1>問い合わせ一覧</h1>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="#incomplete" class="nav-link active" data-toggle="tab">未対応</a>
    </li>
    <li class="nav-item">
        <a href="#complete" class="nav-link" data-toggle="tab">対応済</a>
    </li>
</ul>
<div class="tab-content">
    <div id="incomplete" class="tab-pane active">
        <br>
        <?php foreach($inquiryList as $inquiry): ?>
            <?php if((int)$inquiry['status_flg'] === 0): ?>
                名前：<?php echo $inquiry['name']; ?><br>
                対象イベント：<?php echo $inquiry['title']; ?><br>
                連絡先：<?php echo $inquiry['email']; ?><br>
                <p>
                    問い合わせ内容：<?php echo $inquiry['content']; ?>
                </p>
                <p>
                    <button class="btn btn-primary btn-inquiry-status" value="<?php echo $inquiry['id'] ?>">対応済みにする</button>
                </p>
                <hr>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div id="complete" class="tab-pane">
        <br>
        <?php foreach($inquiryList as $inquiry): ?>
            <?php if((int)$inquiry['status_flg'] !== 0): ?>
                名前：<?php echo $inquiry['name']; ?><br>
                対象イベント：<?php echo $inquiry['title']; ?><br>
                連絡先：<?php echo $inquiry['email']; ?><br>
                <p>
                    問い合わせ内容：<?php echo $inquiry['content']; ?><br>
                </p>
                <p>
                    <button class="btn btn-secondary btn-inquiry-status" value="<?php echo $inquiry['id'] ?>">未対応にする</button>
                </p>
                <hr>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
