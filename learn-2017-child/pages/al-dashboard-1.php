<?php
/**
 * Template Name: maquette section 1 (caméra)
 *
 */

get_header(); ?>

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

			<section id="camera" class="al-dashboard">
				<h3>via le modèle</h3>
				<img name="Foscam-FI8905W" class="stream" src= "http://109.190.19.81:8081/" width="600" height="450" title="Foscam FI8905W"/>
			</section>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
