<?php
session_start();
$vcode = new SaeVCode();
$_SESSION['vcode'] = $vcode->answer();
$question = $vcode->question();
$q = $question['img_html'];
$qs = explode('"', $q);
echo $qs[1];