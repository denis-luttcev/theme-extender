<?php
/**
 * Plugin Name: Theme Extender
 * Description: Adds Typograph support to author's name, adds new custom taxonomy Brends, forms article's Lead from first paragraph of text, adds copyright and publisher's markup into footer.
 * Author: Denis Luttcev
 * Version: 1.0.0
 * Text Domain: theme-extender
 */
 
// Uncomment this if WP Typograph Lite plugin used and activated.
//add_filter('the_author', 'typoFilterHeader', 9);


// Article's Lead formatted from 1-st paragraph of content.
function awesome_excerpt($text, $raw_excerpt) {
	if( !$raw_excerpt ) {
		$content = apply_filters( 'the_content', get_the_content() );
		$text = substr( $content, 0, strpos( $content, '</p>' ) + 4 );
	}
	
	return str_replace('<p>', "<p itemprop='description'>", $text);
}

add_filter( 'wp_trim_excerpt', 'awesome_excerpt', 10, 2 );

function trim_content($text) {
		if ( strpos( $text, '</p>' ) ) {
			$text = substr( $text, strpos( $text, '</p>' ) + 4 );
		}
		
		return $text;
}

add_filter( 'trim_content', 'trim_content', 10 );


// Add Brends Taxonomy.
function create_taxonomy_brends(){
	register_taxonomy('brends', array('post'), array(
		'label'                 => '',
		'labels'                => array(
			'name'              => 'Бренды',
			'singular_name'     => 'Бренд',
			'search_items'      => 'Найти бренды',
			'all_items'         => 'Все бренды',
			'view_item '        => 'Смотреть бренд',
			'parent_item'       => 'Родительский бренд',
			'parent_item_colon' => 'Родительский бренд:',
			'edit_item'         => 'Редактировать бренд',
			'update_item'       => 'Обновить бренд',
			'add_new_item'      => 'Новый бренд',
			'new_item_name'     => 'Переименовать бренд',
			'menu_name'         => 'Бренды',
			'choose_from_most_used' => 'Выбрать из часто используемых брендов',
		),
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => null,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_tagcloud'         => true,
		'show_in_rest'          => null,
		'rest_base'             => null,
		'hierarchical'          => false,
		//'update_count_callback' => '_update_post_term_count',
		'rewrite'               => true,
		//'query_var'             => $taxonomy,
		'capabilities'          => array(),
		'meta_box_cb'           => null,
		'show_admin_column'     => true,
		'_builtin'              => false,
		'show_in_quick_edit'    => null,
	) );
}

add_action( 'init', 'create_taxonomy_brends' );

function taxonomy_link( $link, $term, $taxonomy ) {
    if ( $taxonomy !== 'brends' )
        return $link;
    
	return str_replace( '/brends', '', $link );
}

add_filter( 'term_link', 'taxonomy_link', 10, 3 );


// Add copyright and publisher's markup into footer.
// Place 'logo.jpg' (height 60, text height 48, max widht 600) file into '/img' subdirectoty of current (child) theme.
// Insert call 'generate_extended_footer();' into footer.php of current (child) theme.
function generate_extended_footer() { ?>
		<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" id="copyright">
			<div>&copy; <span itemprop="name"><?php bloginfo(); ?></span>, <?php echo(date('Y')>2017 ? '2017-'.date('Y') : date('Y'))?>.</div>
			<div>Все права защищены.</div>
			<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<link itemprop="contentUrl" type="image/jpeg" rel="image_src" href="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.jpg" />
				<img itemprop="url" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.jpg" class="custom-logo" alt="<?php get_bloginfo(); ?>" />
			</div>
		</div>	
<?php }
 ?>