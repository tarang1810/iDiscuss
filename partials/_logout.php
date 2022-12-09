<?php
session_start();

echo "You are logged out. please wait...";
session_destroy();
header("Location: /forum")
?>