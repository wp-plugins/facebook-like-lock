<?php
class SlSettingsPage
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
      $sl_settings_page =  add_options_page(
            'Settings Admin', 
            'Facebook Like Lock', 
            'manage_options', 
            'wp-like-lock-setting-admin', 
            array( $this, 'create_admin_page' )
        );
		
	add_action( "admin_head-{$sl_settings_page}", array($this,'load_sl_wp_style') );
    }

	public function load_sl_wp_style(){
	 
	 //wp_enqueue_style( 'adminp',plugins_url( 'css/admin.css', __FILE__ ) );

     wp_enqueue_script( 'll_shortcode_gen',plugins_url( 'js/wp-like-lock.js', __FILE__ ) );
	}
    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'sl_option_name' );
		
		//echo "<pre>";print_r($this->options);die;
		
		
        ?>
       <div class="wrap columns-2">
            <?php screen_icon(); ?>
            <h2>Facebook Like Lock </h2>     
		<div id="poststuff" class="metabox-holder has-right-sidebar">	

			<div id="post-body">
			<div id="post-body-content">
			
				<div class="stuffbox">
					<div class="inside">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'sl_option_group' );   
                do_settings_sections( 'wp-like-lock-setting-admin' );
                submit_button(); 
            ?>
            </form>
			</div>
        </div>
        </div>
        </div>
        </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'sl_option_group', // Option group
            'sl_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'wp-like-lock-setting-admin' // Page
        );  

      add_settings_field(
            'fb_app_id', 
            'Facebook AppId', 
            array( $this, 'fb_app_id_callback' ), 
            'wp-like-lock-setting-admin', 
            'setting_section_id'
        );   

		add_settings_field(
            'sl_default_msg', 
            'Lock Message', 
            array( $this, 'sl_default_msg_callback' ), 
            'wp-like-lock-setting-admin', 
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
        if( isset( $input['fb_app_id'] ) )
        $input['fb_app_id'] = ( $input['fb_app_id'] );
		
		if( isset( $input['timer_time'] ) )
        $input['timer_time'] = absint( $input['timer_time'] ) !=0?absint( $input['timer_time'] ):10;

		if(isset($input['sl_default_msg']))
         $input['sl_default_msg'] =  $input['sl_default_msg'] !='' ?  esc_attr( $input['sl_default_msg']) : 'Like to get the lock/hidden content';
        return $input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
      //  print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function tw_user_callback()
    {
        printf(
            '<input type="text" id="twitter_username" size="40" name="sl_option_name[twitter_username]" value="%s" />',
            isset( $this->options['twitter_username'] ) ? esc_attr( $this->options['twitter_username']) : 'buffernow'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function fb_app_id_callback()
    {
        printf(
            '<input type="text" id="fb_app_id" size="40" name="sl_option_name[fb_app_id]" value="%s" />',
            isset( $this->options['fb_app_id'] ) ? esc_attr( $this->options['fb_app_id']) : ''
        );
    }
	
	public function sl_default_msg_callback() {
	
	  printf(
            '<textarea type="text" style="width: 355px; height: 109px;" id="sl_default_msg" name="sl_option_name[sl_default_msg]"  />%s</textarea>',
            isset( $this->options['sl_default_msg'] ) ? esc_attr( $this->options['sl_default_msg']) : ''
        );
	}
		
}

if( is_admin() )
    $sl_settings_page = new SlSettingsPage();