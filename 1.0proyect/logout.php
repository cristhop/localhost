<?php
session_start();
session_destroy();
header('Location: service.php');
exit();
?>
