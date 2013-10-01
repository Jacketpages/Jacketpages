<?php

/**
 * @file
 * Default GT theme implementation to display a block.
 *
 */
?>
<div id="<?php print $block_html_id; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if ($block->subject): ?>
    <div class="block-title-wrapper">
      <h4<?php print $title_attributes; ?>><?php print $block->subject ?></h4>
    </div>
  <?php endif;?>
  <?php print render($title_suffix); ?>

  <div class="content block-body clearfix"<?php print $content_attributes; ?>>
    <?php print $content ?>
  </div>
  
</div>
