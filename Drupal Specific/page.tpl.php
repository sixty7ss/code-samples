<!DOCTYPE html>
<html lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>" prefix="og: http://ogp.me/ns#">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="apple-touch-icon" sizes="180x180" href="/sites/default/themes/dtheme/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/sites/default/themes/dtheme/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/sites/default/themes/dtheme/icons/favicon-16x16.png">
  <link rel="manifest" href="/sites/default/themes/dtheme/icons/site.webmanifest">
  <link rel="mask-icon" href="/sites/default/themes/dtheme/icons/safari-pinned-tab.svg" color="#5bbad5">
  <link rel="shortcut icon" href="/sites/default/themes/dtheme/icons/favicon.ico">
  <meta name="msapplication-TileColor" content="#008cd0">
  <meta name="msapplication-TileImage" content="/sites/default/themes/dtheme/icons/mstile-144x144.png">
  <meta name="msapplication-config" content="/sites/default/themes/dtheme/icons/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">

  <title><?php print $head_title; ?></title>
  <link rel="shortcut icon" href="/sites/default/themes/dtheme/icons/favicon.ico">
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print variable_get("dd_script_head", ""); ?>
  <?php if (!$is_admin): print variable_get("dd_script_head_non_admins", ""); endif; ?>
  <link type="text/css" rel="stylesheet" media="all" href="<?php print $theme_styles; ?>">
</head>
<body class="<?php print $body_classes; ?>">
  <?php print variable_get('dd_html_body_first_child', ''); ?>
  <div class="a11y_quicklinks">
    <a class="sr-only sr-only-focusable" href="#content"><?php print t('Skip to main content'); ?></a>
  </div>
  <div class="page">
    <header class="page-header headroom" role="banner">
      <div class="inner">
        <div class="header">
          <div class="branding">
            <div class="logo">
              <a href="<?php print $front_page; ?>" aria-label="Back to <?php print $site_name ?>">
                <img src="<?php print $logo_src ?>" alt="<?php print $site_name; ?>">
              </a>
            </div>
            <?php if ($site_slogan): ?>
              <div class="slogan"><?php print $site_slogan; ?></div>
            <?php endif; ?>
            <?php if ($mission): ?>
              <div class="mission"><?php print $mission; ?></div>
            <?php endif; ?>
          </div>
          <?php if ($header) print $header ?>
          <div class="toolbar">
            <button class="menu-icon" aria-controls="flyout" aria-haspopup="true" aria-label="Toggle flyout menu" aria-expanded="false">Menu</button>
          </div>
        </div>
        <div class="flyout" tabindex="-1" aria-label="Flyout containing quicklinks, site search, and main navigation" id="flyout">
          <nav class="primary-navigation" id="navigation" tabindex="-1" aria-label="Main"><?php print $navigation; ?></nav>
          <?php if ($flyout) print $flyout ?>
        </div>
      </div>
      <?php if ($search) print $search ?>
    </header>

    <main class="page-main" role="main">
      <?php if ($content_top): ?>
      <div class="page-top">
        <?php print $content_top ?>
      </div>
      <?php endif; ?>

      <div class="page-center">
        <?php if (!empty($left)): ?>
        <aside class="page-left" role="complementary" aria-label="Left Sidebar">
          <div class="page-sidebar">
            <?php print $left; ?>
          </div>
        </aside>
        <?php endif; ?>

        <section id="content" class="page-content" role="region" aria-labelledby="page-title" tabindex="-1">
          <?php if ($title): ?><h1 class="title<?php if ($overview || $is_front) print ' sr-only' ?>" id="page-title"><?php print $title; ?></h1><?php endif; ?>
          <?php if ($tabs): ?><div class="tabs"><?php print $tabs; ?></div><?php endif; ?>
          <?php if ($help) print $help ?>
          <?php print $overview ?>
          <?php print $content; ?>
          <?php if ($home_1): ?>
            <div class="home-page-one">
              <div class="inner">
                <?php print $home_1; ?>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($home_2): ?>
            <div class="home-page-two">
              <div class="layout">
                <div class="inner">
                  <?php print $home_2; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($home_3): ?>
            <div class="home-page-three">
              <div class="layout">
                <div class="inner">
                  <?php print $home_3; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($home_4): ?>
            <div class="home-page-four">
              <div class="layout">
                <div class="inner">
                  <?php print $home_4; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php print $content_after ?>
          <?php if ($feed_icons): ?><div class="feed-icons"><?php print $feed_icons; ?></div><?php endif; ?>
        </section>

        <?php if (!empty($right)): ?>
        <aside class="page-right" role="complementary" aria-label="Right Sidebar">
          <div class="page-sidebar">
            <?php print $right; ?>
          </div>
        </aside>
        <?php endif; ?>
      </div>

      <?php if (!empty($content_bottom)): ?>
      <div class="page-bottom">
        <div class="layout">
          <?php print $content_bottom; ?>
        </div>
      </div>
      <?php endif; ?>
    </main>

    <footer class="page-footer" role="contentinfo">
      <div class="layout">
        <div class="inner">
          <?php if ($footer_1): ?>
          <div class="footer-1">
            <?php print $footer_1 ?>
          </div>
          <?php endif; ?>

          <?php if ($footer_2): ?>
          <div class="footer-2">
            <?php print $footer_2 ?>
          </div>
          <?php endif; ?>

          <?php if ($footer_3): ?>
          <div class="footer-3">
            <?php print $footer_3 ?>
          </div>
          <?php endif; ?>
        </div>
        <?php if ($footer_4) print $footer_4; ?>
      </div>
    </footer>
  </div>

  <?php if ($messages): ?>
  <div class="page-messages" role="alert">
    <?php print $messages ?>
  </div>
  <?php endif; ?>

  <div class="page-closure">
    <?php print $scripts; ?>
    <?php print $dd_scripts; ?>
    <?php print variable_get("dd_script_footer", ""); ?>
    <?php print $closure_region; ?>
    <?php print $closure; ?>
    <script src="<?php print $theme_vendor ?>" async></script>
    <script src="<?php print $theme_script ?>" async></script>
  </div>

  </body>
</html>
