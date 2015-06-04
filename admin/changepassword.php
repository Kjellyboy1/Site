<?php
function GeneratePassword($chars) {
    $acceptedChars = 'azertyuiopqsdfghjklmwxcvbn';
    $max = strlen($acceptedChars)-1;
    $password = null;
    for($i=0; $i < $chars; $i++) {
    $password = $password . $acceptedChars{mt_rand(0, $max)};
    }
    return($password);

}
?>