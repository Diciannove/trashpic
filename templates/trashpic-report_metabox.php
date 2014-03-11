<table class="form-table" cellpadding="0" cellspacing="0">
<tbody>

   <tr valign="top">
        <th >
            <label for="label"><?php echo _e('report_label','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <input type="text" id="label" name="label" value="<?php echo @get_post_meta($post->ID, 'label', true); ?>" />
        </td>
    </tr>
   <tr valign="top">
        <th >
            <label for="link"><?php echo _e('report_link','TRASHPIC-plugin')?></label>
        </th>
        <td >

        <?php 
        $val = @get_post_meta($post->ID, 'link', true);
        global $post;
        	$posts = get_posts(array(
					'post_type'   => 'trashpic-report',
					'post_status' => 'publish',
					'meta_query' => array( array('key' => 'approved','value'=>1)),
					'posts_per_page' => -1,
					'fields' => 'ids'
					)
				);
			echo "<select id='link' name='link'>";
			echo "<option value=''></option>";
			foreach($posts as $p){
				$title = get_the_title($p);
				$label = get_post_meta($p, 'label', true);
				if($val==$p) echo "<option selected value='".$p."'>".$title ." ".$label."</option>";
				else echo "<option value='".$p."'>".$title ." ".$label."</option>";
				
			}
			echo "</select>";
			
			?>
        
        </td>
    </tr>
    
    
    <tr><td colspan="2"><hr/></td></tr>
    
    
		<tr valign="top">
        <th >
            <label for="latitude"><?php echo _e('latitude','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <input type="text" id="latitude" name="latitude" value="<?php echo @get_post_meta($post->ID, 'latitude', true); ?>" />
        </td>
    </tr>
    
    
    
    <tr valign="top">
        <th >
            <label for="longitude"><?php echo _e('longitude','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <input type="text" id="longitude" name="longitude" value="<?php echo @get_post_meta($post->ID, 'longitude', true); ?>" />
        </td>
    </tr>
    <tr><td colspan="2"><hr/></td></tr>
    
    <tr valign="top">
        <th >
            <label for="category"><?php echo _e('category','TRASHPIC-plugin')?></label>
        </th>
        <td >
        		<?php 
        		global $trashpic_category;
        		$val = @get_post_meta($post->ID, 'category', true);
        		
        		echo "<select id='category' name='category'>";
        		echo "<option value=''></option>";
        		
        		foreach ($trashpic_category as $c=>$v){
							if($val==$c) echo "<option selected value='".$c."'>".$v."</option>";
							else echo "<option value='".$c."'>".$v."</option>";
						}
						echo "</select>";
        		?>
        </td>
    </tr>
    
    
    
    <tr><td colspan="2"><hr/></td></tr>
    
    
    
    <tr valign="top">
        <th >
            
        </th>
        <td >
        <?php
        $img = @get_post_meta($post->ID, 'picture', true);
        if($img['url']) {
						//echo $img['url'];
						echo "<img src='".$img['url']."' width='500' />";	
				}?>
        </td>
    </tr>
    <tr valign="top">
        <th >
            <label for="picture"><?php echo _e('picture','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <input type="file" id="picture" name="picture"  />
        </td>
    </tr>
    <tr><td colspan="2"><hr/></td></tr>
    <tr valign="top">
        <th >
            <label for="smile_phone"><?php echo _e('smile_phone','TRASHPIC-plugin')?></label>
        </th>
        <td >
            
            <input type="radio" id="smile_phone1" name="smile_phone" value="0"    <?php echo @get_post_meta($post->ID, 'smile_phone', true) == '0' ? ' checked="checked"' : '' ?>" />
            <label for="smile_phone1"><?php echo _e('no','TRASHPIC-plugin')?></label>
            <input type="radio" id="smile_phone2" name="smile_phone" value="1"    <?php echo @get_post_meta($post->ID, 'smile_phone', true) == '1' ? 'checked="checked"' : '' ?>" />
            <label for="smile_phone2"><?php echo _e('yes','TRASHPIC-plugin')?></label>
            
        </td>
    </tr>
    
    <tr valign="top">
        <th >
            <label for="longitude"><?php echo _e('investigated','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <input type="radio" id="investigated1" name="investigated" value="0"    <?php echo @get_post_meta($post->ID, 'investigated', true) == '0' ? ' checked="checked"' : '' ?>" />
            <label for="investigated1"><?php echo _e('no','TRASHPIC-plugin')?></label>
            <input type="radio" id="investigated2" name="investigated" value="1"    <?php echo @get_post_meta($post->ID, 'investigated', true) == '1' ? 'checked="checked"' : '' ?>" />
            <label for="investigated2"><?php echo _e('yes','TRASHPIC-plugin')?></label>
        </td>
    </tr>

    <tr valign="top">
        <th >
            <label for="public_note"><?php echo _e('public_note','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <textarea id="public_note" cols="46" colss="5" name="public_note"><?php echo @get_post_meta($post->ID, 'public_note', true); ?></textarea>
        </td>
    <tr>
    <tr><td colspan="2"><hr/></td></tr>
    <tr valign="top">
        <th >
            <label for="note"><?php echo _e('note','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <textarea id="note" cols="46" colss="10" name="note"><?php echo @get_post_meta($post->ID, 'note', true); ?></textarea>
        </td>
    <tr>
    <tr><td colspan="2"><hr/></td></tr>
    <tr valign="top">
        <th >
            <label for="approved"><?php echo _e('approved','TRASHPIC-plugin')?></label>
        </th>
        <td >
            <input type="radio" id="approved1" name="approved" value="0"    <?php echo @get_post_meta($post->ID, 'approved', true) == '0' ? ' checked="checked"' : '' ?>" />
            <label for="approved1"><?php echo _e('no','TRASHPIC-plugin')?></label>
            <input type="radio" id="approved2" name="approved" value="1"    <?php echo @get_post_meta($post->ID, 'approved', true) == '1' ? 'checked="checked"' : '' ?>" />
            <label for="approved2"><?php echo _e('yes','TRASHPIC-plugin')?></label>
         </td>
    </tr>
    </tbody>
</table>