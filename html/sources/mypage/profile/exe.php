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
require_once WEB_APP."user.php";
require_once WEB_APP."mypage.php";
$EditProfile = new EditProfile();

// データ取得
$requestData = getRequestData($arrParam);

// エラーチェック
$errMsg = actionValidate("user_edit_val", $requestData, $arrParam);
if (count($errMsg) == 0) {
  $requestData['birthday'] = $requestData['birthday_y']."-".$requestData['birthday_m']."-".$requestData['birthday_d'];
	$objDB->updateData('user', $requestData, $infoLoginUser['user_id']);
	redirectUrl("exe");
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
          <p>会員情報変更は正常に終了いたしました。</p>
          <div style="margin-top:40px;">
            <a href="<?php echo h(HOME_URL) ?>mypage/profile/" style="color: white;"><button class="c-form__button" type="submit">会員情報の照会・変更ページに戻る</button></a>
          </div>
        </div>
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