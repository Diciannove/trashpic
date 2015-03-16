<?php 

class JSON_API_Trashpic_Controller {
	
	/**
	 * 
	 * @return multitype:string
	 */
	public function hello_world() {
		return array(
				"message" => "Trashpic!"
		);
	}
	
	/**
	 * 
	 * @return multitype:number
	 */
	public function get_public_reports() {
		
		$posts = get_posts(array(
				'post_type'   => 'trashpic-report',
				'post_status' => 'publish',
				'meta_query' => array( array('key' => 'approved','value'=>1)),
				'posts_per_page' => -1,
				'fields' => 'ids'
				 )
		);
			
		foreach($posts as $p){
			$img  = @get_post_meta($p, 'picture', true);
			$link = @get_post_meta($p, 'link', true);
		
			if(!$link){
				$tpost[$p]["id"]  = $p;
				$tpost[$p]["date_creation"] = get_the_time( "d/m/Y", $p ); ;
				$tpost[$p]["lat"] = get_post_meta($p,"latitude",true);
				$tpost[$p]["lon"] = get_post_meta($p,"longitude",true);
				$tpost[$p]["solved"] = get_post_meta($p,"solved",true);
				$tpost[$p]["notified"] = get_post_meta($p,"notified",true);
				$tpost[$p]["img"] = $img['url'];
				$tpost[$p]['n']  += 1;
			} else $tpost[$link]['n']  += 1;
		}		
		
		return array(
				"points" => $tpost
		);
	}
	
	/**
	 * 
	 * @return multitype:mixed number
	 */
	public function get_map_parmas() {
		return array(
				'latitude' => str_replace(",", ".",get_trashpic_option( 'trashpic_default_latitude')) ,
				'longitude' => str_replace(",", ".",get_trashpic_option( 'trashpic_default_longitude')) ,
				'zoom' => get_trashpic_option( 'trashpic_default_zoom_level') ,
		);
	
	}
	

	/**
	 * 
	 * @return multitype:string
	 */
	public function send_report() 	{

		$postTitle   = date("YmdB").rand(10,100);

		$latitude    = trim(substr($_REQUEST['latitude'],0,8));
		$longitude   = trim(substr($_REQUEST['longitude'],0,7));
		$category    = $_REQUEST['category'];
		$public_note = $_REQUEST['public_note'];
		$userid = $_REQUEST['userid'];
		
		insert_trashpic_report($postTitle,$userid,$latitude,$longitude,$category,$public_note,$_FILES);
				
		
		return array(
				"status" => "ok",
		);
		
		
	}
}
?>