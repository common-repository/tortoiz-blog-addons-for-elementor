<?php


class tortoiz_blog_Sidebar extends \Elementor\Widget_Base {

	// Widget Name

	public function get_name() {
		return 'magazine-sidebar';
	}

	// Widget Titke

	public function get_title() {
		return __( 'tortoiz_blog Sidebar', 'tortoiz_blog' );
	}

	// Widget Icon

	public function get_icon() {
		return 'fa fa-list-alt';
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

		// Controls

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content Controls', 'tortoiz_blog' ),
			]
		);

		$this->end_controls_section();

	}

	// Style Control

	protected function register_style_controls() {

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style Controls', 'tortoiz_blog' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();

	}

	// Widget Render Output

	protected function render() {

		$settings   = $this->get_settings_for_display();

		?>
		<div class="row">
			<!--~~~~~ Start sidebar ~~~~~-->
			<div class="col-lg-12">
			    <div class="sidebar">
			        <?php
			            if ( is_active_sidebar( "sidebar" ) ) {
			                dynamic_sidebar( "sidebar" );
			            }
			        ?>
			    </div>
			</div><!--~./ end sidebar ~-->
		</div>
		<?php
	}
}