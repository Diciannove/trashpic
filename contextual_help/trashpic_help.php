<?php
class trashpic_help {

	/**
	 * The assoc key represents the ID
	 * It is NOT allowed to contain spaces
	 * @var unknown
	 */
	public $tabs = array(
			'PAG1' => array('title'   => 'Sommario' ,'content' => 'file1.html'),
			'PAG2' => array('title'   => 'Polimeri artificiali 1-4' ,'content' => 'file2.html'),
			'PAG3' => array('title'   => 'Polimeri artificiali 5-8' ,'content' => 'file3.html'),
			'PAG4' => array('title'   => 'Polimeri artificiali 9-13' ,'content' => 'file4.html')
	);

	/**
	 *
	 */
	static public function init() {
		$class = __CLASS__ ;
		new $class;
	}

	/**
	 *
	 */
	public function __construct() {
		$pt = ($_GET['post_type']) ? : get_post_type( $_GET['post']);
		if($pt=='trashpic-report')
			add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_tabs' ), 20 );
	}

	/**
	 *
	 */
	public function add_tabs() {
		foreach ( $this->tabs as $id => $data ) {
			get_current_screen()->add_help_tab( array(
	 			 'id'       => $id,
				 'title'    => __( $data['title'], 'trashpic' ),
				 'content'  => '<p>La fotoguida</p>',
			   'callback' => array( $this, 'prepare' )
			));
		}
	}

	/**
	 *
	 * @param unknown $screen
	 * @param unknown $tab
	 */
	public function prepare( $screen, $tab ) {

		$file = sprintf("%s/%s", dirname(__FILE__),$tab['callback'][0]->tabs[ $tab['id'] ]['content']);
		$content = file_get_contents($file);

		if(!$content) $content ="Nessun contenuto. ".$file;
		else $content = str_replace('%path%',TRASHPIC_URL_HELP_IMG,$content);

		printf(	'<p>%s</p>', $content );
	}
}


?>
