<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'site_scripts' ), 999 );
		add_action( 'widgets_init', array($this, 'my_register_sidebars' ));
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu('menu');
		$context['boczne'] = new TimberMenu('boczne');
		$context['site'] = $this;
		$context['primary_sidebar'] = Timber::get_widgets('Primary');
		$context['aktualne_sidebar'] = Timber::get_widgets('Aktualne');
		$context['header_links'] = array(
			'dzienniki' => array( 'link' => esc_url('https://uonetplus.vulcan.net.pl/mazowieckie/'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/dziennik.svg') ),
			'plan' => array( 'link' => esc_url('http://zs3-wyszkow.pl/plan_ZS3/'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/plan.svg') ),
			'echo trójki' => array( 'link' => esc_url('http://echotrojki.zs3-wyszkow.pl/'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/echo.svg') ),
			'projekty europejskie' => array( 'link' => esc_url('http://zs3-wyszkow.pl/category/projekty-europejskie/'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/eu.svg') ),
		);
		$context['klasy'] = array(
			'liceum' => array(
				'klasa lingwistyczno-humanistyczna' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/LOh.PDF'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/lol.png') ),
				'klasa matematyczno-informatyczna' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/LOi.PDF'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/loi.png') ),
				'klasa społeczno-ekonomiczna' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/LOe.PDF'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/loe.png') ),
				'klasa prozdrowotno-przyrodnicza' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/LOZ.pdf'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/loz.png') ),
				'klasa menadżersko-turystyczna' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/LOT.pdf'), 'icon' => esc_url(get_template_directory_uri().'/inc/images/lot.png') ),
			),
			'technikum' => array(
				'technikum informatyczne' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/Ti.PDF'), 'icon' => 
esc_url(get_template_directory_uri().'/inc/images/ti.png') ),
				'technikum ekonomiczne' => array( 'link' => 
esc_url('http://zs3-wyszkow.pl/dokumenty/Te.PDF'), 'icon' => 
esc_url(get_template_directory_uri().'/inc/images/te.png') ),
			)

		);
		$context['linki'] = Timber::get_posts( array( 'post_type' => 'link', 'meta_key' => 'kolejnosc', 'orderby' => 'meta_value_num', 'order' => 'ASC' ) );

		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

	function site_scripts() {
		global $wp_styles;
		//wp_enqueue_scripts('jquery');
		wp_enqueue_script( 'foundation-js', get_template_directory_uri() . '/inc/foundation/js/vendor/foundation.min.js', array( 'jquery' ), '6.0', true );
		wp_enqueue_script( 'What-Input-js', get_template_directory_uri() . '/inc/foundation/js/vendor/what-input.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'site-js', get_template_directory_uri() . '/inc/js/site.js', array( 'jquery' ), '', true );

		wp_enqueue_style( 'foundation-css', get_template_directory_uri() . '/inc/foundation/css/foundation.css', array(), '', 'all' );
		wp_enqueue_style( 'fonts-css', 'https://fonts.googleapis.com/css?family=Poiret+One|Roboto+Condensed:400,700|Roboto:400,700&amp;subset=latin-ext', '', 'all' );
		wp_enqueue_style( 'site-css', get_template_directory_uri() . '/inc/css/site.css', array(), '', 'all' );

	}

	function my_register_sidebars() {

		/* Register the 'primary' sidebar. */
		register_sidebar(
			array(
				'id' => 'primary',
				'name' => __( 'Primary' ),
				'description' => __( 'A short description of the sidebar.' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s animated bounceInRight duration1 eds-on-scroll ">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar(
			array(
				'id' => 'aktualne',
				'name' => __( 'Aktualne' ),
				'description' => __( 'A short description of the sidebar.' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s  /*animated flash duration14 delay2 infinite */ ">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		/* Repeat register_sidebar() code for additional sidebars. */
	}

}

new StarterSite();
