<?php

// ライブラリ読み込み
require_once WEB_APP."mypage.php";

$edit_datas = $_POST;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>電力シェアリング</title>
  <meta name="description" content="">
  <meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:title" content="">
  <meta property="og:description" content="">
  <meta property="og:site_name" content="">
  <meta property="og:image" content="">
  <link rel="icon" href="./favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css"
    integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
  <link href="../../../css/style.css" rel="stylesheet" media="all">
</head>

<body>
  <header class="l-header">
   <?php require_once TEMPLATE_DIR.'mypage_header.php' ?>
  </header>
  <div class="l-contents__wrap">
    <main class="l-contents">
      <div class="l-breadcrumbs">
        <ol class="l-breadcrumbs__inner">
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>">電力シェアリング TOP</a>
          </li>
          <li class="l-breadcrumbs__item">
            <a href="<?php echo h(HOME_URL) ?>mypage/">マイページ TOP</a>
          </li>
          <li class="l-breadcrumbs__item">
            <span>会員情報の照会・変更</span>
          </li>
        </ol>
      </div>
      <section class="l-contents__section">
        <div class="l-contents__inner">
          <h2 class="c-title__page">会員情報の照会・変更</h2>
          <form name="yes_form" action="exe" method="post">
            <?php
              foreach ($edit_datas as $key => $val) {
            ?>
                <input type="hidden" name="'<?php echo $key ?>" value="<?php echo $val ?>">
            <?php } ?>
          </form>
          <form name="no_form" action="index" method="post">
            <?php
              foreach ($edit_datas as $key => $val) {
           ?>
                <input type="hidden" name="'<?php echo $key ?>" value="<?php echo $val ?>">
            <?php } ?>
          </form>
          <table class="c-form__table">
            <tr>
              <th class="u-w4em">姓</th>
              <td>
                <?php echo $edit_datas['name1']; ?>
              </td>
              <th class="u-w6em">名</th>
              <td>
                <?php echo $edit_datas['name2']; ?>
              </td>
            </tr>
            <tr>
              <th>セイ</th>
              <td>
                <?php echo $edit_datas['firi1'] ?>
              </td>
              <th>メイ</th>
              <td>
                <?php echo $edit_datas['firi2'] ?>
              </td>
            </tr>
          </table>
          <table class="c-form__table">
            <tr>
              <th>メールアドレス</th>
              <td>
                <?php echo $user_datas['email'] ?>
              </td>
            </tr>
            <tr>
              <th>性別</th>
              <td>
                <?php
                  if($edit_datas['sex'] == '0') {
                ?>
                    <label>男性</label>
                <?php
                  }elseif($edit_dates['sex'] == '1') {
                ?>
                    <label>女性</label>
                <?php
                  }else{
                ?>
                    <label>その他</label>
                <?php }?>
              </td>
            </tr>
            <tr>
              <th>生年月日</th>
              <td>
                <?php echo $user_datas['birthday'] ?>
              </td>
            </tr>
            <tr>
              <th>職業</th>
              <td>
                <?php echo $user_datas['works'] ?>
              </td>
            </tr>
            <tr>
              <th>郵便番号</th>
              <td>
                <?php echo $user_datas['post'] ?>
              </td>
            </tr>
            <!-- <tr>
              <th>checkbox</th>
              <td>
                <div class="c-form__float">
                  <div class="c-form__float-item">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox1" name="checkbox">
                      <label for="checkbox1">checkbox1</label>
                    </div>
                  </div>
                  <div class="c-form__float-item">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox2" name="checkbox">
                      <label for="checkbox2">checkbox2</label>
                    </div>
                  </div>
                  <div class="c-form__float-item">
                    <div class="c-form__checkbox">
                      <input type="checkbox" id="checkbox3" name="checkbox">
                      <label for="checkbox3">checkbox3</label>
                    </div>
                  </div>
                </div>
              </td>
            </tr> -->
          </table>
        </div>
      </section>
    </main>
    <footer class="l-footer">
      <?php require_once TEMPLATE_DIR.'footer.php' ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="../../../js/common.js"></script>
</body>

</html>