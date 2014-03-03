<div class="form_external">
  <?php do_action( 'trashpic-notice' ); ?>
  <?php do_action( 'trashpic-error' ); ?>
	<form action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
		<fieldset class="gllpLatlonPicker">
   	  <div class="gllpMap olMap"></div>
   	  <br/>
   	  <label for="latitude"><?php esc_html_e('latitude', 'TRASHPIC-plugin') ?></label>
   	  <input id="latitude" name="latitude"   type="text" class="gllpLatitude"/>
   	  <label for="longitude"><?php _e('longitude', 'TRASHPIC-plugin') ?></label>
   	  <input id="longitude" name="longitude" type="text" class="gllpLongitude"/>
   	  <input type="hidden" class="gllpZoom"/>
   	  <input type="hidden" class="gllpLocationName"/>
			<?php if($latitudeError != '') { ?>
			<br/>
			<span class="error"><?php echo $latitudeError; ?></span>
			<div class="clearfix"></div>
			<?php } ?>
			<?php if($longitudeError != '') { ?>
			<br/>
			<span class="error"><?php echo $longitudeError; ?></span>
			<div class="clearfix"></div>
			<?php } ?>
								
 		</fieldset>
					
		<fieldset>
			<label for="files"><?php _e('picture', 'framework') ?></label>
			<input type="file" name="picture" />
		</fieldset>
		<fieldset>
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<input type="hidden" name="action" value="trashpic_report" />
			<button type="submit"><?php _e('send_report', 'TRASHPIC-plugin') ?></button>
		</fieldset>
	</form>
</div>