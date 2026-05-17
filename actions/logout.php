<?php
session_start();
session_destroy();
header('Location: /vite-gourmand/index.php');
exit;
?>