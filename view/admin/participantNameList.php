<?php if (!empty($participantList)): ?>
    <p>参加者リスト</p>
    <table>
        <tr>
            <th>名前</th><th>職種</th><th>性別</th>
        </tr>
        <?php foreach($participantList as $participant): ?>
            <tr>
                <th><?php echo $participant['name'] ?></th>
                <th><?php echo $participant['occupation_name'] ?></th>
                <th><?php echo $participant['sex_name'] ?></th>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p>参加者はいません</p>
<?php endif ?>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>
</html>