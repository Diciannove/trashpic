<div class="wrap">
    <h2><?php echo _e('trashpic_setting_title','TRASHPIC-plugin')?></h2>
    <form method="post" action="options.php">
        <?php @settings_fields('trashpic-group'); ?>
				<?php @do_settings_fields('trashpic-group'); ?>
				<?php do_settings_sections('trashpic'); ?>
				<?php @submit_button(); ?>
</form>
</div>