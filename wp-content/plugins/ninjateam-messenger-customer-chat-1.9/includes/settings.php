<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;
if(!class_exists('NJT_CUSTOMER_CHAT_ADMIN_SETTINGS')){
  class NJT_CUSTOMER_CHAT_ADMIN_SETTINGS{
    public function __construct() {
      add_action('admin_menu', array($this, 'admin_menu_settings'));
      //add_action('admin_enqueue_scripts', array( $this, 'admin_styles' ) );
      //add_filter('plugin_row_meta', array($this,'action_link'),10, 2);
      add_filter( 'plugin_action_links', array($this,'plugin_action_links') , 100, 25 );
      add_action('admin_init',array($this,'njt_api_customer_chat_settings'));
      add_action( 'current_screen',array( $this,'njt_current_screen' ));
      add_action( 'wp_ajax_njt_cus_chat_reconnect', array( $this, 'njt_cus_chat_reconnect' ));
      add_action( 'wp_ajax_nopriv_njt_cus_chat_reconnect',array( $this, 'njt_cus_chat_reconnect' ));
    } 
    public function njt_api_customer_chat_settings(){
      /*if (!session_id()) {
          session_start();
      }
      ob_start(); // fixed : Warning: Cannot modify header information - headers already sent by (output started at (2)*/
      register_setting( 'njt_customer_chat','njt_customer_chat_app_id');
      register_setting( 'njt_customer_chat','njt_customer_chat_app_serect');
      register_setting( 'njt_customer_chat','njt_customer_chat_margin_bottom');
      register_setting( 'njt_customer_chat','njt_customer_chat_position_icon');
      register_setting('njt_customer_chat','njt_customer_chat_color_icon');
      register_setting('njt_customer_chat','njt_customer_chat_display');
      register_setting('njt_customer_chat','njt_customer_chat_list_page');
      register_setting('njt_customer_chat','njt_customer_chat_enable');
      register_setting('njt_customer_chat','njt_customer_chat_languages');
      register_setting('njt_customer_chat','njt_customer_chat_facebook_messenger_hide_display');
      register_setting('njt_customer_chat','njt_fb_facebook_messenger_hide_page');
      register_setting('njt_customer_chat','njt_fb_facebook_messenger_show_page');
      // update 12/2/18
      register_setting('njt_customer_chat','njt_fb_logged_in_greeting');
      register_setting('njt_customer_chat','njt_fb_logged_out_greeting');
      register_setting('njt_customer_chat','njt_fb_enable_minimized');
    }
    // update 12/2/18
    public function plugin_action_links( $actions, $plugin_file){
        static $plugin;
        //echo $plugin_file;
        //echo NJT_CUSTOMER_CHAT_FOLDER;
        if (!isset($plugin))
          $plugin = NJT_CUSTOMER_CHAT_FOLDER;
        if ($plugin == $plugin_file) {
            $setting= array('<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=njt-customer-chat-settings" title=" Settings">'.__( 'Settings', NJT_CUSTOMER_CHAT ).'</a>');
            $actions = array_merge($setting,$actions);
        }
        return $actions;
    }
    public function admin_menu_settings() {
      $user_token=get_option('njt_customer_chat_app_token');
      add_menu_page(__('Customer Chat',NJT_CUSTOMER_CHAT), __('Messenger Chat', NJT_CUSTOMER_CHAT),'manage_options', 'njt-customer-chat-settings', array($this, 'call_fb_api_setup'), NJT_CUSTOMER_CHAT_URL.'assets/images/icon.svg','59');
      add_submenu_page( 'njt-customer-chat-settings', 'Settings', 'Settings', 'manage_options', 'njt-customer-chat-settings', array($this,'call_fb_api_setup') );
      add_submenu_page( 'njt-customer-chat-settings', 'Messenger Plugins', 'Messenger Plugins', 'manage_options', 'njt-cus-chat-list-plugin', array($this,'call_fb_api_show_messenger_plugins') );
      add_submenu_page( 'njt-customer-chat-settings', 'Support', 'Support', 'manage_options', 'https://ninja.ticksy.com');
    }
    
    public function call_fb_api_setup(){
        require_once(NJT_CUSTOMER_CHAT_INC.'setup.php');
    }
    public function call_fb_api_show_messenger_plugins(){
       require_once(NJT_CUSTOMER_CHAT_INC.'list-plugin.php');
    }
    public function njt_current_screen(){
      $currentScreen = get_current_screen();
    //  echo $currentScreen->id;die;
        if($currentScreen->id=="toplevel_page_njt-customer-chat-settings"){
              add_action('admin_enqueue_scripts', array( $this, 'admin_styles_noiuislider' ) );
              add_action('admin_enqueue_scripts', array( $this, 'njt_admin_styles' ) );
              add_action('admin_footer', array( $this, 'admin_footer' ),1,1 );
        }else if($currentScreen->id=="messenger-chat_page_njt-cus-chat-list-plugin"){
              add_action('admin_enqueue_scripts', array( $this, 'njt_admin_styles' ) );
        }
    }
    public function njt_admin_styles(){
      wp_enqueue_style('njt_customer_chat_admin_style',NJT_CUSTOMER_CHAT_URL.'assets/css/admin.css');
      wp_enqueue_script('njt_customer_chat_admin_script',NJT_CUSTOMER_CHAT_URL.'assets/js/customer-chat-auto.js',array('jquery'),NJT_CUSTOMER_CHAT_VERSION); //,time()
      // Add the color picker css file       
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script('njt_customer_chat_admin_settings_script',NJT_CUSTOMER_CHAT_URL.'assets/js/settings.js',array('jquery','wp-color-picker'));//,time()
      // ADD MENU SELECT2
      wp_enqueue_style('njt-cus-chat-select2-style',NJT_CUSTOMER_CHAT_URL.'assets/css/select2/select2.min.css');
      wp_enqueue_script('njt-cus-chat-select2-script',NJT_CUSTOMER_CHAT_URL.'assets/css/select2/select2.full.min.js'); //,time()
    }
    public function admin_styles_noiuislider(){
        wp_enqueue_style('njt_customer_chat_admin_noiuislider_style',NJT_CUSTOMER_CHAT_URL.'assets/css/jquery.nouislider.css');
        wp_enqueue_style('njt_customer_chat_admin_custom_style',NJT_CUSTOMER_CHAT_URL.'assets/css/custom.css');
        wp_enqueue_script('njt_customer_chat_wNumb_admin_noiuislider_script',NJT_CUSTOMER_CHAT_URL.'assets/js/wNumb.js',array('jquery')); //,time()
        wp_enqueue_script('njt_customer_chat_admin_noiuislider_script',NJT_CUSTOMER_CHAT_URL.'assets/js/jquery.nouislider.min.js',array('jquery')); // ,time()
        wp_enqueue_script('njt_customer_chat_link_admin_noiuislider_script',NJT_CUSTOMER_CHAT_URL.'assets/js/jquery.liblink.js',array('jquery')); //,time()
    } 
    public function njt_cus_chat_reconnect(){
          if(isset($_POST['reconnect'])){
              delete_option('njt_customer_chat_app_token');
              delete_option('njt_customer_chat_app_id'); 
              delete_option('njt_customer_chat_app_serect'); 
              delete_option('njt_customer_chat_list_page');
              die("success");       
          }
    }
    //   ADMIN admin-footer
    public function admin_footer(){
      ?>
      <script type="text/javascript" src="<?php echo NJT_CUSTOMER_CHAT_URL.'assets/js/custom-select2.js'; ?>"></script>
       <?php
    }
  }
}
new NJT_CUSTOMER_CHAT_ADMIN_SETTINGS();
