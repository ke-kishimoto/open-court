<table>
    <tr>
        <td>
            <div class="sales-head">
                <p>売上集計表(年)</p>
            </div>
        </td>
        <td>
            <div class="sales-head">
                <a href="./index">イベント別</a>
            </div>
        </td>
        <td>
            <div class="sales-head">
                <a href="./month">月別</a>
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
                <td><?php echo $year['date'] ?></td>
                <td><?php echo $year['cnt'] ?></td>
                <td><?php echo $year['amount'] ?></td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>合計</td>
            <td><?php echo $total_cnt ?></td>
            <td><?php echo $total_amount ?></td>
        </tr>
    </table>
<?php else : ?>
    <p>イベントはありません。</p>
<?php endif ?>
<p>
    <a href="/admin/admin/index">トップに戻る</a>
</p>
<?php include('common/footer.php') ?>
<script src="/resource/js/common_admin.js">
</script>
</body>
</html>