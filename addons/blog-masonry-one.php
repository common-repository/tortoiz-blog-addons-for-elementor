<?php


class tortoiz_blog_Blog_Masonry extends \Elementor\Widget_Base {

	// Widget Name

	public function get_name() {
		return 'tortoiz_blog-blog-masonry';
	}

	// Widget Titke

	public function get_title() {
		return __( 'Blog Masonry', 'tortoiz_blog' );
	}

	// Widget Icon

	public function get_icon() {
		return 'fa fa-rss';
	}

	//	Widget Categories

	public function get_categories() {
		return [ 'tortoiz_blog_addons' ];
	}

	// Register Widget Control

	protected function _register_controls() {

		$this->register_content_controls();
		$this->register_style_controls();

	}

	// Widget Controls 

	function register_content_controls() {

		$args = array(
			'orderby' => 'name',
			'order' => 'ASC'
		);

		$categories=get_categories($args);
		$cate_array = array();
		$arrayCateAll = array( 'all' => 'All categories ' );
		if ($categories) {
			foreach ( $categories as $cate ) {
				$cate_array[$cate->cat_name] = $cate->slug;
			}
		} else {
			$cate_array["No content Category found"] = 0;
		}

		// Controls

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content Controls', 'tortoiz_blog' ),
			]
		);

		// Category

		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'tortoiz_blog' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array_merge($arrayCateAll,$cate_array),
			]
		);

		// Total Count

		$this->add_control(
			'total_count',
			[
				'label' => __( 'Total Post', 'tortoiz_blog' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 9,
			]
		);

		// Order By

		$this->add_control(
			'order_by',
			[
				'label' => __('Order By', 'tortoiz_blog'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __('ASC', 'tortoiz_blog'),
					'desc' => __('DESC', 'tortoiz_blog'),
				]
			]
		);

		// Meta Condition

		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Date', 'tortoiz_blog' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tortoiz_blog' ),
				'label_off' => __( 'Hide', 'tortoiz_blog' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label' => __( 'Show Category', 'tortoiz_blog' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tortoiz_blog' ),
				'label_off' => __( 'Hide', 'tortoiz_blog' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_thumb',
			[
				'label' => __( 'Show Thumbnail', 'tortoiz_blog' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tortoiz_blog' ),
				'label_off' => __( 'Hide', 'tortoiz_blog' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->end_controls_section();

	}

	// Style Control

	protected function register_style_controls() {

		$this->start_controls_section(
			'title_style_section',
			[
				'label' => __( 'Title Style', 'tortoiz_blog' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'tortoiz_blog' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .entry-title a' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => __( 'Title Hover Color', 'tortoiz_blog' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#D82E2A',
				'selectors' => [
					'{{WRAPPER}} .entry-title a:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'tortoiz_blog' ),
				'scheme'   => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .entry-title',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'date_style_section',
			[
				'label' => __( 'Date Style', 'tortoiz_blog' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'     => __( 'Date Color', 'tortoiz_blog' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#727272',
				'selectors' => [
					'{{WRAPPER}} .entry-date span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'label'    => __( 'Typography', 'tortoiz_blog' ),
				'scheme'   => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .entry-date span',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'category_style_section',
			[
				'label' => __( 'Category Style', 'tortoiz_blog' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'category_color',
			[
				'label'     => __( 'Category Color', 'tortoiz_blog' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .entry-category a' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'category_hover_color',
			[
				'label'     => __( 'Category Hover Color', 'tortoiz_blog' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#D82E2A',
				'selectors' => [
					'{{WRAPPER}} .entry-category a:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'category_typography',
				'label'    => __( 'Typography', 'tortoiz_blog' ),
				'scheme'   => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .entry-category a',
			]
		);

		$this->end_controls_section();

	}

	// Widget Render Output

	protected function render() {

		$settings   = $this->get_settings_for_display();

		$category = $settings['category'];
		$total_count = $settings['total_count'];
		$order = $settings['order_by'];

		$args = [];
		if ($category == 'all') {
			$args=[
				'post_type' => 'post',
				'posts_per_page' => $total_count,
				'order' => $order,
			];
		} else {
			$args=[
				'post_type' => 'post', 
				'category_name'=>$category,
				'posts_per_page' => $total_count,
				'order' => $order,
			];
		}

		$blog = new \WP_Query($args);

		?>
		<div class="main-wrapper ptb-100 blog-style-two">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!--~~~~~ Start Site Main ~~~~~-->
                        <main class="site-main">
							<div id="masonry-posts" class="row">
								<?php
									if($blog->have_posts()) : while($blog->have_posts()) : $blog->the_post();
								?>
								<div class="col-lg-4 col-md-6 grid-item" style="position: absolute; left: 0px; top: 0px;">
								    <article class="post hentry post-grid style-three">

								    	<?php if ($settings['show_thumb']) : ?>
								        <div class="entry-thumb">
								            <figure class="thumb-wrap">
								                <a href="<?php the_permalink(); ?>">
								                    <img src="<?php the_post_thumbnail_url() ?>" alt="post">
								                </a>
								            </figure><!--./ thumb-wrap -->
								        </div><!--./ entry-thumb -->
								        <?php endif ?>

								        <div class="content-entry-wrap">
								            <div class="entry-meta-content">

								            	<?php if ($settings['show_date']) : ?>
								                <div class="entry-date">
								                    <?php 
								                        $archive_year  = get_the_time('y'); 
								                        $archive_month = get_the_time('m'); 
								                        $archive_day   = get_the_time('d'); 
								                    ?>
								                    <span><?php echo esc_html( get_the_date() ); ?> </span>
								                </div><!--./ entry-date -->
								                <?php endif ?>

								                <?php if ($settings['show_category']) : ?>
								                <div class="entry-category">
								                    <?php the_category( " " ); ?>
								                </div><!--./ entry-category -->
								                <?php endif ?>

								            </div><!--./ thumb-wrap -->
								            <div class="entry-content">
								                <h3 class="entry-title">
								                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								                </h3><!--./ entry-title -->
								            </div><!--./ entry-content -->
								        </div><!--./ content-entry-wrap -->
								    </article>
								</div>
								<?php
									endwhile; endif; wp_reset_postdata();
								?>
							</div>
						</main>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}