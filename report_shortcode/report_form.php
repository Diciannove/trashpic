<div class="form_external">
  <?php do_action( 'trashpic-notice' ); ?>
  <?php do_action( 'trashpic-error' ); ?>
	<form action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
		<fieldset class="gllpLatlonPicker">
   	  <div class="gllpMap olMap"></div>
   	  <br/>
   	  <label for="latitude"><?php _e('latitude', 'TRASHPIC-plugin') ?></label>
   	  <input id="latitude" name="latitude"   type="text" class="gllpLatitude"/>
   	  <label for="longitude"><?php _e('longitude', 'TRASHPIC-plugin') ?></label>
   	  <input id="longitude" name="longitude" type="text" class="gllpLongitude"/>
   	  <input type="hidden" class="gllpZoom"/>
   	  <input type="hidden" class="gllpLocationName"/>
								
 		</fieldset>
		<fieldset>
   	  <label for="category"><?php _e('category', 'TRASHPIC-plugin') ?></label>
        		<?php 
        		global $trashpic_category;
        		
        		echo "<select id='category' name='category'>";
        		echo "<option value=''></option>";
        		foreach ($trashpic_category as $c=>$v){
							 echo "<option value='".$c."'>".$v."</option>";
						}
						echo "</select>";
        		?>
   	  
 		</fieldset>
		<fieldset>
			<label for="files"><?php _e('picture', 'TRASHPIC-plugin') ?></label>
			
			<input type="file" name="picture" />
		</fieldset>
		
		<fieldset>
		  <label for="public_note"><?php echo _e('public_note','TRASHPIC-plugin')?></label>
      <textarea id="public_note" cols="46" colss="5" name="public_note"></textarea>
		</fieldset>
		
		
		<fieldset>
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
			<input type="hidden" name="action" value="trashpic_report" />
			<button type="submit"><?php _e('send_report', 'TRASHPIC-plugin') ?></button>
		</fieldset>
	</form>
</div>