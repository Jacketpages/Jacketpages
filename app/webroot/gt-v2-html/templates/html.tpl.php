<!DOCTYPE html>
<!--[if lt IE 9]><html class="lt-ie9" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)|!(IE)]<!--> <html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>" <?php print $rdf_namespaces; ?>> <!--<![endif]-->

<head profile="<?php print $grddl_profile; ?>">
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <p id="skip-links">
    <a href="#main" class="element-invisible element-focusable">Skip to content</a>
  </p>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
