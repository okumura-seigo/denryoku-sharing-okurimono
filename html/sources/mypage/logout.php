<?php

//ライブラリ読み込み
require_once WEB_APP."user.php";

session_destroy();
setcookie("ul", "", time() - 3600, "/", DMN_NAME);
redirectUrl(HOME_URL."sign-in/");

