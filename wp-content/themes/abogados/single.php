<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package abogados
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<div class="inner-content">
			<?php custom_breadcrumbs(); ?> <!-- Breadcrumbs here -->
			<div class="post-content">
				<div class="left-area">
					<?php
					while (have_posts()) :
						the_post();
						get_template_part('template-parts/content', get_post_type());
						echo do_shortcode('[addtoany]');
					endwhile; // End of the loop.
					?>
				</div>
				<div class="right-area">
					<?php get_sidebar(); ?>
				</div>
			</div>

			<!-- Related Posts -->
			<div class="related-post-content">
				<?php
				// Get the current post's categories
				$categories = get_the_category(get_the_ID());

				if ($categories) {
					$category_ids = array();

					foreach ($categories as $category) {
						$category_ids[] = $category->term_id;
					}

					// Query related posts
					$args = array(
						'category__in'   => $category_ids,
						'post__not_in'    => array(get_the_ID()), // Exclude the current post
						'posts_per_page'  => 3, // Number of related posts to show
						'orderby'         => 'date', // Order by date
    					'order'           => 'DESC', // Optional: order in descending order (most recent first)
					);

					$related_posts = new WP_Query($args);

					// Display related posts if any
					if ($related_posts->have_posts()) : ?>
						<div class="related-posts">
							<h2><?php _e('Otras Noticias', 'textdomain'); ?></h2>
							<div class="post-listing">
								<?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
									<div class="post-item">
										<div class="post-thumb">
											<div class="post-image">
												<?php if (has_post_thumbnail()) : ?>
													<img loading="lazy" decoding="async" width="400" height="267" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" class="attachment-full size-full wp-post-image" alt="<?php the_title_attribute(); ?>">
												<?php else : ?>
													<img loading="lazy" decoding="async" width="400" height="267" src="http://localhost/abogados/wp-content/uploads/placeholder-image.jpg" alt="<?php the_title_attribute(); ?>">
												<?php endif; ?>
											</div>
										</div>

										<div class="post-details">
											<div class="post-meta">
												<span class="post-category">
													<?php
													$post_categories = get_the_category();
													if ($post_categories) {
														echo '<a href="' . esc_url(get_category_link($post_categories[0]->term_id)) . '" rel="category tag">' . esc_html($post_categories[0]->name) . '</a>';
													}
													?>
												</span>
												<span class="post-date"><?php echo get_the_date('d M Y'); ?></span>
											</div>
											<a href="<?php the_permalink(); ?>">
												<h3 class="post-title"><?php the_title(); ?></h3>
											</a>
											<a href="<?php the_permalink(); ?>" class="read-more">Seguir Leyendo</a>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
							<?php wp_reset_postdata(); // Reset the query 
							?>
						</div>
					<?php else : ?>
						<h4><?php _e('No related posts found.', 'textdomain'); ?></h4>
				<?php endif;
				}
				?>
			</div>

		</div>
	</div>

</main><!-- #main -->

<?php

get_footer();
