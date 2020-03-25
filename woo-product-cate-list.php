<?php
/**
 * Plugin Name: woo-product-cate-list
 * Description: 产品页面显示分类列表
 */
?>
<?php
add_action( 'wp_enqueue_scripts', 'woo_product_categories_css' );
function woo_product_categories_css() {
    wp_register_style( 'woo_product_categories_css', plugins_url( 'css/style.css', __FILE__ ) );
    wp_enqueue_style( 'woo_product_categories_css' );
}

add_action( 'woocommerce_before_shop_loop', 'woo_product_subcategories', 50 );
function woo_product_subcategories( $args = array() ) {
	global $wp;
	//home_url( $wp->request.'/' );当前页面链接地址
	$woocommerce_category_id = get_terms('product_type');
    foreach ($woocommerce_category_id as $term) { 
		$args = array( 'parent' => $woocommerce_category_id ->term_id );
		$terms = get_terms( 'product_cat', $args );
		 if ( $terms ) {
			foreach ( $terms as $term ) {
				$args = $term->term_id;
				$thumbnailid = get_term_meta( $term->term_id, 'thumbnail_id', true );
				$thumbnailidurl = wp_get_attachment_url( $thumbnailid );
				?>
				<ul class="catlist products grid col-4">
				<div style="margin: 15px 10px;text-align: center; padding:50px;"><a class="catlistbackground" style="background-image:url(<?php echo $thumbnailidurl;?>);background-origin: border-box;background-position: 50%; background-size: cover;position: relative;box-sizing: border-box;font-size: 32px;padding:50px;"><?php echo $term->name;?></a></div>
					<?php woo_product_subcat( $args ); ?>
				</ul>
				<?php
			}			 
		}
    }
}

function woo_product_subcat( $args ) {  
	$query_args = array (
		'post_type' => 'product',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'term_id',
				'terms'     => $args
			)
		),
	);				
	$the_query = new WP_Query($query_args);			
		if( $the_query->have_posts() ):	
			while( $the_query->have_posts() ): $the_query->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;	
		endif;
	wp_reset_postdata();	
}