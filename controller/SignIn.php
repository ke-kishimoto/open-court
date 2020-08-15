<?php
session_start();

require_once('./header.php');

$title = 'ログイン';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/signIn.php');
include('../view/common/footer.php');
?>