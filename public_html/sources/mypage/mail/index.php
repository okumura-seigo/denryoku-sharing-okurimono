<?php

  // ライブラリ読み込み
  require_once WEB_APP."user.php";
  require_once WEB_APP."mypage.php";

  $my_page = new MyPage();
  $my_page_datas = $my_page->getMyPageData();
  $_POST['page_no'] ? $page_no = $_POST['page_no'] : $page_no = 1;
  $messages_conut = $my_page->getMessageCount();
  $message_datas = $my_page->getMessageListData($page_no);

