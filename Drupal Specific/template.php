<?php

/**
 * Implementation of HOOK_theme().
 */
function dtheme_theme(&$existing, $type, $theme, $path) {
  $dmodel_hooks = dmodel_theme($existing, $type, $theme, $path);
  $hooks = array(
   'dd_hover_help' => array(
     'arguments' => array('variables' => array()),
     'template' => 'dd-hover-help',
     'path' => $path . '/tpl',
   ),
 );
 return array_merge($dmodel_hooks, $hooks);
}

/**
 * Implements hook_preprocess_TEMPLATE().
 */
function dtheme_preprocess_page(&$vars) {
  // Define environment.
  $devMode = file_exists(dirname(__FILE__) . '/DEV');
  $vars['devMode'] = $devMode;

  // Cache breaker to aid development and troubleshooting live sites.
  $vars['cache_breaker'] = $devMode ? time() : substr(variable_get('css_js_query_string', '0'), 0, 1);


  // Define theme helpers.
  $theme_base_url = $vars['base_path'] . $vars['directory'];
  $vars['theme_base_url'] = $theme_base_url;

  // Styles & Scripts
  if ($devMode) {
    $vars['theme_styles'] = '/dist/theme.css?' . $vars['cache_breaker'];
    $vars['theme_script'] = '/dist/theme.js?' . $vars['cache_breaker'];
    $vars['theme_vendor'] = '/dist/vendors~theme.js?' . $vars['cache_breaker'];
  } else {
    $vars['theme_styles'] = $theme_base_url . '/dist/theme.min.css?' . $vars['cache_breaker'];
    $vars['theme_script'] = $theme_base_url . '/dist/theme.min.js?' . $vars['cache_breaker'];
    $vars['theme_vendor'] = $theme_base_url . '/dist/vendors~theme.min.js?' . $vars['cache_breaker'];
  }

  // Transform some region variables to make more sense within the page template.
  $vars['flyout'] = $vars['very_top'];
  $vars['navigation'] = $vars['navbar'];

  // Process special handling of blocks and classes within microsite.
  $vars['microsite'] = FALSE;
  $vars['logo_src'] = $theme_base_url . '/img/logo.svg';
  $vars['site_name'] = $vars['site_name'] . ' home page';

  if (module_exists('dd_navigation')) {
    $root_tid = dd_navigation_active_root_tid();
    $root_name = taxonomy_get_term($root_tid)->name;

    if ($root_tid != 0) {
      $vars['microsite'] = TRUE;
      $vars['site_name'] = $root_name . ' home page';
      $vars['logo_src'] = $theme_base_url . '/img/logo-' . $root_tid . '.svg';

      // Set home pathway.
      $path = 'taxonomy/term/' . $root_tid;

      if (module_exists('dd_normalize')) {
        $path = dd_normalize_uri($path);
      }

      $vars['front_page'] = $path;
    }
  }

  // Overcome the 'Home' page title to actually use what has been configured
  // within the term settings.
  if ($vars['is_front']) {
    $tid = arg(2);
    $term = taxonomy_get_term($tid);
    $vars['title'] = $term->name;
  }

  // Unset page title variable if the 'content_before' region is populated.
  // We're only using this region for the overview block so we should be safe
  // to unset the standard page title which will in-turn eliminate
  // the duplicate h1 tag issue.
  if (!empty($vars['content_before'])) {
    $vars['title'] = NULL;
  }

  // Unset page title variable on all full post displays since it causes
  // duplicate h1 tags.
  if (dd_is_node_page()) {
    $vars['title'] = NULL;
  }

  // Let's just rename content_before to overview since we're making that
  // assumption.
  $vars['overview'] = $vars['content_before'];
}

/**
 * Implementation of HOOK_preprocess_block().
 */
function dtheme_preprocess_block(&$vars, $hook) {
  $block = $vars['block'];
  $custom_classes = array('block');

  // Check to see if a custom template has been configured for this display.
  if ($template = dtheme_views_add_custom_template($block->delta, 'block')) {
    $vars['template_files'][] = $template;
  }

  // Add a special template for any custom blocks.
  if ($block->module === 'block') {
    if ($template = dtheme_views_add_custom_template('block-' . $block->delta, 'block')) {
      $vars['template_files'][] = $template;
    }
  }

  // Add a special template for our main navigation block.
  if ($block->module === 'nice_menus' && $block->delta === '3') {
    $vars['template_files'][] = 'block--main-navigation';
  }

  // Add a special template for our term navigation block.
  if ($block->module === 'menu_block' && $block->delta === '2') {
    $vars['template_files'][] = 'block--term-navigation';
  }

  // Add a special template for our main navigation block.
  if ($block->module === 'digitaldcore' && $block->delta === 'search') {
    $vars['template_files'][] = 'block--search';
  }

  // Add a special template for the GTranslate block.
  if ($block->module === 'gtranslate') {
    $vars['template_files'][] = 'block--gtranslate';
  }

  // Add custom classes for certina block displays.
  $options = array($block->delta, $block->module . '-' . $block->delta);
  foreach($options as $option) {
    if ($config = dtheme_custom_template_configuration($option)) {
      array_unshift($custom_classes, $config['block-classes']); break;
    }
  }

  // Set placeholder-block status if it's set.
  $aClasses = explode(' ', $block->views_blocks_classes);
  if (in_array('placeholder-block', $aClasses)) {
    $custom_classes[] = 'placeholder-block';
  }

  // Add ability to alter block editing variables within this theme.
  if (user_access('administer blocks') || user_access('reorder views blocks')) {
    $custom_classes[] = 'with-block-editing';
    dtheme_preprocess_block_editing($vars, $hook);
  }

  // Convert custom classes array into classname string.
  $vars['custom_classes'] = implode(' ', $custom_classes);
}

/**
 * Implementation of HOOK_preprocess_menu_block_wrapper().
 */
function dtheme_preprocess_block_editing(&$vars, $hook) {
  $block = $vars['block'];
  $block_name = NULL;

  // Add an appropriate name for the main navigation block.
  if ($block->module === 'nice_menus' && $block->delta === '3') {
    $block_name = 'Main Navigation';
  }

  // Add an appropriate name for the term navigation block.
  if ($block->module === 'menu_block' && $block->delta === '2') {
    $block_name = 'Term Navigation';
  }

  // Set this new block names instead.
  if ($block_name && $vars['edit_links_array']) {
    $vars['edit_links'] = '<div class="block-controls-wrapper"><div class="block-name">' . $block_name . '</div><div class="block-controls">' . implode(' ', $vars['edit_links_array']) . '</div></div>';
  }
}

/**
 * Implementation of HOOK_preprocess_menu_block_wrapper().
 */
function dtheme_preprocess_menu_block_wrapper(&$vars) {
  if ($vars['delta'] === '2') {
    $vars['template_files'][] = 'block--term-navigation-wrapper';
    $vars['content'] = preg_replace('%<ul(.*?)>%', '<ul id="term-navigation-menu" class="menu" role="menu" aria-label="Submenu options" tabindex="-1">', $vars['content'], 1);
  }
}

/**
 * Implementation of HOOK_preprocess_node().
 */
function dtheme_preprocess_node(&$vars) {
  $node = $vars['node'];
  $view = $vars['view'];
  $tag = $vars['teaser'] ? 'teaser' : 'full';
  $custom_classes = array('node');
  $build_mode = $node->build_mode;
  $delta = $view->name . '-' . $view->current_display;

  // Add a node template file based on the tag, similar to what
  // views_theme_functions() does for the view itself.
  if (!empty($node->tag)) {
    $tag = $node->tag;
  }

  // Rewrite the node sidebar stuff because it's a mess.
  if ($build_mode === 'sidebar' && $tag === 'full') {
    $tag = $build_mode;
  }

  // Define a custom template file based on the views_blocks block
  // delta which represents specific types of blocks.
  if (!empty($view->_views_blocks_delta)) {
    $delta = $view->_views_blocks_delta;
  }

  // Define our custom template file based on the delta value, if set.
  if ($config = dtheme_custom_template_configuration($delta)) {
    $tag = $config['node'];
    $custom_classes[] = 'node-' . $tag;
    $context_classes = dd_classes_for_element_in_order('node-' . $node->nid . '-teaser', array('global'));
    $custom_classes = array_merge($custom_classes, $context_classes);
    array_unshift($custom_classes, $config['node-classes']);

    if ($config['custom-image-preset']) {
      // Preprocess this image with the preset specified.
      $vars['image'] = dtheme_render_image($node->field_image[0], $config['custom-image-preset']);
      if ($tag === 'home-kids') {
        $vars['image_2'] = dtheme_render_image($node->field_image[1], $config['custom-image-preset']);
      }

      // Provides an option within the DD Hover flyout to manually crop this image display.
      $vars['imagecache_preset'] = $config['custom-image-preset'];
    }

    $more_text = $node->field_readmore_text[0]['value'];
    $vars['more_text'] = !empty($more_text) ? $more_text : 'Read more';
  }

  // Register the potential template option.
  $vars['template_files'][] = 'node--' . $tag;

  // Add the post type id which is helpful in many situations.
  $custom_classes[] = 'pt' . $node->pt['term']->tid;

  // Helps custom templates retain dd_hover functionality.
  if (user_access('access dd_hover')) {
    $custom_classes[] = 'dd-hover-help ddHover-processed';
    $vars['dd_hover_help'] = theme('dd_hover_help', $vars);
  }

  // Helps custom templates retain unpublished state.
  if (!$vars['status']) {
    $custom_classes[] = 'node-unpublished';
  }

  // Convert custom classes array into classname string.
  $vars['custom_classes'] = implode(' ', $custom_classes);
}

/**
 * Implementation of HOOK_preprocess_views_view().
 */
function dtheme_preprocess_views_view(&$vars) {
  $view = $vars['view'];

  // Check to see if a custom template has been configured for this display.
  if ($template = dtheme_views_add_custom_template($view, 'views-view')) {
    $vars['template_files'][] = $template;
  }

  // Simplify the classes past to our calendar feed view
  if ($view->name === 'calendar_feed') {
    $vars['classes'] = isset($vars['css_class']) ? $vars['css_class'] : 'calendar-feed';
  }
}

/**
 * Implementation of HOOK_preprocess_views_view_unformatted().
 */
function dtheme_preprocess_views_view_unformatted(&$vars) {
  // Helps custom templates retain dd_hover functionality.
  if (user_access('access dd_hover')) {
    $vars['add_dd_hover_help'] = TRUE;
  }

  // Check to see if a custom template has been configured for this display.
  if ($template = dtheme_views_add_custom_template($vars['view'], 'views-view-unformatted')) {
    $vars['template_files'][] = $template;
  }
}

/**
 * Implementation of HOOK_preprocess_views_view_fields().
 */
function dtheme_preprocess_views_view_fields(&$vars) {
  $view = $vars['view'];
  $fields = $vars['fields'];

  // Check to see if a custom template has been configured for this display.
  if ($template = dtheme_views_add_custom_template($view, 'views-view-fields')) {
    $vars['template_files'][] = $template;
  }

  // Prepare the variables for the calendar feed display
  if ($view->name === 'calendar_feed') {
    $row = $vars['row'];
    $classes = array('node');
    $fields = $vars['fields'];

    // Set the associated terms this post is and tagged to
    if ($node = node_load($row->nid)) {
      // Add the post type id to classes
      $classes[] = 'pt' . $node->pt['term']->tid;
      $vars['post_type'] = $node->pt['name'];

      // Add all the related terms
      if (function_exists('ddt_term_classes')) {
        $taxonomy_classes = trim(ddt_term_classes($node->taxonomy));
        $classes = array_merge($classes, explode(' ', $taxonomy_classes));
      }
    }

    // Create the classes string from the array we've been adding to
    $vars['classes'] = implode(' ', $classes);

    // Create a node edit link for administration on the calendar page
    if (user_access('edit any post content')) {
      $vars['edit_link_path'] = '/node/' . $row->nid . '/edit';
    }

    // Sets the destination of the post
    if (module_exists('dd_create')) {
      $vars['destination'] = dd_normalize_uri('node/' . $row->nid);
    }

    // Sets the title and subtitle headings
    $vars['title'] = $fields['title']->content;
    $vars['subtitle'] = $fields['field_subtitle_value']->content;

    // Sets the date to be displayed
    $vars['date'] = $fields['field_date_value']->content;

    // Datetimes
    $vars['start_date'] = $fields['field_date_value_1']->content;
    $vars['end_date'] = $fields['field_date_value2']->content;

    // Set the range flag if dates are different
    if ($vars['start_date'] != $vars['end_date']) {
      $vars['range'] = TRUE;
    }
  }
}

/**
 * Preprocess variables for the dd hover help template.
 */
function dtheme_preprocess_dd_hover_help(&$vars) {
  $vars = $vars['variables'];
}

/**
 * Helper function which creates an image tag powered by imagecache.
 */
function dtheme_render_image($field_image, $preset = 'thumbnail') {
  require_once(__DIR__ . '/template.ext.inc');
  return _dtheme_render_image($field_image, $preset);
}

/**
 * Helper function which defines customized display templates.
 */
function dtheme_views_add_custom_template($view, $display) {
  require_once(__DIR__ . '/template.ext.inc');
  return _dtheme_views_add_custom_template($view, $display);
}

/**
 * Returns a view tag which can be used with preprocessors
 * in order to define custom templates.
 */
function dtheme_custom_template_configuration($delta) {
  require_once(__DIR__ . '/template.ext.inc');
  return _dtheme_custom_template_configuration($delta);
}
