<?php
class trashpic_help
{
	public $tabs = array(
			// The assoc key represents the ID
			// It is NOT allowed to contain spaces
			'PAG1' => array('title'   => 'PAGINA 1'
					,'content' => 'file1.html'),
			'PAG2' => array('title'   => 'PAGINA 2'
					,'content' => 'file2.html'),
			'PAG3' => array('title'   => 'PAGINA 3'
					,'content' => 'file3.html')
	);

	static public function init()
	{
		$class = __CLASS__ ;
		new $class;
	}

	public function __construct()
	{
		$pt = ($_GET['post_type']) ? : get_post_type( $_GET['post']);
		
		if($pt=='trashpic-report')
			add_action( "load-{$GLOBALS['pagenow']}", array( $this, 'add_tabs' ), 20 );
	}

	public function add_tabs()
	{
		foreach ( $this->tabs as $id => $data )
		{
			get_current_screen()->add_help_tab( array(
			'id'       => $id
			,'title'    => __( $data['title'], 'trashpic' )
			// Use the content only if you want to add something
			// static on every help tab. Example: Another title inside the tab
			,'content'  => '<p>La fotoguida</p>'
					,'callback' => array( $this, 'prepare' )
			) );
		}
	}

	public function prepare( $screen, $tab )
	{
		
		$file = sprintf("%s/%s", dirname(__FILE__),$tab['callback'][0]->tabs[ $tab['id'] ]['content']);
		$content = file_get_contents($file);
		
		if(!$content) $content ="Nessun contenuto. ".$file;
		else $content = str_replace('%path%',TRASHPIC_URL_HELP_IMG,$content);
		
		//exit;
		printf(	'<p>%s</p>', $content );
	}
}


?>