<?php
/**
 * @file field--horizontal-landing-page.tpl.php
 *
 * Default template implementation to display the value of a field in multipurpose pages.
 * (getting rid of the <div> overload)
 *
 */
?>
<?php foreach ($items as $delta => $item): ?>
  <?php print render($item); ?>
<?php endforeach; ?>