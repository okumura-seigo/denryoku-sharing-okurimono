<div class="l-header__contents">
  <div class="l-header__logo">
    <a href="<?php echo h(HOME_URL) ?>"><img src="<?php echo h(HOME_URL) ?>images/logo.png" alt="電力シェアリング"></a>
  </div>
<?php if (!isset($infoLoginUser['user_id'])) { ?>
  <nav class="l-global-nav">
    <ul class="l-global-nav__inner">
      <li class="l-global-nav__item">
        <a href="<?php echo h(HOME_URL) ?>about/" class="l-global-nav__link">
          <i class="fas fa-angle-right"></i>
          会員プログラムとは
        </a>
      </li>
      <li class="l-global-nav__item">
        <a href="<?php echo h(HOME_URL) ?>contact/" class="l-global-nav__link">
          <i class="fas fa-angle-right"></i>
          お問い合わせ
        </a>
      </li>
      <li class="l-global-nav__item">
        <a href="<?php echo h(HOME_URL) ?>sign-up/" class="l-global-nav__button">新規会員登録</a>
      </li>
    </ul>
  </nav>
<?php } else { ?>
  <nav class="l-global-nav">
    <ul class="l-global-nav__inner">
      <li class="l-global-nav__item">
        <a href="<?php echo h(HOME_URL) ?>about/" class="l-global-nav__link">
          <i class="fas fa-angle-right"></i>
          会員プログラムとは
        </a>
      </li>
      <li class="l-global-nav__item">
        <a href="<?php echo h(HOME_URL) ?>mypage/contact/" class="l-global-nav__link">
          <i class="fas fa-angle-right"></i>
          お問い合わせ
        </a>
      </li>
      <li class="l-global-nav__item">
        <div class="l-global-nav__hover">
          <a href="<?php echo h(HOME_URL) ?>mypage/" class="l-global-nav__button">
            マイページ
          </a>
          <ul class="l-global-nav__dropdown">
            <!-- <li class="l-global-nav__dropdown-item">
              <a href="<?php echo h(HOME_URL) ?>mypage/profile/" class="l-global-nav__dropdown-link">会員情報</a>
            </li> -->
            <li class="l-global-nav__dropdown-item">
              <a href="<?php echo h(HOME_URL) ?>mypage/logout" class="l-global-nav__dropdown-link">ログアウト</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
  </nav>
<?php } ?>
  <span class="l-global-nav__bg js-nav-close"></span>
  <span class="l-global-nav__btn-close js-nav-close">
    <i class="fas fa-times"></i>
  </span>
  <span class="l-global-nav__btn js-nav-open">
    <i class="fas fa-bars"></i>
  </span>
</div>