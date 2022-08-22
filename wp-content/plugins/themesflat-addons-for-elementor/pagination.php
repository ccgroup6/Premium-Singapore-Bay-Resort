<?php
if(!function_exists('tfpost_pagination')){
	function tfpost_pagination($query, $paged, $style, $align) {

		if($style == 'numeric'){
			?>
			<nav class="navigation navigation-numeric" role="navigation">
				<div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
				<?php	
				if (  $query->max_num_pages > 1 ){		  
					echo paginate_links( array(
					'base' => str_replace( $query->max_num_pages, '%#%', esc_url( get_pagenum_link( $query->max_num_pages ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, $paged ),
					'total' => $query->max_num_pages,
					'prev_next' => false
					));
				}
				?>
				</div>
			</nav>
			<?php
		}elseif ($style == 'numeric-link') {
			?>
			<nav class="navigation navigation-numeric-link" role="navigation">
				<div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
				<?php
				if (  $query->max_num_pages > 1 ){
					echo paginate_links( array(
					'base' => str_replace( $query->max_num_pages, '%#%', esc_url( get_pagenum_link( $query->max_num_pages ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, $paged ),
					'total' => $query->max_num_pages,				
					'prev_text' => ( '<i class="fas fa-angle-left"></i>' ),
					'next_text' => ( '<i class="fas fa-angle-right"></i>' ),
					));
				}
				?>
				</div>
			</nav>
			<?php
		}elseif ($style == 'link') {
			?>
			<nav class="navigation navigation-link" role="navigation">
				<div class="pagination loop-pagination clearfix">
				<?php
				if (  $query->max_num_pages > 1 ){
					echo paginate_links( array(
					'base' => str_replace( $query->max_num_pages, '%#%', esc_url( get_pagenum_link( $query->max_num_pages ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, $paged ),
					'total' => $query->max_num_pages,
					'prev_text' => esc_html__( 'Previous', 'tf-addon-for-elementer' ),
					'next_text' => esc_html__( 'Next', 'tf-addon-for-elementer' ),
					));
				}
				?>
				</div>
			</nav>
			<?php
		}elseif ($style == 'loadmore') {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                ?>
                <div class="scroll-loadmore"></div>
                <nav class="navigation loadmore" role="navigation">
                    <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">            
                    <a href=" <?php echo esc_url( get_next_posts_page_link() ); ?> "><?php echo esc_html__('Load More', 'tf-addon-for-elementer'); ?></a>
                    </div>
                </nav>
                <?php
            }else {
                ?>
                <div class="scroll-loadmore"></div>
                <nav class="navigation loadmore" role="navigation">
                    <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
                    <?php echo next_posts_link( esc_html__( 'Load More', 'tf-addon-for-elementer' ), $query->max_num_pages ); ?>
                    </div>
                </nav>
                <?php
            }
		}
		
	}
}
