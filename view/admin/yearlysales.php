<table>
    <tr>
        <td colspan=7>
            <div class="year">
                <p>売上確認(年単位)</p>
                <a href="./index">売上確認(月単位)へ</a>
            </div>
        </td>
    </tr>
</table>
<?php if (!empty($salesYearList)) : ?>
    <table>
        <tr>
            <th>年</th>
            <th>参加人数</th>
            <th>売上金額</th>
        </tr>
        <?php foreach ($salesYearList as $year) : ?>
            <tr>
                <th><?php echo $year['date'] ?></th>
                <!-- <th><a href="./detail?gameid=<?php echo $event['game_id'] ?>"><?php echo $event['title'] ?></a></th> -->
                <th><?php echo $year['cnt'] ?></th>
                <th><?php echo $year['amount'] ?></th>
            </tr>
        <?php endforeach ?>
        <tr>
            <th colspan="1">合計</th>
            <th><?php echo $total_cnt ?></th>
            <th><?php echo $total_amount ?></th>
        </tr>
    </table>
<?php else : ?>
    <p>対象月にイベントはありません。</p>
<?php endif ?>
<p>
    <a href="/admin/admin/index">トップに戻る</a>
</p>