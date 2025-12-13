<?php
// This will generate a hashed password for you
$password = 'admin123';  // Change this to your desired password
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Your hashed password is:<br>";
echo $hash;
echo "<br><br>";
echo "Copy this hash and update your admin table";
?>
