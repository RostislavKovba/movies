<?php
/**
 * Movies functions and definitions
 */


/**
 * Register and Enqueue Styles and Scripts.
 */

function movies_register_styles_and_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	// Load parent styles.
	wp_enqueue_style( 'movies-style', get_template_directory_uri() . '/style.css', null, $theme_version, 'print' );
    wp_enqueue_script( 'movies-js', get_template_directory_uri() . '/assets/js/index.js', array(), $theme_version, false );
}
add_action( 'wp_enqueue_scripts', 'movies_register_styles_and_scripts' );

/**
 * Change excerpt length
 */
add_filter( 'excerpt_length', function(){
	return 15;
});

/**
 * Change excerpt "more"
 */
add_filter('excerpt_more', function($more) {
	return '...';
});

/**
 * Posts loop
 */
function the_post_loop( $args = array() ) {

	$wp_query = new WP_Query( $args );

	if( $wp_query->have_posts() ) {
		while( $wp_query->have_posts() ) {
			$wp_query->the_post();
			get_template_part( 'template-parts/content', $args['post_type'] );
		}
	}
	wp_reset_postdata();
}


/**
 * Create shortcode [movies]
 */
add_shortcode( 'movies', 'the_newest_movies' );
function the_newest_movies($atts) {
	ob_start();

	the_post_loop( [
		'post_type'      => 'movie',
		'posts_per_page' => 4,
		'meta_key'		 => 'movie_date',
		'orderby'		 => 'meta_value',
		'order'			 => 'DESC'
	] );

	$newest_movies = ob_get_clean();
	return $newest_movies;
}

/**
 * Add ACF options page
 */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'  => 'Фильтры',
        'menu_title'  => 'Фильтры',
        'menu_slug'   => 'filters',
        'capability'  => 'edit_posts',
        'post_id'     => 'movie_filters',
        'icon_url'    => 'dashicons-filter',
        'position'    => 21,
    ));
}


/**
 * Register post types.
 */
function add_new_post_types() {
    $labels = [
        'name'               => 'Фильм',
        'singular_name'      => 'Фильм',
        'add_new'            => 'Добавить Фильм',
        'add_new_item'       => 'Добавить новий Фильм',
        'edit_item'          => 'Редактировать Фильм',
        'new_item'           => 'Новый Фильм',
        'all_items'          => 'Все Фильми',
        'view_item'          => 'Просмотр Фильмов на сайте',
        'search_items'       => 'Искать Фильмы',
        'not_found'          => 'Фильмов не найдено.',
        'not_found_in_trash' => 'В корзине нет Фильмов.',
        'menu_name'          => 'Фильмы'
    ];

    $args = [
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => true,
        'hierarchical'  => true,
        'menu_icon'     => 'dashicons-editor-video',
        'menu_position' => 21,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail',  'custom-fields', 'page-attributes', 'post-formats', 'revisions'],
    ];

    register_post_type( 'movie', $args );
}
add_action( 'init', 'add_new_post_types', 0 );


/**
 * Register taxonomies
 */
function add_new_taxonomies() {

    if ( have_rows( 'movie_filters', 'movie_filters' ) ) {
        while (have_rows('movie_filters', 'movie_filters') ) {
            the_row();

            $taxonomy     = get_sub_field('slug');
            $name         = get_sub_field('labels');
            $hierarchical = get_sub_field('hierarchical');

            $labels = [
                'name'                          => $name,
                'singular_name'                 => $name,
                'search_items'                  => 'Искать ' . $name,
                'popular_items'                 => 'Популярные ' . $name,
                'all_items'                     => 'Все ' . $name,
                'parent_item'                   => null,
                'parent_item_colon'             => null,
                'edit_item'                     => 'Редактировать ' . $name,
                'update_item'                   => 'Обновить ' . $name,
                'add_new_item'                  => 'Добавить ' . $name,
                'new_item_name'                 => 'Название нового' . $name,
                'separate_items_with_commas'    => 'Розделяйте ' . $name,
                'add_or_remove_items'           => 'Добавить или удалить ' . $name,
                'choose_from_most_used'         => 'Выбрать из часто используемых ' . $name,
                'menu_name'                     => $name
            ];

            $args = [
                'hierarchical'          => $hierarchical,
                'labels'                => $labels,
                'show_in_nav_menus'     => true,
                'show_ui'               => true,
                'show_tagcloud'         => true,
                'show_in_quick_edit'    => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
                'rewrite'               => array(
                    'slug'              => $taxonomy,
                    'hierarchical'      => $hierarchical
                )
            ];

            register_taxonomy( $taxonomy, 'movie', $args );
        }
    }
}
add_action( 'init', 'add_new_taxonomies', 0 );



/* Create columns for post type product. */
add_filter( 'manage_'.'movie'.'_posts_columns', 'add_movie_column', 4 );
function add_movie_column( $columns ){

    $new_columns['poster'] = 'Постер';

    if ( have_rows( 'movie_filters', 'movie_filters' ) ) {
        while (have_rows('movie_filters', 'movie_filters') ) {
            the_row();

            $taxonomy = get_sub_field('slug');
            $name     = get_sub_field('labels');

            $new_columns[$taxonomy] = $name;
        }
    }

    return
        array_slice( $columns, 0, 2 ) + $new_columns + array_slice( $columns, 2 );
}

/* Add data to column in admin. */
add_action('manage_'.'movie'.'_posts_custom_column', 'fill_movie_column', 5, 2 );
function fill_movie_column( $colname, $post_id ){

    if( $colname === 'poster' ) {
        echo '<img class="admin-movie_poster" src="' . get_field('movie_poster', $post_id) . '">';
    }

    if ( have_rows( 'movie_filters', 'movie_filters' ) ) {
        while (have_rows('movie_filters', 'movie_filters') ) {
            the_row();

            $name     = get_sub_field('labels');
            $taxonomy = get_sub_field('slug');

            if( $colname === $taxonomy ){

                $terms =  wp_get_post_terms( $post_id, $taxonomy );
                foreach( $terms as $term ) {
                    echo $term->name.'<br>';
                }
            }
        }
    }
}

/* Make column sortable */
add_filter( 'manage_'.'edit-movie'.'_sortable_columns', 'add_movie_sortable_column' );
function add_movie_sortable_column( $sortable_columns ){

    if ( have_rows( 'movie_filters', 'movie_filters' ) ) {
        while (have_rows('movie_filters', 'movie_filters') ) {
            the_row();

            $taxonomy = get_sub_field('slug');
            $sortable_columns[$taxonomy] = [ $taxonomy, false ];
        }
    }

    return $sortable_columns;
}

add_action('admin_head', 'admin_poster_styles');
function admin_poster_styles() {
    print
    '<style>
        .admin-movie_poster { max-height: 60px; width: auto; }
    </style>';
}


/**
 * Register acf fields
 */
acf_add_local_field_group(array (
	'key' => 'group_movies',
	'title' => 'Фильм',
	'fields' => array (
		array (
			'key' => 'field_price',
			'label' => 'Стоимость сеанса',
			'name' => 'movie_price',
			'type' => 'text',
			'prefix' => '',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_date',
			'label' => 'Дата выхода в прокат',
			'name' => 'movie_date',
			'type' => 'date_picker',
			'prefix' => '',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_poster',
			'label' => 'Постер',
			'name' => 'movie_poster',
			'type' => 'image',
			'prefix' => '',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'movie',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));
