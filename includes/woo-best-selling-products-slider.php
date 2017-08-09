<?php

add_shortcode( 'bestselling_products_slider', 'wcpscwc_bestselling_products_slider' );
function wcpscwc_bestselling_products_slider($atts){
 
	global $woocommerce_loop;
 
		extract(shortcode_atts(array(
		'cats' => '',	
		'design' => '',
		'tax' => 'product_cat',	
		'limit' => '-1',	
		'slide_to_show' => '3',
		'slide_to_scroll' => '3',
		'autoplay' => 'true',
		'autoplay_speed' => '3000',
		'speed' => '300',
		'arrows' => 'true',
		'dots' => 'true',
		'rtl'  => false,
		'slider_cls'	=> 'products',
	), $atts));
 
	$unique = wcpscwc_get_unique();
	
	$cat = (!empty($cats)) ? explode(',',$cats) 	: '';
	$slider_cls = !empty($slider_cls) ? $slider_cls : 'products';
	$design = !empty($design) ? $design : '';
 
	ob_start();
 
	// Slider configuration
	$slider_conf = compact('slide_to_show', 'slide_to_scroll', 'autoplay', 'autoplay_speed', 'speed', 'arrows','dots','rtl', 'slider_cls'); 	
 
		// setup query
		$args = array(
			'post_type' 			=> 'product',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts'   => 1,
			'posts_per_page'		=> $limit,			
			'orderby' 		 		=> 'meta_value_num',
			'order'                 => 'DESC',
			'meta_query' => array(				
				// get only products marked as featured
				array(
					'key' => 'total_sales',
					'value' => 0,
					'compare' => '>',
				)
			)			
		);

		// Category Parameter
		if($cat != "") {			
			$args['tax_query'] = array(
										array( 
												'taxonomy' 	=> $tax,
												'field' 	=> 'id',
												'terms' 	=> $cat
									));

		}		
 
		// query database
		$products = new WP_Query( $args );
 
		
 
		if ( $products->have_posts() ) : ?>
			<div class="wcpscwc-product-slider-wrap wcps-<?php echo $design; ?>">
				<div class="woocommerce wcpscwc-product-slider" id="wcpscwc-product-slider-<?php echo $unique; ?>">
				<?php woocommerce_product_loop_start(); ?>
	 
					<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					
						<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					
					<?php endwhile; // end of the loop. ?>
	 
				<?php woocommerce_product_loop_end(); ?>
				</div>
				<div class="wcpscwc-slider-conf"><?php echo json_encode( $slider_conf ); ?></div><!-- end of-slider-conf -->
			</div>
		<?php endif;
 
		wp_reset_postdata();
	 
	
	?>

	
	<?php return ob_get_clean(); 
}

