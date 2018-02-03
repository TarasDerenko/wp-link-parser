<?php
function parser_set_options(){

}

function parser_unset_options(){
	
}

function show_parser_post_typs(){
	$post_types=get_post_types(array('public'=>true),'names','and');
?>	
<ul class="list-inline">
<?php	foreach ($post_types  as $type ) : ?>
	<li>
		<input class="post-type" type="checkbox" name="type[]" value="<?php echo $type ?>" id="type_<?php echo $type ?>">
		<label for="type_<?php echo $type ?>"><?php echo $type ?></label>
	</li>
<?php 	endforeach; ?>
</ul>
<?php	
}

function getResults($type,$paged = 1,$srch = null){
	$result = array();
	$posts = get_posts(array(
			'post_type' => $type,
			'posts_per_page' => 100,
			'paged' => $paged
		));
		foreach ($posts as $post) {
			$links = array();
			$content = str_get_html($post->post_content);
			if($content){
				$tags = $content->find('a');
				if(is_array($tags)){
					foreach ($tags as $tag) {
						$target = ($tag->target)?$tag->target:'';
						$rel = ($tag->rel)?$tag->rel:'';
						$url = ($tag->href)?$tag->href:'';
						$text = ($tag->innertext)?$tag->innertext:'';
						if(isset($srch)){
							foreach ($srch as $search) {
								switch ($search['attr']) {
									case 'anchor':
										if(strpos(trim($text), trim($search['val'])) === false){
											continue 2;
										}
										break;
									case 'url':
										if(strpos(trim($url), trim($search['val'])) === false)
											continue 2;
										break;
									case 'page':
										if(strpos(trim(get_permalink($post)), trim($search['val'])) === false)
											continue 4;
										break;
									case 'rel':
										if(trim($rel) != trim($search['val']))
											continue 2;
										break;
									case 'target':
										if(trim($target) != trim($search['val']))
											continue 2;
										break;
									default:
										continue;
										break;
								}
								$links[] = array(
								'url' => $url,
								'rel' => $rel,
								'target' => $target,
								'text' => $text
							);	
							}
						}else{
							$links[] = array(
								'url' => $url,
								'rel' => $rel,
								'target' => $target,
								'text' => $text
							);							
						}
					}				
				}
				if(!empty($links)){
					$result[] = array(
						'type' => $post->post_type,
						'id' => $post->ID,
						'url' => get_permalink($post),
						'title' => $post->post_title,
						'links' => $links
					);					
				}
			}
		}
		return $result;
}

function getResultByID($id = 0){
	$result = array();
	$post = get_post($id);
	if(!$post)
		return $result;
	$content = str_get_html($post->post_content);
	if($content){
		$tags = $content->find('a');
		if(is_array($tags)){
					foreach ($tags as $tag) {
						$target = ($tag->target)?$tag->target:'';
						$rel = ($tag->rel)?$tag->rel:'';
						$url = ($tag->href)?$tag->href:'';
						$text = ($tag->innertext)?$tag->innertext:'';
						$links[] = array(
							'url' => $url,
							'rel' => $rel,
							'target' => $target,
							'text' => $text
						);							
					
					}				
				}
				if(!empty($links)){
					$result[] = array(
						'type' => $post->post_type,
						'id' => $post->ID,
						'url' => get_permalink($post),
						'title' => $post->post_title,
						'links' => $links
					);					
				}
	}
	return $result;
}

function parser_pagination($args = array()){
	$default = array(
		'post_types' => array('post'),
		'paged' => 1,
		'limit' => 100
	);
	$args = array_merge($default,$args);
	$total = 0;
	foreach ($args['post_types'] as $type) {
		$total += wp_count_posts($type)->publish;
	}
	$max_paged = ceil($total / $args['limit']);
	$args = array(
		'total' => $max_paged,
		'current' => $args['paged'],
		'show_all' => false,
		'prev_next' => true,
		'end_size' => 1,
		'mid_size' => 4,
	);
	$total = (int) $args['total'];
	if ( $total < 2 ) {
		return;
	}
	$current  = (int) $args['current'];
	$end_size = (int) $args['end_size']; 
	if ( $end_size < 1 ) {
		$end_size = 1;
	}
	$mid_size = (int) $args['mid_size'];
	if ( $mid_size < 0 ) {
		$mid_size = 2;
	}

	$r = '';
	$page_links = array();
	$dots = false;


	if($current == 1){
		$page_linksss_1 = '<li class="noactive"><span aria-hidden="true">&laquo;</span></li>';
	}else{
		$page_linksss_1 = '<li><a data-paged="'. ($current - 1) .'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';		
	}

	for ( $n = 1; $n <= $total; $n++ ) :
		if ( $n == $current ) :
			$page_links[] = "<li class='noactive'><span>" . number_format_i18n( $n ) . "</span></li>";
			$dots = true;
		else :
			if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
				$page_links[] = "<li><a data-paged='" . number_format_i18n( $n ) . "'>" . number_format_i18n( $n ) . "</a></li>";
				$dots = true;
			elseif ( $dots && ! $args['show_all'] ) :
				$page_links[] = '<li><span class="page-numbers dots">' . __( '&hellip;' ) . '</span></li>';
				$dots = false;
			endif;
		endif;
	endfor;
		if($current == $total){
			$page_linksss_2 = '<li class="noactive"><span aria-hidden="true">&raquo;</span></li>';
		}else{
			$page_linksss_2 = '<li><a data-paged="' . ($current + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		}
			$r = '<nav aria-label="Page navigation" class="ato-pagenavi"><ul class="pagination">'.$page_linksss_1;
			$r .= join("\n", $page_links);
			$r .= $page_linksss_2.'</ul></nav>';
	return $r;	
}
