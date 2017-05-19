<?php 
add_action( 'wp_enqueue_scripts', 'tw17_child_enqueue_parent_child' );

// Do theme setup on the 'after_setup_theme' hook.
add_action( 'after_setup_theme', 'tw17_child_theme_setup', 11 ); 

function tw17_child_enqueue_parent_child() { 

    $parent_style = 'twentyseventeen-style-css'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'tw17-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );

} 

function tw17_child_theme_setup() {
	
	/* Register and load styles. */
	add_action( 'wp_enqueue_scripts', 'tw17_child_enqueue_styles', 4 ); 
	
	/* Register and load scripts. */
	add_action( 'wp_enqueue_scripts', 'tw17_child_enqueue_scripts' );
}

function tw17_child_enqueue_styles() {

	// feuille de style pour l'impression
	wp_enqueue_style( 'print', get_stylesheet_directory_uri() . '/css/print.css', array(), false, 'print' );

}

function tw17_child_enqueue_scripts() {

	// embed font awesome 
	// wp_enqueue_script( 'font-awesome', 'https://use.fontawesome.com/af5aa524e2.js', false );


}
?>