<?php
/**
 * Custom functions
 *---------------------/
 
 
 /**
 * Home page id
 */
$home_id = pll_get_post(get_option( 'page_on_front' ));
 
/**
 * Delete widgets.
 */
function remove_default_widget() {
	unregister_widget('WP_Widget_Archives'); // Архивы
	unregister_widget('WP_Widget_Calendar'); // Календарь
	unregister_widget('WP_Widget_Categories'); // Рубрики
	unregister_widget('WP_Widget_Meta'); // Мета
	unregister_widget('WP_Widget_Pages'); // Страницы
	unregister_widget('WP_Widget_Recent_Comments'); // Свежие комментарии
	unregister_widget('WP_Widget_Recent_Posts'); // Свежие записи
	unregister_widget('WP_Widget_RSS'); // RSS
	unregister_widget('WP_Widget_Search'); // Поиск
	unregister_widget('WP_Widget_Tag_Cloud'); // Облако меток
	unregister_widget('WP_Widget_Text'); // Текст 
	unregister_widget('WP_Widget_Media_Audio'); 
	unregister_widget('WP_Widget_Media_Video');
	unregister_widget('WP_Widget_Media_Gallery'); 
	unregister_widget('WP_Widget_Media_Image'); 
}
 
add_action( 'widgets_init', 'remove_default_widget', 20 );

/**
 * Add a breadcrumb
 */
function the_breadcrumb(){
 
	// получаем номер текущей страницы
	$pageNum = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
   
	$separator = ''; //  »
   
	// если главная страница сайта
	if( is_front_page() ){
   
	  if( $pageNum > 1 ) {
		echo '<a href="' . site_url() . '">' . pll__('main') . '</a>' . $separator . $pageNum . '-я страница';
	  }
   
	} else { // не главная
   
	  echo '<a href="' .site_url(). '">' . get_bloginfo('name') . '</a>' . $separator; 
   
	  if( is_single() ){ // записи
   
		the_category( $separator, 'multiple'); echo $separator; the_title();
   
	  } elseif ( is_home() ){ // страницы WordPress 
			   
			  wp_title('');
   
	  } elseif ( is_page() ){ // страницы WordPress 
   
		global $post;
			  
			  // если у текущей страницы существует родительская
			  if ( $post->post_parent ) {
  
				  $parent_id  = $post->post_parent; // присвоим в переменную
				  $breadcrumbs = array(); 
  
				  while ( $parent_id ) {
					  $page = get_page( $parent_id );
					  $breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
					  $parent_id = $page->post_parent;
				  }
				  echo join( $separator, array_reverse( $breadcrumbs ) ) . $separator;
			  }
			  
			  the_title();
   
	  } elseif ( is_category() ) {
			  
			  $current_cat = get_queried_object();
			  // если родительская рубрика существует
			  if( $current_cat->parent ) {
				  echo get_category_parents( $current_cat->parent, true, $separator ) . $separator;
			  }
   
		single_cat_title();
   
	  } elseif( is_tag() ) {
   
		single_tag_title();
   
	  } elseif ( is_day() ) { // архивы (по дням)
   
		echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
		echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
		echo get_the_time('d');
   
	  } elseif ( is_month() ) { // архивы (по месяцам)
   
		echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
		echo get_the_time('F');
   
	  } elseif ( is_year() ) { // архивы (по годам)
   
		echo get_the_time('Y');
   
	  } elseif ( is_author() ) { // архивы по авторам
   
		global $author;
		$userdata = get_userdata($author);
		echo 'Опублікував(ла) ' . $userdata->display_name;
   
	  } elseif ( is_404() ) { // если страницы не существует
  
		echo 'Помилка 404'; 
	  }
   
	  if ( $pageNum > 1 ) { // номер текущей страницы
		echo ' (' . $pageNum . '-я страница)';
	  }
   
    }
   
}


/**
 * Unregister admin menu items
 */
add_action( 'admin_menu', 'remove_menus' );
function remove_menus(){
	remove_menu_page( 'edit-comments.php' );          // Комментарии
}


/**
 * Delete category prefix
 */
add_filter( 'get_the_archive_title_prefix', 'delete_category_prefix' );
function delete_category_prefix( $prefix ){
  $prefix = '';
  return $prefix;
}


/**
 * Change excerpt length
 */
add_filter( 'excerpt_length', function(){
	return 20;
});


/**
 * Change excerpt "more"
 */
add_filter('excerpt_more', function($more) {
	return '...';
});


/**
 * Socials links
 */
function the_social_link( $social = '' ) {

    if( have_rows('contact-socials', 'contact') ) :
        while( have_rows('contact-socials', 'contact') ) :
            the_row();

            if( !$social ) {
                return;
            }

            $link = get_sub_field( $social );

            switch ( $social ) {
                case 'youtube' :
                    $icon = '<svg viewBox="0 -62 512.00199 512" xmlns="http://www.w3.org/2000/svg"><path d="m334.808594 170.992188-113.113282-61.890626c-6.503906-3.558593-14.191406-3.425781-20.566406.351563-6.378906 3.78125-10.183594 10.460937-10.183594 17.875v122.71875c0 7.378906 3.78125 14.046875 10.117188 17.832031 3.308594 1.976563 6.976562 2.96875 10.652344 2.96875 3.367187 0 6.742187-.832031 9.847656-2.503906l113.117188-60.824219c6.714843-3.613281 10.90625-10.59375 10.9375-18.222656.027343-7.628906-4.113282-14.640625-10.808594-18.304687zm-113.859375 63.617187v-91.71875l84.539062 46.257813zm0 0"/><path d="m508.234375 91.527344-.023437-.234375c-.433594-4.121094-4.75-40.777344-22.570313-59.421875-20.597656-21.929688-43.949219-24.59375-55.179687-25.871094-.929688-.105469-1.78125-.203125-2.542969-.304688l-.894531-.09375c-67.6875-4.921874-169.910157-5.5937495-170.933594-5.59765575l-.089844-.00390625-.089844.00390625c-1.023437.00390625-103.246094.67578175-171.542968 5.59765575l-.902344.09375c-.726563.097657-1.527344.1875-2.398438.289063-11.101562 1.28125-34.203125 3.949219-54.859375 26.671875-16.972656 18.445312-21.878906 54.316406-22.382812 58.347656l-.058594.523438c-.152344 1.714844-3.765625 42.539062-3.765625 83.523437v38.3125c0 40.984375 3.613281 81.808594 3.765625 83.527344l.027344.257813c.433593 4.054687 4.746093 40.039062 22.484375 58.691406 19.367187 21.195312 43.855468 24 57.027344 25.507812 2.082031.238282 3.875.441406 5.097656.65625l1.183594.164063c39.082031 3.71875 161.617187 5.550781 166.8125 5.625l.15625.003906.15625-.003906c1.023437-.003907 103.242187-.675781 170.929687-5.597657l.894531-.09375c.855469-.113281 1.816406-.214843 2.871094-.324218 11.039062-1.171875 34.015625-3.605469 54.386719-26.019532 16.972656-18.449218 21.882812-54.320312 22.382812-58.347656l.058594-.523437c.152344-1.71875 3.769531-42.539063 3.769531-83.523438v-38.3125c-.003906-40.984375-3.617187-81.804687-3.769531-83.523437zm-26.238281 121.835937c0 37.933594-3.3125 77-3.625 80.585938-1.273438 9.878906-6.449219 32.574219-14.71875 41.5625-12.75 14.027343-25.847656 15.417969-35.410156 16.429687-1.15625.121094-2.226563.238282-3.195313.359375-65.46875 4.734375-163.832031 5.460938-168.363281 5.488281-5.082032-.074218-125.824219-1.921874-163.714844-5.441406-1.941406-.316406-4.039062-.558594-6.25-.808594-11.214844-1.285156-26.566406-3.042968-38.371094-16.027343l-.277344-.296875c-8.125-8.464844-13.152343-29.6875-14.429687-41.148438-.238281-2.710937-3.636719-42.238281-3.636719-80.703125v-38.3125c0-37.890625 3.304688-76.914062 3.625-80.574219 1.519532-11.636718 6.792969-32.957031 14.71875-41.574218 13.140625-14.453125 26.996094-16.054688 36.160156-17.113282.875-.101562 1.691407-.195312 2.445313-.292968 66.421875-4.757813 165.492187-5.464844 169.046875-5.492188 3.554688.023438 102.589844.734375 168.421875 5.492188.808594.101562 1.691406.203125 2.640625.3125 9.425781 1.074218 23.671875 2.699218 36.746094 16.644531l.121094.128906c8.125 8.464844 13.152343 30.058594 14.429687 41.75.226563 2.558594 3.636719 42.171875 3.636719 80.71875zm0 0"/></svg>';
                    break;

                case 'instagram' :
                    $icon = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M11.9879 3.52803C11.9598 2.89043 11.8567 2.45208 11.709 2.07224C11.5566 1.66906 11.3222 1.30809 11.0151 1.00801C10.715 0.703262 10.3517 0.466461 9.95314 0.316468C9.5711 0.168765 9.13504 0.0656561 8.49744 0.0375439C7.85507 0.00705093 7.65114 0 6.02192 0C4.3927 0 4.18877 0.00705093 3.54878 0.0351631C2.91118 0.0632753 2.47283 0.166475 2.09309 0.314087C1.68981 0.466461 1.32884 0.700882 1.02876 1.00801C0.724014 1.30809 0.487304 1.67144 0.33722 2.06995C0.189516 2.45208 0.086408 2.88805 0.0582959 3.52565C0.0278029 4.16802 0.020752 4.37195 0.020752 6.00117C0.020752 7.6304 0.0278029 7.83432 0.055915 8.47431C0.0840272 9.11192 0.187227 9.55027 0.334931 9.9301C0.487304 10.3333 0.724014 10.6943 1.02876 10.9943C1.32884 11.2991 1.69219 11.5359 2.0907 11.6859C2.47283 11.8336 2.9088 11.9367 3.54649 11.9648C4.18639 11.993 4.39041 12 6.01963 12C7.64885 12 7.85278 11.993 8.49277 11.9648C9.13037 11.9367 9.56872 11.8336 9.94847 11.6859C10.7549 11.3741 11.3925 10.7365 11.7043 9.9301C11.8519 9.54798 11.9551 9.11192 11.9833 8.47431C12.0114 7.83432 12.0184 7.6304 12.0184 6.00117C12.0184 4.37195 12.016 4.16802 11.9879 3.52803ZM10.9073 8.42743C10.8815 9.01348 10.783 9.32995 10.701 9.54093C10.4994 10.0637 10.0844 10.4786 9.56167 10.6802C9.35069 10.7623 9.03194 10.8607 8.44817 10.8865C7.81524 10.9147 7.62541 10.9216 6.0243 10.9216C4.42319 10.9216 4.23098 10.9147 3.60034 10.8865C3.01428 10.8607 2.69782 10.7623 2.48684 10.6802C2.22669 10.5841 1.98989 10.4317 1.79768 10.2325C1.59842 10.0379 1.44605 9.80346 1.3499 9.54331C1.26785 9.33233 1.16941 9.01348 1.14368 8.42981C1.11548 7.79687 1.10852 7.60695 1.10852 6.00584C1.10852 4.40473 1.11548 4.21252 1.14368 3.58197C1.16941 2.99592 1.26785 2.67945 1.3499 2.46847C1.44605 2.20823 1.59842 1.97152 1.80006 1.77922C1.99456 1.57996 2.22898 1.42759 2.48922 1.33153C2.7002 1.24948 3.01905 1.15104 3.60272 1.12522C4.23565 1.09711 4.42557 1.09006 6.02659 1.09006C7.63008 1.09006 7.81991 1.09711 8.45055 1.12522C9.03661 1.15104 9.35307 1.24948 9.56405 1.33153C9.8242 1.42759 10.061 1.57996 10.2532 1.77922C10.4525 1.9738 10.6048 2.20823 10.701 2.46847C10.783 2.67945 10.8815 2.99821 10.9073 3.58197C10.9354 4.2149 10.9425 4.40473 10.9425 6.00584C10.9425 7.60695 10.9354 7.79449 10.9073 8.42743Z" fill="white"/><path d="M6.02184 2.91846C4.32 2.91846 2.93921 4.29916 2.93921 6.00109C2.93921 7.70302 4.32 9.08372 6.02184 9.08372C7.72377 9.08372 9.10447 7.70302 9.10447 6.00109C9.10447 4.29916 7.72377 2.91846 6.02184 2.91846ZM6.02184 8.00072C4.91777 8.00072 4.02221 7.10525 4.02221 6.00109C4.02221 4.89693 4.91777 4.00146 6.02184 4.00146C7.126 4.00146 8.02147 4.89693 8.02147 6.00109C8.02147 7.10525 7.126 8.00072 6.02184 8.00072Z" fill="white"/><path d="M9.94636 2.7968C9.94636 3.19422 9.62412 3.51646 9.22661 3.51646C8.8292 3.51646 8.50696 3.19422 8.50696 2.7968C8.50696 2.39929 8.8292 2.07715 9.22661 2.07715C9.62412 2.07715 9.94636 2.39929 9.94636 2.7968Z" fill="white"/></g><defs><clipPath id="clip0"><rect width="12" height="12" fill="white"/></clipPath></defs></svg>';
                    break;

                case 'facebook' :
                    $icon = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M7.99875 1.9925H9.09425V0.0845C8.90525 0.0585 8.25525 0 7.49825 0C5.91875 0 4.83675 0.9935 4.83675 2.8195V4.5H3.09375V6.633H4.83675V12H6.97375V6.6335H8.64625L8.91175 4.5005H6.97325V3.031C6.97375 2.4145 7.13975 1.9925 7.99875 1.9925Z" fill="white"/></g><defs><clipPath id="clip0"><rect width="12" height="12" fill="white"/></clipPath></defs></svg>';
                    break;

                case 'linkedin' :
                    $icon = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M11.9974 12.0001V11.9996H12.0004V7.59861C12.0004 5.44561 11.5369 3.78711 9.01987 3.78711C7.80987 3.78711 6.99787 4.45111 6.66637 5.08061H6.63137V3.98811H4.24487V11.9996H6.72987V8.03261C6.72987 6.98811 6.92787 5.97811 8.22137 5.97811C9.49587 5.97811 9.51487 7.17011 9.51487 8.09961V12.0001H11.9974Z" fill="white"/></g><defs><clipPath id="clip0"><rect width="12" height="12" fill="white"/></clipPath></defs></svg>';
                    break;
            };

            printf(
                '<a href="%s">%s</a>',
                $link,
                $icon
            );

        endwhile;
    endif;
}


/**
 * Messengers links
 */
function the_messenger_link( $messenger = '' ) {

    $phone = get_sub_field( $messenger );
    $icon  = get_bloginfo( 'template_url' ). '/img/' .$messenger . '.svg';

    switch ( $messenger ) {
        case 'telegram' :
            $link = 'https://t.me/'. $phone;
            break;

        case 'viber' :
            if ( wp_is_mobile() && ! preg_match( '/iPhone|iPad|iPod/i', $_SERVER ['HTTP_USER_AGENT'] ) ) {
                $viber_prefix = "";
            } else {
                $viber_prefix = "%2B";
            }

            $link = 'viber://chat?number='. $viber_prefix . preg_replace('/[^0-9]/', '', $phone );
            break;

        case 'whatsapp' :
            $link = 'https://wa.me/'. preg_replace('/[^0-9]/', '', $phone );
            break;
    };

    printf(
        '<a href="%s">
	        <img src="%s" alt="%s">
	    </a>',
        $link,
        $icon,
        $messenger
    );
}


/**
 * Posts loop
 */
function post_loop( $post_type = 'post', $taxonomy = '', $category = '' ) {
    $args = array(
        'post_type'     => $post_type,
        $taxonomy       => $category->slug
    );

    $wp_query = new WP_Query( $args );

    if( $wp_query->have_posts() ) {
        while( $wp_query->have_posts() ) {
            $wp_query->the_post();
            get_template_part( 'template-parts/content', $post_type );
        }
    }
    wp_reset_postdata();
}


/**
 * Add ACF options page
 */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'  => 'Тут знаходиться основна контактна інформація',
        'menu_title'  => 'Контактна інформація',
        'menu_slug'   => 'main-contact-info',
        'capability'  => 'edit_posts',
        'post_id'     => 'contact',
        'icon_url'    => 'dashicons-phone',
        'position'    => 29,
    ));

    acf_add_options_page(array(
        'page_title'  => 'CBA Settings',
        'menu_title'  => 'Theme settings',
        'menu_slug'   => 'cba-settings',
        'capability'  => 'edit_posts',
        'icon_url'    => 'dashicons-games',
        'redirect'    => true,
        'position'    => 81,
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'WordPress',
        'menu_title'	=> 'WordPress',
        'parent_slug'	=> 'cba-settings',
        'post_id'       => 'cba-wordpress',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Woocommerce',
        'menu_title'	=> 'Woocommerce',
        'parent_slug'	=> 'cba-settings',
        'post_id'       => 'cba-woocommerce',
    ));
}

/**
 * ACF button
 */
function acf_taxonomy_button( $name='', $class='', $icon='', $arrow=false ) {
    if( have_rows( $name ) ) {
        while( have_rows( $name ) ) {
            the_row();
            $svg = '';
            $term = get_sub_field('link');
            if( $arrow == true ) {
                $svg = '<svg width="19" height="8" viewBox="0 0 19 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.3536 4.35355C18.5488 4.15829 18.5488 3.84171 18.3536 3.64645L15.1716 0.464465C14.9763 0.269203 14.6597 0.269203 14.4645 0.464465C14.2692 0.659727 14.2692 0.976309 14.4645 1.17157L17.2929 4L14.4645 6.82843C14.2692 7.02369 14.2692 7.34027 14.4645 7.53553C14.6597 7.73079 14.9763 7.73079 15.1716 7.53553L18.3536 4.35355ZM4.37114e-08 4.5L18 4.5L18 3.5L-4.37114e-08 3.5L4.37114e-08 4.5Z" fill="white"/>
                        </svg>';
            }

            printf(
                '<a href="%s" class="%s">%s %s %s</a>',
                get_term_link( $term->term_id, $term->taxonomy ),
                $class,
                $icon,
                get_sub_field('text'),
                $svg
            );
        }
    }
}

/**
 * ACF Gallery
 */
function acf_gallery( $field='', $lightbox = false, $class='', $class_child='',  ) {
    $images = get_field($field);
    $size = 'large'; // (thumbnail, medium, large or custom size)

    if( $images ) {

        foreach ($images as $image) {

            if( $lightbox ) {
                printf(
                    '<a data-fslightbox="%s" href="%s">
                        <img src="%s" alt="%s">
                    </a>',
                    $field,
                    $image['url'],
                    $image['sizes'][$size],
                    $image['alt']
                );
            } else {
                printf(
                    '<div class="%s">
                        <div class="%s">
                            <img src="%s" alt="%s">
                        </div>
                    </div>',
                    $class,
                    $class_child,
                    $image['sizes'][$size],
                    $image['alt']
                );
            }
        }
    }
}

/**
 * Get background image
 */
function custom_background() {
    if( is_home() ) {
        $background = get_the_post_thumbnail_url( get_option('page_for_posts') );
    } elseif ( has_post_thumbnail() ) {
        $background = get_the_post_thumbnail_url();
    } else {
        $background = get_header_image();
    }    
    return $background;
}

/**
 * Get title
 */
function custom_title() {
    if ( is_front_page() ) {
        $title = get_bloginfo('name');
    } elseif ( is_home() ) {
        $title = get_the_title( get_option('page_for_posts') );
    } elseif ( is_category() ) {
        $title = get_the_archive_title( '<h1>', '</h1>' );
    } else {
        $title = get_the_title();
    }
    return $title;
}