<?php

# パラメータ設定
$arrParam = array(
  "email" => "メールアドレス",
  "name1" => "お名前(姓)",
  "name2" => "お名前(名)",
  "furi1" => "フリガナ(セイ)",
  "furi2" => "フリガナ(メイ)",
  "sex" => "性別",
  "birthday_y" => "生年",
  "birthday_m" => "生月",
  "birthday_d" => "生日",
  "post" => "郵便番号",
  "pref" => "都道府県",
  "address1" => "市区町村",
  "address2" => "町名・番地",
  "address3" => "建物名",
  "tel1" => "電話番号1",
  "tel2" => "電話番号2",
  "works" => "職業",
  "works_type" => "業種",
  "final_education" => "最終学歴",
  "spouse" => "配偶者",
  "childeren" => "子ども",
  "number_of_people_living_together" => "同居人数(ご本人除く)",
  "private_car" => "自家用車",
  "car_license" => "自動車運転免許",
  "householde_income" => "世帯年収（税込）",
  "housing_form" => "住居形態"
);
// ライブラリ読み込み
require_once WEB_APP."mypage.php";

// データ取得
$requestData = getRequestData($arrParam);

if (count($_POST) == 0) {
  $EditProfile = new EditProfile();
  $user_datas = $EditProfile->getProfile($edit_datas);
  list($user_datas['birthday_y'], $user_datas['birthday_m'], $user_datas['birthday_d']) = explode("-", $user_datas['birthday']);
}else{
  $user_datas = $requestData;
}

// 出力設定
extract($requestData);

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
  <link href="../../css/mypage__profile.css" rel="stylesheet">
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

          <form action="conf" class="c-form2" method="post">

            <h3 class="c-form2__hw">会員ID・パスワード</h3>

            <div class="c-form2__table">

              <div class="c-form2__table__line">

                <div class="c-form2__table__line__cell_hw">会員ID<br>(メールアドレス)</div>
                <div class="c-form2__table__line__cell_contents">

                  <input type="text" name="name" class="c-form2__input_text c-form2__input_text--50per" required placeholder="abcd@efg.com" value="<?php echo h($user_datas['email']) ?>"  ></label>
                  
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">パスワード</div>
                <div class="c-form2__table__line__cell_contents">
                  <a href="password" class="c-form2__password_link">パスワードを変更する</a>
                </div>
              </div>

            </div>

            <?php if (count($errMsg) > 0) { ?>
              <div style="color:#FF0000">
                <?php foreach ($errMsg as $val) { ?>
                  <?php echo h($val) ?><br>
                <?php } ?>
              </div>
            <?php } ?>

            <h3 class="c-form2__hw">基本情報</h3>

            <div class="c-form2__table">
              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">氏名<font color="red">【必須】</font></div>
                <div class="c-form2__table__line__cell_contents">
                  <fieldset class="c-form2__fieldset">
                    <label><span class="c-form2__label_name">姓</span><input type="text" class="c-form2__input_text c-form2__input_text--name" name="name1" placeholder="姓" value="<?php echo $user_datas['name1'] ?>"></label>
                    <label><span class="c-form2__label_name">名</span><input type="text" class="c-form2__input_text c-form2__input_text--name"  name="name2" placeholder="名" value="<?php echo $user_datas['name2'] ?>"></label>
                  </fieldset>
                  <fieldset class="c-form2__fieldset">
                    <label><span class="c-form2__label_name">セイ</span><input type="text" class="c-form2__input_text c-form2__input_text--name" name="furi1" placeholder="セイ" value="<?php echo $user_datas['furi1'] ?>"></label>
                    <label><span class="c-form2__label_name">メイ</span><input type="text" class="c-form2__input_text c-form2__input_text--name" name="furi2" placeholder="メイ" value="<?php echo $user_datas['furi2'] ?>"></label>
                  </fieldset>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">性別</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form__float">
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio1" name="sex" value="男" <?php if($user_datas['sex'] == '男') { echo 'checked="checked"'; } ?> >
                        <label for="radio1">男性</label>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio2" name="sex" value="女" <?php if($user_datas['sex'] == '女') { echo 'checked="checked"'; } ?> >
                        <label for="radio2">女性</label>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio3" name="sex" value="上記に該当しない" <?php if($user_datas['sex'] == '上記に該当しない') { echo 'checked="checked"'; } ?> >
                        <label for="radio3">その他</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">生年月日</div>
                <div class="c-form2__table__line__cell_contents">

                  <div class="c-form2__select">
                    <select name="birthday_y" id="">
                      <option value=""></option>
<?php for ($i = date("Y") - 100;$i <= date("Y");$i++) { ?>
                      <option value="<?php echo h($i) ?>" <?php if ($user_datas['birthday_y'] == $i) echo "selected"; ?>><?php echo h($i) ?></option>
<?php } ?>
                    </select>
                  </div>
                  <span class="c-form2__separetor">年</span>
                  <div class="c-form2__select">
                    <select name="birthday_m" id="">
                      <option value=""></option>
<?php for ($i = 1;$i <= 12;$i++) { ?>
                      <option value="<?php echo h($i) ?>" <?php if ($user_datas['birthday_m'] == $i) echo "selected"; ?>><?php echo h($i) ?></option>
<?php } ?>
                    </select>
                  </div>
                  <span class="c-form2__separetor">月</span>
                  <div class="c-form2__select">
                    <select name="birthday_d" id="">
                      <option value=""></option>
<?php for ($i = 1;$i <= 31;$i++) { ?>
                      <option value="<?php echo h($i) ?>" <?php if ($user_datas['birthday_d'] == $i) echo "selected"; ?>><?php echo h($i) ?></option>
<?php } ?>
                    </select>
                  </div>
                  <span class="c-form2__separetor">日</span>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">郵便番号</div>
                <div class="c-form2__table__line__cell_contents">
                  <input type="text" class="c-form2__input_text" name="post" placeholder="郵便番号" value="<?php echo $user_datas['post'] ?>">
<?php /*
                  <input type="text" class="c-form2__input_text" required placeholder="〇〇〇" size="3" ><span class="c-form2__separetor">-</span>
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="〇〇〇" size="4" >
*/ ?>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">都道府県<span class="required">【必須】</span></div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form2__select">
                    <select name="pref" id="">
                      <option value="">選択してください</option>
<?php foreach ($PREFECTURE_CODE as $key => $val) { ?>
                      <option value="<?php echo h($val) ?>" <?php if ($user_datas['pref'] == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">市区町村</div>
                <div class="c-form2__table__line__cell_contents">
                  <input type="text" class="c-form2__input_text c-form2__input_text--50per" name="address1" placeholder="市区町村" value="<?php echo $user_datas['address1'] ?>">
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">町名・番地</div>
                <div class="c-form2__table__line__cell_contents">
                  <input type="text" name="name" class="c-form2__input_text c-form2__input_text--50per" name="address2" placeholder="町名・番地" value="<?php echo $user_datas['address2'] ?>">
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">建物名</div>
                <div class="c-form2__table__line__cell_contents">
                  <input type="text" class="c-form2__input_text c-form2__input_text--50per" name="address3" placeholder="建物名" value="<?php echo $user_datas['address3'] ?>">
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">電話番号1</div>
                <div class="c-form2__table__line__cell_contents">
                  <input type="text" class="c-form2__input_text" name="tel1" placeholder="電話番号1" value="<?php echo $user_datas['tel1'] ?>">
<?php /*
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="〇〇〇" size="4" ><span class="c-form2__separetor">-</span>
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="〇〇〇" size="4" ><span class="c-form2__separetor">-</span>
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="〇〇〇" size="4" >
*/ ?>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">電話番号2</div>
                <div class="c-form2__table__line__cell_contents">
                  <input type="text" class="c-form2__input_text" name="tel2" placeholder="電話番号2" value="<?php echo $user_datas['tel2'] ?>">
<?php /*
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="" size="4" ><span class="c-form2__separetor">-</span>
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="" size="4" ><span class="c-form2__separetor">-</span>
                  <input type="text" name="name" class="c-form2__input_text" required placeholder="" size="4" >
*/ ?>
                </div>
              </div>

            </div>

            <h3 class="c-form2__hw">オプション項目</h3>

            <div class="c-form2__table">
              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">職業・業種</div>
                <div class="c-form2__table__line__cell_contents">

                  <div class="c-form2__select">
                    <select name="works" id="">
                      <option value="">選択してください</option>
<?php foreach ($WORK_CODE as $key => $val) { ?>
                      <option value="<?php echo h($val) ?>" <?php if ($user_datas['works'] == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                    </select>
                  </div>
                  <div class="c-form2__select">
                    <select name="works_type" id="">
                      <option value="">選択してください</option>
<?php foreach ($JOB_CODE as $key => $val) { ?>
                      <option value="<?php echo h($val) ?>" <?php if ($user_datas['works_type'] == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">最終学歴</div>
                <div class="c-form2__table__line__cell_contents">

                  <div class="c-form2__select">
                    <select name="final_education" id="">
                      <option value="">選択してください</option>
<?php foreach ($EDUCATION_CODE as $key => $val) { ?>
                      <option value="<?php echo h($val) ?>" <?php if ($user_datas['final_education'] == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                    </select>
                  </div>
                </div>

              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">配偶者</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form__float">
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio4" name="spouse" value="あり" <?php if($user_datas['spouse'] == 'あり') { echo 'checked="checked"'; } ?> >
                        <label for="radio4">あり</label>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio5" name="spouse" value="なし" <?php if($user_datas['spouse'] == 'なし') { echo 'checked="checked"'; } ?> >
                        <label for="radio5">なし</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">子供</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form__float">
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio6" name="childeren" value="あり" <?php if($user_datas['childeren'] == 'あり') { echo 'checked="checked"'; } ?> >
                        <label for="radio6">あり</label>
                      </div>
                    </div>
                    <div class="c-form__float-item">
                      <div class="c-form__radio">
                        <input type="radio" id="radio7" name="childeren" value="なし" <?php if($user_datas['childeren'] == 'なし') { echo 'checked="checked"'; } ?> >
                        <label for="radio7">なし</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">同居人数(ご本人除く)</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form2__select">
                    <select name="number_of_people_living_together" id="">
                      <option value="">選択してください</option>
<?php for ($i = 1;$i <= 7;$i++) { ?>
                      <option value="<?php echo h($i) ?>" <?php if ($user_datas['number_of_people_living_together'] == $i) echo "selected"; ?>><?php echo h($i) ?>人</option>
<?php } ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">自家用車</div>
                <div class="c-form2__table__line__cell_contents">

                  <div class="c-form2__select">
                    <select name="private_car" id="">
                      <option value="">選択してください</option>
                      <option value="保有している" <?php if($user_datas['private_car'] == '保有している') { echo 'selected'; } ?>>保有している</option>
                      <option value="保有していない" <?php if($user_datas['private_car'] == '保有していない') { echo 'selected'; } ?>>保有していない</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">自動車運転免許</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form2__select">
                    <select name="car_license" id="">
                      <option value="">選択してください</option>
                      <option value="保有している" <?php if($user_datas['car_license'] == '保有している') { echo 'selected'; } ?>>保有している</option>
                      <option value="保有していない" <?php if($user_datas['car_license'] == '保有していない') { echo 'selected'; } ?>>保有していない</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">世帯年収(税込み)</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form2__select">
                    <select name="householde_income" id="">
                      <option value="">選択してください</option>
<?php foreach ($INCOME_CODE as $key => $val) { ?>
                      <option value="<?php echo h($val) ?>" <?php if ($user_datas['householde_income'] == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="c-form2__table__line">
                <div class="c-form2__table__line__cell_hw">住居形態</div>
                <div class="c-form2__table__line__cell_contents">
                  <div class="c-form2__select">
                    <select name="housing_form" id="">
                      <option value="">選択してください</option>
<?php foreach ($HOUSING_CODE as $key => $val) { ?>
                      <option value="<?php echo h($val) ?>" <?php if ($user_datas['housing_form'] == $val) echo "selected"; ?>><?php echo h($val) ?></option>
<?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

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

          <input type="hidden" name="email" value="<?php echo h($user_datas['email']) ?>">

          <div class="c-form2__button">
            <button class="c-form2__button_submit" type="submit">確認する</button>
          </div>

        </form>

      </section>
    </main>
    <footer class="l-footer">
      <?php require_once TEMPLATE_DIR.'footer.php' ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="../../js/common.js"></script>
</body>
</html>
