<div class="<?php print $custom_classes ?>">
  <?php if ($upper_meta): ?>
  <div class="node-upper-meta meta node-item">
    <div class='meta-inner'>
      <?php print $upper_meta; ?>
    </div>
  </div>
  <?php elseif (!$upper_meta && $post_type): ?>
  <div class="node-upper-meta meta node-item">
    <div class="meta-inner">
      <span class="post-type"><?php print $post_type; ?></span>
    </div>
  </div>
  <?php endif; ?>
  <header class="node-header">
    <h2 class="title"><a href="<?php print check_url($node_url); ?>"><?php print $title; ?></a></h2>
    <?php if ($subtitle): ?><p class="subtitle"><?php print $subtitle; ?></p><?php endif; ?>
    <a class="more" href="<?php print check_url($node_url); ?>"><?php print $more_text ?></a>
  </header>
  <?php if ($dd_hover_help) print $dd_hover_help; ?>
</div>
