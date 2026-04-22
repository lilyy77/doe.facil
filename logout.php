<?php
session_start();
session_destroy();
header("Location: conecte.php");
exit;

