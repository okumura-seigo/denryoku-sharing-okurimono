<?php

// ライブラリ読み込み
require_once WEB_APP."mypage.php";

if (count($_POST) == 0) {
	$EditProfile = new EditProfile();
	$user_datas = $EditProfile->getProfile($edit_datas);
	list($user_datas['birthday_y'], $user_datas['birthday_m'], $user_datas['birthday_d']) = explode("-", $user_datas['birthday']);
}
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
  <link href="../../css/style.css" rel="stylesheet" media="all">
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
<?php if (count($errMsg) > 0) { ?>
	<div style="color:#FF0000">
<?php foreach ($errMsg as $val) { ?>
	・<?php echo h($val) ?><br>
<?php } ?>
	</div>
<?php } ?>
          <form action="conf" class="c-form" method="post">
            <table class="c-form__table">
              <tr>
                <th class="u-w4em">姓</th>
                <td>
                  <input type="text" class="c-form__input" name="name1" placeholder="姓" value="<?php echo $user_datas['name1'] ?>">
                </td>
                <th class="u-w6em">名</th>
                <td>
                  <input type="text" class="c-form__input" name="name2" placeholder="名" value="<?php echo $user_datas['name2'] ?>">
                </td>
              </tr>
              <tr>
                <th>セイ</th>
                <td>
                  <input type="text" class="c-form__input" name="furi1" placeholder="セイ" value="<?php echo $user_datas['furi1'] ?>">
                </td>
                <th>メイ</th>
                <td>
                  <input type="text" class="c-form__input" name="furi2" placeholder="メイ" value="<?php echo $user_datas['furi2'] ?>">
                </td>
              </tr>
            </table>
            <table class="c-form__table">
              <tr>
                <th>メールアドレス</th>
                <td>
                  <input type="text" class="c-form__input" name="email"  value="<?php echo $user_datas['email'] ?>">
                </td>
              </tr>
              <tr>
                <th>性別</th>
                <td>
                  <div class="c-form__float">
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio1" name="sex" value="1" <?php if($user_datas['sex'] == '1') { echo 'checked="checked"'; } ?> >
                        <label for="radio1">男性</label>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio2" name="sex" value="2" <?php if($user_datas['sex'] == '2') { echo 'checked="checked"'; } ?> >
                        <label for="radio2">女性</label>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio3" name="sex" value="3" <?php if($user_datas['name'] == '3') { echo 'checked="checked"'; } ?> >
                        <label for="radio3">その他</label>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <th>生年月日</th>
                <td>
                  <div class="c-form__float">
                    <div class="c-form__float-item">
                      <div class="c-form__select">
                        <select name="birthday_y" id="">
                          <option value=""></option>
<?php for ($i = date("Y") - 100;$i <= date("Y");$i++) { ?>
                          <option value="<?php echo h($i) ?>" <?php if ($user_datas['birthday_y'] == $i) echo "selected"; ?>><?php echo h($i) ?></option>
<?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      年
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__select">
                        <select name="birthday_m" id="">
                          <option value=""></option>
<?php for ($i = 1;$i <= 12;$i++) { ?>
                          <option value="<?php echo h($i) ?>" <?php if ($user_datas['birthday_m'] == $i) echo "selected"; ?>><?php echo h($i) ?></option>
<?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      月
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__select">
                        <select name="birthday_d" id="">
                          <option value=""></option>
<?php for ($i = 1;$i <= 31;$i++) { ?>
                          <option value="<?php echo h($i) ?>" <?php if ($user_datas['birthday_d'] == $i) echo "selected"; ?>><?php echo h($i) ?></option>
<?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      日
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <th>職業</th>
                <td>
                  <input type="text" class="c-form__input" name="works" placeholder="職業" value="<?php echo $user_datas['works'] ?>">
                </td>
              </tr>
              <tr>
                <th>郵便番号</th>
                <td>
                  <input type="text" class="c-form__input" name="post" placeholder="郵便番号" value="<?php echo $user_datas['post'] ?>">
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
              <tr>
                <td colspan="2">
                  <button class="c-form__button" type="submit">確認する</button>
                </td>
              </tr>
            </table>
          </form>
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