<div class="wrap">
    <h2><?php echo _e('trashpic_setting_title','TRASHPIC-plugin')?></h2>
    
    <div>
    <h3>Shorcode disponibili</h3>
    Utilizzare [trashpic_report] per visualizzare il form di invio segnalazione<br/><br/>
    Utilizzare [trashpic_map] per visualizzare la mappa delle segnalazioni approvate
    
    </div>
    
    <form method="post" action="options.php">
        <?php @settings_fields('trashpic-group'); ?>
				<?php @do_settings_fields('trashpic-group'); ?>
				<?php do_settings_sections('trashpic'); ?>
				<?php @submit_button(); ?>
</form>
</div>