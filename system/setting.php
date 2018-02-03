<?php
/**
* 
*/
class Parser{
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init',array( $this, 'parser_scripts'));
	}

	function admin_menu () {
		add_menu_page("Парсер","Парсер",'manage_options','parser-index',array($this,'parser_index'),'', 67);
		add_submenu_page('parser-index','Налаштування','Налаштування','manage_options','parser-option',array($this,'parser_page'));
	}

	function parser_index () {
		require_once __DIR__."/parser.php";
	}

	function parser_page () {
		require_once __DIR__."/options.php";
	}
	
	function parser_scripts(){
		if(isset($_GET['page']) && $_GET['page'] == 'parser-index'){
			wp_enqueue_style('parser-bootstrap-style',plugins_url('/parse-links/css/lib/bootstrap.min.css'));
			wp_enqueue_style('parser-style',plugins_url('/parse-links/css/style.css'));
		    wp_enqueue_script('parser-bootstrap-jquery',plugins_url('/parse-links/js/lib/bootstrap.min.js'), array('jquery'),'1.0.0',true);			
		    wp_enqueue_script('parser-script',plugins_url('/parse-links/js/script.js'), array(),'1.0.0',true);
		    wp_localize_script( 'parser-script', 'parse_ajax', 
				array(
					'url' => admin_url('admin-ajax.php')
				)
			);
		}
	}

}

new Parser;