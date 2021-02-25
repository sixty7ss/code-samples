<div class="<?php print $custom_classes ?>">
  <?php if (!empty($image)): ?>
  <div class="node-image">
    <div class="field-image">
      <?php print $image; ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="node-inner">
    <header class="node-header">
      <h2 class="title"><?php print $title; ?></h2>
      <?php if ($subtitle): ?><p class="subtitle"><?php print $subtitle ?></p><?php endif; ?>
    </header>
    <?php if ($links): ?>
    <div class='node-links node-item'>
      <?php print $links; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php if ($dd_hover_help) print $dd_hover_help; ?>
</div>
