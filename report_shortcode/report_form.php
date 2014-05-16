<div class="form_external">
  <?php do_action( 'trashpic-notice' ); ?>
  <?php do_action( 'trashpic-error' ); ?>
	<form action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
		<fieldset class="gllpLatlonPicker">
   	  <table style="width:100%">
   	  	<tr>
   	  		<td><label for="latitude"><?php _e('latitude', 'TRASHPIC-plugin') ?></label></td>
   	  		<td><input id="latitude" name="latitude"   type="text" class="gllpLatitude"/></td>
   	  		<td style="width:60%"><label for="public_note"><?php echo _e('public_note','TRASHPIC-plugin')?></label></td>
   	  	</tr>
   	  	<tr>
   	  		<td><label for="longitude"><?php _e('longitude', 'TRASHPIC-plugin') ?></label></td>
   	  		<td><input id="longitude" name="longitude" type="text" class="gllpLongitude"/></td>
   	  		<td rowspan="3">
      					<textarea style="width:100%;height: 100%;" id="public_note" cols="46" rows="4"  name="public_note"></textarea>
   	  		</td>
   	  	</tr>
   	  	<tr>
   	  		<td><label for="category"><?php _e('category_public_form', 'TRASHPIC-plugin') ?></label></td>
   	  		<td>		        		<?php 
		        		global $trashpic_category;
		        		
		        		echo "<select id='category' name='category'>";
		        		echo "<option value=''></option>";
		        		foreach ($trashpic_category as $c=>$v){
									 echo "<option value='".$c."'>".$v."</option>";
								}
								echo "</select>";
		        		?>
   	  		</td>
   	  	</tr>
   	  	<tr>
   	  		<td><label for="files"><?php _e('picture', 'TRASHPIC-plugin') ?></label></td>
   	  		<td><input type="file" name="picture" /></td>
   	  	</tr>
   	  	<tr>
   	  		<td colspan="3">			<br/>
   	  		<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
					<input type="hidden" name="submitted" id="submitted" value="true" />
					<input type="hidden" name="action" value="trashpic_report" />
					<button type="submit"><?php _e('send_report', 'TRASHPIC-plugin') ?></button>
   	  		</td>
   	  	</tr>
   	  	
   	  </table>
   	  <hr/>
   	  <div class="gllpMap olMap"></div>
		  <input type="hidden" class="gllpZoom"/>
		  <input type="hidden" class="gllpLocationName"/>
			</fieldset>
	</form>
</div>