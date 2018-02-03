<?php
add_action('wp_ajax_parser_link', 'ajax_parser_link');
add_action('wp_ajax_parser_update', 'ajax_parser_update');
add_action('wp_ajax_create_csv', 'ajax_create_csv');
function ajax_parser_link(){
	$result = array();
	$pagination = '';
	$paged = 1;
	$search = (isset($_POST['search']))?$_POST['search']:NULL;
	if(isset($_POST['paged']))
		$paged = $_POST['paged'];
	if(!empty($_POST['id'])){
		$result = getResultByID($_POST['id']);
	}else if(!empty($_POST['type'])){
		$result = getResults($_POST['type'],$paged,$search);
		$pagination = parser_pagination(array('post_types'=>$_POST['type'],'paged'=>$paged));
	}

	echo json_encode(array('content'=>$result,'pagi'=>$pagination),JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);		
	die;
}

function ajax_parser_update(){
	global $wpdb;
	$attr = array(
		'text' => 0,
		'href' => 1,
		'rel' =>2,
		'target' => 3, 
	);
	if(!isset($_POST['id']))
		die;

	$post = get_post($_POST['id']);
	$content = str_get_html($post->post_content);
			if($content){
				$tag = $content->find('a',$_POST['num']);
				if(!empty($_POST['data'][$attr['href']]))
					$tag->href = $_POST['data'][$attr['href']];
				else
					$tag->href = null;

				if(!empty($_POST['data'][$attr['rel']]))
					$tag->rel = $_POST['data'][$attr['rel']];
				else
					$tag->rel = null;

				if(!empty($_POST['data'][$attr['target']]))
					$tag->target = $_POST['data'][$attr['target']];
				else
					$tag->target = null;

				if(!empty($_POST['data'][$attr['text']]))
					$tag->innertext = $_POST['data'][$attr['text']];
				else
					$tag->innertext = null;

				$wpdb->update( $wpdb->posts,
					array( 'post_content' => stripslashes($content)),
					array( 'ID' => $_POST['id'] ),
					array( '%s' ),
					array( '%d' )
				);							
			}
	die;
}

function ajax_create_csv(){
	isset($_POST['data']) || die;
	$path = __DIR__.'/csv/links_parser.csv';
	$csv = fopen($path, 'w');
	foreach ($_POST['data'] as $line) {
		fputcsv($csv, $line);
	}
	fclose($csv);
	echo plugins_url('parse-links/system/csv/links_parser.csv');
	die;
}