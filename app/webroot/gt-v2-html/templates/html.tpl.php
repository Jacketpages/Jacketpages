<!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>" <?php print $rdf_namespaces; ?>>
<head profile="<?php print $grddl_profile; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <!--[if lt IE 7]><div class="ie-sucks-wrapper ie7"><![endif]-->
  <!--[if lt IE 8]><div class="ie-sucks-wrapper ie8"><![endif]-->
  <!--[if lt IE 9]><div class="ie-sucks-wrapper ie9"><![endif]-->
  <p id="skip-links">
    <a href="#main" class="element-invisible element-focusable">Skip to content</a>
  </p>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
  <!--[if lt IE 7]></div><![endif]-->
  <!--[if lt IE 8]></div><![endif]-->
  <!--[if lt IE 9]></div><![endif]-->
</body>
</html>
