<?php
class DethemekitSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Dethemekit', 
            'manage_options', 
            'dethemekit-setting-page', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
      $this->options = get_option( 'dethemekit_option' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'DethemeKit Settings', 'dethemekit-addons-for-elementor' ); ?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'dethemekit_option_group' );
                do_settings_sections( 'dethemekit-setting-page' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {        
      register_setting(
          'dethemekit_option_group', // Option group
          'dethemekit_option', // Option name
          array( $this, 'sanitize' ) // Sanitize
      );

      add_settings_section(
          'setting_section_id', // ID
          'Extensions', // Title
          array( $this, 'print_section_info' ), // Callback
          'dethemekit-setting-page' // Page
      );  

      add_settings_field(
          'de_scroll_animation', 
          'De Scroll Animation', 
          array( $this, 'de_scroll_animation_callback' ), 
          'dethemekit-setting-page', 
          'setting_section_id'
      );

      add_settings_field(
        'de_reveal_animation', 
        'De Reveal Animation', 
        array( $this, 'de_reveal_animation_callback' ), 
        'dethemekit-setting-page', 
        'setting_section_id'
      );

      add_settings_field(
        'de_staggering', 
        'De Staggering', 
        array( $this, 'de_staggering_callback' ), 
        'dethemekit-setting-page', 
        'setting_section_id'
      );

      add_settings_field(
        'de_carousel', 
        'De Carousel', 
        array( $this, 'de_carousel_callback' ), 
        'dethemekit-setting-page', 
        'setting_section_id'
      );

      add_settings_field(
        'de_gallery', 
        'De Gallery', 
        array( $this, 'de_gallery_callback' ), 
        'dethemekit-setting-page', 
        'setting_section_id'
      );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['de_scroll_animation'] ) )
          $new_input['de_scroll_animation'] = absint( $input['de_scroll_animation'] );

        if( isset( $input['de_reveal_animation'] ) )
          $new_input['de_reveal_animation'] = absint( $input['de_reveal_animation'] );

        if( isset( $input['de_staggering'] ) )
          $new_input['de_staggering'] = absint( $input['de_staggering'] );

        if( isset( $input['de_carousel'] ) )
          $new_input['de_carousel'] = absint( $input['de_carousel'] );

        if( isset( $input['de_gallery'] ) )
          $new_input['de_gallery'] = absint( $input['de_gallery'] );
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        echo esc_html__( 'Select the extensions to be activated', 'dethemekit-addons-for-elementor' );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function de_scroll_animation_callback() {
      $option_value = '0';
      if( isset( $this->options['de_scroll_animation'] ) ) {
        $option_value = $this->options['de_scroll_animation'];
      }

      printf('<input id="de_scroll_animation" name="dethemekit_option[de_scroll_animation]" type="checkbox" value="1" %s /> %s',checked( '1', esc_attr( $option_value ), false ), esc_html__( 'Enabled', 'dethemekit-addons-for-elementor' ) );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function de_reveal_animation_callback() {
      $option_value = '0';
      if( isset( $this->options['de_reveal_animation'] ) ) {
        $option_value = $this->options['de_reveal_animation'];
      }

      printf('<input id="de_reveal_animation" name="dethemekit_option[de_reveal_animation]" type="checkbox" value="1" %s /> %s',checked( '1', esc_attr( $option_value ), false ), esc_html__( 'Enabled', 'dethemekit-addons-for-elementor' ) );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function de_staggering_callback() {
      $option_value = '0';
      if( isset( $this->options['de_staggering'] ) ) {
        $option_value = $this->options['de_staggering'];
      }

      printf('<input id="de_staggering" name="dethemekit_option[de_staggering]" type="checkbox" value="1" %s /> %s',checked( '1', esc_attr( $option_value ), false ), esc_html__( 'Enabled', 'dethemekit-addons-for-elementor' ) );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function de_carousel_callback() {
      $option_value = '0';
      if( isset( $this->options['de_carousel'] ) ) {
        $option_value = $this->options['de_carousel'];
      }

      printf('<input id="de_carousel" name="dethemekit_option[de_carousel]" type="checkbox" value="1" %s /> %s',checked( '1', esc_attr( $option_value ), false ), esc_html__( 'Enabled', 'dethemekit-addons-for-elementor' ) );
      printf('&nbsp;&nbsp;<a target="_blank" href="https://detheme.helpscoutdocs.com/article/368-how-to-use-decarousel">How to Use De Carousel ?</a>');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function de_gallery_callback() {
      $option_value = '0';
      if( isset( $this->options['de_gallery'] ) ) {
        $option_value = $this->options['de_gallery'];
      }

      printf('<input id="de_gallery" name="dethemekit_option[de_gallery]" type="checkbox" value="1" %s /> %s',checked( '1', esc_attr( $option_value ), false ), esc_html__( 'Enabled', 'dethemekit-addons-for-elementor' ) );
    }

}

if( is_admin() )
    $my_settings_page = new DethemekitSettingsPage();