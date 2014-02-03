<table class="trashpic_metabox_table" cellpadding="0" cellspacing="0">
    <tr valign="top">
    	<th colspan="2" class="trashpic_phase1">A</th>
    </tr>
    <tr valign="top">
        <th class="trashpic_phase1">
            <label for="latitude"><?echo _e('latitude','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase1">
            <input type="text" id="latitude" name="latitude" value="<?php echo @get_post_meta($post->ID, 'latitude', true); ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th class="trashpic_phase1">
            <label for="longitude"><?echo _e('longitude','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase1">
            <input type="text" id="longitude" name="longitude" value="<?php echo @get_post_meta($post->ID, 'longitude', true); ?>" />
        </td>
    </tr>
    <tr valign="top">
        <th class="trashpic_phase1">
            <label for="longitude"><?echo _e('picture','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase1">
        </td>
    </tr>
    
    <tr valign="top">
    	<th colspan="2" class="trashpic_phase2">B</th>
    </tr>
    <tr valign="top">
        <th class="trashpic_phase2">
            <label for="smile_phone"><?echo _e('smile_phone','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase2">
            
            <input type="radio" id="smile_phone1" name="smile_phone" value="0"    <?php echo @get_post_meta($post->ID, 'smile_phone', true) == '0' ? ' checked="checked"' : '' ?>" />
            <label for="smile_phone1"><?echo _e('no','TRASHPIC-plugin')?></label>
            <input type="radio" id="smile_phone2" name="smile_phone" value="1"    <?php echo @get_post_meta($post->ID, 'smile_phone', true) == '1' ? 'checked="checked"' : '' ?>" />
            <label for="smile_phone2"><?echo _e('yes','TRASHPIC-plugin')?></label>
            
        </td>
    </tr>
    
    <tr valign="top">
        <th class="trashpic_phase2">
            <label for="longitude"><?echo _e('investigated','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase2">
            <input type="radio" id="investigated1" name="investigated" value="0"    <?php echo @get_post_meta($post->ID, 'investigated', true) == '0' ? ' checked="checked"' : '' ?>" />
            <label for="investigated1"><?echo _e('no','TRASHPIC-plugin')?></label>
            <input type="radio" id="investigated2" name="investigated" value="1"    <?php echo @get_post_meta($post->ID, 'investigated', true) == '1' ? 'checked="checked"' : '' ?>" />
            <label for="investigated2"><?echo _e('yes','TRASHPIC-plugin')?></label>
        </td>
    </tr>
    <tr valign="top">
        <th class="trashpic_phase2">
            <label for="note"><?echo _e('note','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase2">
            <textarea id="note" cols="46" colss="10" name="note"><?php echo @get_post_meta($post->ID, 'note', true); ?></textarea>
        </td>
    <tr>
    <tr valign="top">
    	<th colspan="2" class="trashpic_phase3">C</th>
    </tr>
    <tr valign="top">
        <th class="trashpic_phase3">
            <label for="approved"><?echo _e('approved','TRASHPIC-plugin')?></label>
        </th>
        <td class="trashpic_phase3">
            <input type="radio" id="approved1" name="approved" value="0"    <?php echo @get_post_meta($post->ID, 'approved', true) == '0' ? ' checked="checked"' : '' ?>" />
            <label for="approved1"><?echo _e('no','TRASHPIC-plugin')?></label>
            <input type="radio" id="approved2" name="approved" value="1"    <?php echo @get_post_meta($post->ID, 'approved', true) == '1' ? 'checked="checked"' : '' ?>" />
            <label for="approved2"><?echo _e('yes','TRASHPIC-plugin')?></label>
         </td>
    </tr>
</table>