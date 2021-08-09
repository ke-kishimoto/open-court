<div id="app">
    <vue-header></vue-header>

    <h1><?php echo $notice['title']; ?></h1>
    <p>
        <?php echo $notice['date']; ?>
    </p>
    <p>
        詳細
    </p>
    <p>
        <?php echo $notice['content']; ?>
    </p>

    <vue-footer></vue-footer>

</div>
<script src="/resource/js/common.js"></script>
<script src="/resource/js/Vue.min.js"></script>
<script src="/resource/js/header.js"></script>
<script src="/resource/js/footer.js"></script>
<script>
    'use strict'

    const vue = new Vue({
        el:"#app",
    })
</script>
</body>
</html>