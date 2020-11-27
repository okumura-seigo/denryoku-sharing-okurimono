<?php

// ライブラリ読み込み
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";

// $my_page = new MyPage();
$_POST['page_no'] ? $page_no = $_POST['page_no'] : $page_no = 1;
// $point_historys = $my_page->getPointHistoryData($page_no);

// ポイント明細
$resPointDetail = $objDB->findData(
  'point_detail',
  array(
    "column" => array("point_detail.*", "point_division.atext as point_division_atext"),
    "join" => array("Inner Join point_division On point_detail.point_division_id = point_division.point_division_id"),
    "where" => array("point_detail.user_id = ?"),
    "order" => array("point_detail.update_datetime Desc"),
    "param" => array($infoLoginUser['user_id']),
  )
);

