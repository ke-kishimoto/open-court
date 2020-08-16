<?php if (!empty($userList)): ?>
    <p>ユーザー一覧</p>
    <!-- <table>
        <tr>
            <th>ユーザー名</th><th>職種</th><th>性別</th><th>連絡先</th><th>権限</th><th>権限変更</th>
        </tr>
        <?php foreach($userList as $user): ?>
            <?php if($user['id'] <> $_SESSION['user']['id']): ?>
                <tr>
                    <th><?php echo $user['name'] ?></th>
                    <th><?php echo $user['occupation_name'] ?></th>
                    <th><?php echo $user['sex_name'] ?></th>
                    <th><a href="mailto:<?php echo $user['email'] ?>"><?php echo $user['email'] ?></a></th>
                    <th><span id="authority-name-<?php echo $user['id'] ?>"><?php echo $user['authority_name'] ?></span></th>
                    <th><button class="change-authority btn btn-info" type="button" value="<?php echo $user['id'] ?>">権限の変更</button></th>
                </tr>
            <?php endif; ?>
        <?php endforeach ?>
    </table> -->

    <ol>
        <?php foreach($userList as $user): ?>
            <?php if($user['id'] <> $_SESSION['user']['id']): ?>
                <li>
                    <ul>
                        <li>ユーザー名：<?php echo $user['name'] ?></li>
                        <li>職種：<?php echo $user['occupation_name'] ?></li>
                        <li>性別：<?php echo $user['sex_name'] ?></li>
                        <li>連絡先：<a href="mailto:<?php echo $user['email'] ?>"><?php echo $user['email'] ?></a></li>
                        <li>権限：<span id="authority-name-<?php echo $user['id'] ?>"><?php echo $user['authority_name'] ?></span></li>
                    </ul>
                    <button class="change-authority btn btn-info" type="button" value="<?php echo $user['id'] ?>">権限の変更</button>
                    <hr>
                </li>
            <?php endif; ?>
        <?php endforeach ?>
    </ol>

<?php else: ?>
    <p>現在登録したユーザーはいません</p>
<?php endif ?>

<a href="./index.php">イベント一覧に戻る</a>
