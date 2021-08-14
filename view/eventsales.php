<div id="app">

<vue-header></vue-header>

    <?php if (!empty($participantList)) : ?>
        <form action="/sales/update" method="post">
            <table>
                <tr>
                    <td align="center">
                        <p class="sales-event-title"><?php echo $participantList[0]['title'] ?></p>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>名前</th>
                    <th>出欠</th>
                    <th>回収金額</th>
                    <th>備考</th>
                </tr>
                <?php $count = 0; ?>
                <?php foreach ($participantList as $participant) : ?>
                    <tr>
                        <input type="hidden" name="id-<?php echo $count ?>" value="<?php echo $participant['id'] ?>">
                        <th><?php echo $participant['name'] ?></th>
                        <th>
                            <select name="attendance-<?php echo $count ?>" class="form-control input-sm">
                                <option value="1" <?php echo $participant['attendance'] == '1' ? 'selected' : '' ?>>出席</option>
                                <option value="2" <?php echo $participant['attendance'] == '2' ? 'selected' : '' ?>>欠席</option>
                            </select>
                        </th>
    
                        <th><input type="number" name="amount-<?php echo $count ?>" value="<?php echo $participant['amount'] ?>" class="form-control"></th>
                        <th><input type="text" name="amount_remark-<?php echo $count ?>" value="<?php echo $participant['amount_remark'] ?>" class="form-control"></th>
                    </tr>
                    <?php $count++; ?>
                <?php endforeach ?>
            </table>
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="count" value="<?php echo $count ?>">
            <p>
                <button type="submit" class="btn btn-primary">更新</button>
            </p>
        </form>
    <?php else : ?>
        <p>参加者はいません。</p>
    <?php endif ?>
    
    <vue-footer></vue-footer>
</div>


<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'
    const app = new Vue({
        el:"#app",
    })
</script>
</body>
</html>