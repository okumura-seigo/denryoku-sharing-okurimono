<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";

// プロジェクト参加履歴
$resProjectHistory = $objDB->findData(
  'project_history',
  array(
    "join" => array("Inner Join project On project_history.project_id = project.project_id"),
    "where" => array("project_history.user_id = ?"),
    "order" => array("project_history.update_datetime Desc"),
    "param" => array($infoLoginUser['user_id']),
  )
);
