<?php
/**
 * @file
 * Generates variables for GT Tools content types.
 */

// Horizontal landing page block row variables
if ($node->type == 'horizontal_landing_page') {
  
  !empty($node->field_body_1) ? $variables['body_1'] = TRUE : $variables['body_1'] = FALSE; 
  if (!empty($node->field_block_row_1)) {
    $block_count = count($node->field_block_row_1['und']);
    $block_count > 3 ? $variables['block_row_1'] = '4' : $variables['block_row_1'] = $block_count;
  } else {
    $variables['block_row_1'] = '';
  }
  if (!empty($node->field_block_row_2)) {
    $block_count = count($node->field_block_row_2['und']);
    $block_count > 3 ? $variables['block_row_2'] = '4' : $variables['block_row_2'] = $block_count;
  } else {
    $variables['block_row_2'] = '';
  }
  if (!empty($node->field_block_row_3)) {
    $block_count = count($node->field_block_row_3['und']);
    $block_count > 3 ? $variables['block_row_3'] = '4' : $variables['block_row_3'] = $block_count;
  } else {
    $variables['block_row_3'] = '';
  }
  if (!empty($node->field_block_row_4)) {
    $block_count = count($node->field_block_row_4['und']);
    $block_count > 3 ? $variables['block_row_4'] = '4' : $variables['block_row_4'] = $block_count;
  } else {
    $variables['block_row_4'] = '';
  }
  
}

// Multipurpose page article row variables
if ($node->type == 'multipurpose_page') {
  
  // Row 1 body (flag), articles (count), & aside (count)
  !empty($node->field_body_1) ? $variables['body_1'] = TRUE : $variables['body_1'] = FALSE; 
  if (!empty($node->field_articles_1)) {
    $block_count = count($node->field_articles_1['und']);
    $block_count > 3 ? $variables['articles_1'] = '4' : $variables['articles_1'] = $block_count;
  } else {
    $variables['articles_1'] = '';
  }
  if (!empty($node->field_aside_1)) {
    $block_count = count($node->field_aside_1['und']);
    $block_count > 3 ? $variables['aside_1'] = '4' : $variables['aside_1'] = $block_count;
  } else {
    $variables['aside_1'] = '';
  }
  
  // Row 2 body (flag), articles (count), & aside (count)
  !empty($node->field_body_2) ? $variables['body_2'] = TRUE : $variables['body_2'] = FALSE;
  if (!empty($node->field_articles_2)) {
    $block_count = count($node->field_articles_2['und']);
    $block_count > 3 ? $variables['articles_2'] = '4' : $variables['articles_2'] = $block_count;
  } else {
    $variables['articles_2'] = '';
  }
  if (!empty($node->field_aside_2)) {
    $block_count = count($node->field_aside_2['und']);
    $block_count > 3 ? $variables['aside_2'] = '4' : $variables['aside_2'] = $block_count;
  } else {
    $variables['aside_2'] = '';
  }
  
  // Row 3 body (flag), articles (count), & aside (count)
  !empty($node->field_body_3) ? $variables['body_3'] = TRUE : $variables['body_3'] = FALSE;
  if (!empty($node->field_articles_3)) {
    $block_count = count($node->field_articles_3['und']);
    $block_count > 3 ? $variables['articles_3'] = '4' : $variables['articles_3'] = $block_count;
  } else {
    $variables['articles_3'] = '';
  }
  if (!empty($node->field_aside_3)) {
    $block_count = count($node->field_aside_3['und']);
    $block_count > 3 ? $variables['aside_3'] = '4' : $variables['aside_3'] = $block_count;
  } else {
    $variables['aside_3'] = '';
  }
  
}

// Multipurpose page article row variables
if ($node->type == 'vertical_landing_page') {

  !empty($node->field_body_1) ? $variables['body_1'] = TRUE : $variables['body_1'] = FALSE;

  if (!empty($node->field_column_left)) {
    $block_count = count($node->field_column_left['und']);
    $block_count > 3 ? $variables['column_left'] = '4' : $variables['column_left'] = $block_count;
  } else {
    $variables['column_left'] = '';
  }
  
  if (!empty($node->field_column_right)) {
    $block_count = count($node->field_column_right['und']);
    $block_count > 3 ? $variables['column_right'] = '4' : $variables['column_right'] = $block_count;
  } else {
    $variables['column_right'] = '';
  }

}

