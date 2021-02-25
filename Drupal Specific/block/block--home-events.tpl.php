<div class="<?php print $custom_classes ?>">
  <div class="block-inner">
    <?php print $edit_links; ?>
    <?php if (!empty($block->subject)): ?>
    <p class="block-title h2"><?php print $block->subject; ?></p>
    <?php endif; ?>
    <div class="block-content">
      <?php print $block->content; ?>
    </div>
    <div class="block-footer">
      <button class="btn-secondary">View Full Calendar</button>
    </div>
  </div>
</div>
