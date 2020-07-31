<?php 

// session_unset('user');
session_destroy();
header('Location: ../index.php');

?>