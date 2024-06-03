<?php
$password = 'your_password_here';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>
