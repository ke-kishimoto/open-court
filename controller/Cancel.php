<?php
session_start();

require_once('./header.php');

if(isset($_SESSION['user'])) {
    $email = $_SESSION['user']['email'];
    $mode = 'login';
} else {
    $email = '';
    $mode = 'guest';
}
$gameId = $_GET['gameid'];

$title = 'キャンセル';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/cancelForm.php');
include('../view/common/footer.php');
?>
