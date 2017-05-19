<?php
/**
 * Template Name: maquette section 3 (Eedomus)
 */

get_header(); ?> 

<?php
include_once get_stylesheet_directory() . '/inc/pi-login/test-w.php';
// https://github.com/gboudreau/nest-api
require_once get_stylesheet_directory() . '/inc/nest.class.php';

// définition des variables
$api_user = $ee_apiuser  ; // a récupérer sur votre compte eedomus
$api_secret = $ee_passwd; // a récupérer sur votre compte eedomus
$periph_id = 156595; // code API du périphérique à mettre à jour (http://doc.eedomus.com/index.php/Code_API)

// construction de l'URL de l'API
$url = "http://api.eedomus.com/get?";
$url .= "api_user=$api_user";
$url .= "&api_secret=$api_secret";
$url .= "&action=periph.list";


// appel de l'API
$result = file_get_contents($url);

// on controle le résultat
if (strpos($result, '"success": 1') == false)
{
  echo "Une erreur est survenue: [".$result."]";
}
else
{
  // Tout s'est bien passé, si nécessaire on peut exécuter un code spécifique ici
}
?>


<?php

function eedomus_url( $action, $user_id, $pw ) {
	/* 
	* eedomus_url( "&action=auth.test", $ee_apiuser, $ee_passwd ) construit
	* https://api.eedomus.com/get?api_user=USER&api_secret=SECRET&action=auth.test
	* eedomus_url( "&action=periph.list", $ee_apiuser, $ee_passwd ) construit
	* https://api.eedomus.com/get?api_user=USER&api_secret=SECRET&action=periph.list		
	* eedomus_url( "&action=periph.caract&periph_id=156595", $ee_apiuser, $ee_passwd ) construit
	* https://api.eedomus.com/get?api_user=USER&api_secret=SECRET&action=periph.caract&periph_id=156595
	*
	* ces 3 url renvoient bien une info en Json, en provoenance de l'eedomus dans un navigateur
	*/
	
	$eedomus_get = "https://api.eedomus.com/get?api_user=" ;
	$eedomus_get .= $user_id ;
	$eedomus_get .= "&api_secret=" ;
	$eedomus_get .= $pw ;
	$eedomus_get .= $action ;

	return $eedomus_get ;
}

$ee_test = eedomus_url( "&action=auth.test", $ee_apiuser, $ee_passwd ) ; 
$ee_list = eedomus_url( "&action=periph.list", $ee_apiuser, $ee_passwd ) ; 
$ee_156595 = eedomus_url( "&action=periph.caract&periph_id=156595", $ee_apiuser, $ee_passwd ) ; 

function ald_decode_french( $string ) {
	
	$contents = utf8_encode( $string ); 
	$results = json_decode( $contents ); // si json_decode( $contents, TRUE ); on obtient un array
	return $results ;
	
}


// json_decode avec TRUE decode et transforme en array	
?>



<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<h3>via la page</h3>
			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

			<section id="eedomus" class="al-dashboard">
				<h2> eedomus test </h2>
				<p>eedomus url : pas affiché<?php // var_dump( $url ); ?></p>
				<pre><?php // print_r ( $result ) ; ?> </pre>
	
				<h2>Eedomus</h2>				
				<pre><?php // print_r ( file_get_contents( $ee_list ) ) ; ?> </pre>
				
				<p>eedomus connexion : <?php var_dump( json_decode( file_get_contents( $ee_test ), true ) ); ?></p>
				<pre><?php print_r ( json_decode( file_get_contents( $ee_test ), true ) ); ?> </pre>

				<p>eedomus liste des périphériques : <?php // var_dump( ald_decode_french( file_get_contents( $ee_list ) )  ); ?></p>
				<pre><?php print_r ( ald_decode_french( file_get_contents( $ee_list ) ) ); ?> </pre>

				<p>eedomus valeur : <?php var_dump( json_decode( file_get_contents( $ee_156595 ), true ) ); ?></p>
				<pre><?php print_r ( json_decode( file_get_contents( $ee_156595 ), true ) ); ?> </pre>				
			</section>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();