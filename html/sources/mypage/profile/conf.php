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
$user_datas = $requestData;

// エラーチェック
$errMsg = actionValidate("user_edit_val", $requestData, $arrParam);
if (count($errMsg) > 0) {
  require_once 'index.php';
  exit;
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
          <table class="c-form__table">
            <tr>
              <th>お名前</th>
              <td>
                <?php echo h($user_datas['name1']) ?> <?php echo h($user_datas['name2']) ?>
              </td>
            </tr>
            <tr>
              <th>フリガナ</th>
              <td>
                <?php echo h($user_datas['furi1']) ?> <?php echo h($user_datas['furi2']) ?>
              </td>
            </tr>
            <!-- <tr>
              <th>メールアドレス</th>
              <td>
                <?php echo h($user_datas['email']) ?>
              </td>
            </tr> -->
            <tr>
              <th>性別</th>
              <td>
<?php if($user_datas['sex'] == "男" || $user_datas['sex'] == "女") { ?>
                <?php echo h($user_datas['sex'])."性"; ?>
<?php } else { ?>
                <label>その他</label>
<?php } ?>
              </td>
            </tr>
            <tr>
              <th>生年月日</th>
              <td>
                <?php echo h($user_datas['birthday_y']) ?>年<?php echo h($user_datas['birthday_m']) ?>月<?php echo h($user_datas['birthday_d']) ?>日
              </td>
            </tr>
            <tr>
              <th>郵便番号</th>
              <td>
                <?php echo h($user_datas['post']) ?>
              </td>
            </tr>
            <tr>
              <th>都道府県</th>
              <td>
                <?php echo h($user_datas['pref']) ?>
              </td>
            </tr>
            <tr>
              <th>市区町村</th>
              <td>
                <?php echo h($user_datas['address1']) ?>
              </td>
            </tr>
            <tr>
              <th>町名・番地</th>
              <td>
                <?php echo h($user_datas['address2']) ?>
              </td>
            </tr>
            <tr>
              <th>建物名</th>
              <td>
                <?php echo h($user_datas['address3']) ?>
              </td>
            </tr>
            <tr>
              <th>電話番号1</th>
              <td>
                <?php echo h($user_datas['tel1']) ?>
              </td>
            </tr>
<?php if($user_datas['tel2']){ ?>
            <tr>
              <th>電話番号2</th>
              <td>
                <?php echo h($user_datas['tel2']) ?>
              </td>
            </tr>
<?php } ?>
            <tr>
              <th>職業</th>
              <td>
                <?php echo h($user_datas['works']) ?>
              </td>
            </tr>
            <tr>
              <th>業種</th>
              <td>
                <?php echo h($user_datas['works_type']) ?>
              </td>
            </tr>
            <tr>
              <th>最終学歴</th>
              <td>
                <?php echo h($user_datas['final_education']) ?>
              </td>
            </tr>
            <tr>
              <th>配偶者</th>
              <td>
                <?php echo h($user_datas['spouse']) ?>
              </td>
            </tr>
            <tr>
              <th>子ども</th>
              <td>
                <?php echo h($user_datas['childeren']) ?>
              </td>
            </tr>
            <tr>
              <th>同居人数(ご本人除く)</th>
              <td>
<?php if($user_datas['number_of_people_living_together'] > 0) {?>
                <?php echo h($user_datas['number_of_people_living_together']) ?>人
<?php } ?>
              </td>
            </tr>
            <tr>
              <th>自家用車</th>
              <td>
                <?php echo h($user_datas['private_car']) ?>
              </td>
            </tr>
            <tr>
              <th>自動車運転免許</th>
              <td>
                <?php echo h($user_datas['car_license']) ?>
              </td>
            </tr>
            <tr>
              <th>世帯年収(税込)</th>
              <td>
                <?php echo h($user_datas['householde_income']) ?>
              </td>
            </tr>
            <tr>
              <th>住居形態</th>
              <td>
                <?php echo h($user_datas['housing_form']) ?>
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
          <form action="exe" method="POST" style="margin:10px;">
            <input type="submit" value="登録する" class="c-form__button">
            <input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
<!-- <?php foreach ($user_datas as $key => $val) { ?>
            <input type="hidden" name="<?php echo h($key) ?>" value="<?php echo h($val) ?>">
<?php } ?> -->
          </form>
          <form action="index" method="POST" style="margin:10px;">
            <input type="submit" value="修正する" class="c-form__button">
            <input type="hidden" name="<?php echo h(CRYPT_PARAM) ?>" value="<?php echo h(encryptParam($requestData)) ?>">
<!-- <?php foreach ($user_datas as $key => $val) { ?>
            <input type="hidden" name="<?php echo h($key) ?>" value="<?php echo h($val) ?>">
<?php } ?> -->
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
  <script src="../../js/common.js"></script>
</body>

</html>