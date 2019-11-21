<?php 
if (!defined('ABSPATH')) die('-1');
/**
* 
*/
// 
//require_once('fbConnet.php');
class FB_Customer_Chat_Ajax
{
	public $fb;
	function __construct()
	{
	}
	public static function init(){
		add_action( 'init', array( __CLASS__, 'njt_cus_define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'njt_cus_do_fb_cus_chat_ajax' ), 0 );
	    self::add_ajax_events();
	}
	public static function njt_cus_define_ajax(){
		if ( ! empty( $_GET['cus-chat-ajax'] ) ) {
				if ( ! defined( 'DOING_AJAX' ) ) {
					define( 'DOING_AJAX', true );
				}
				if ( ! defined( 'CUS_CHAT_DOING_AJAX' ) ) {
					define( 'CUS_CHAT_DOING_AJAX', true );
				}
			 if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
					ini_set( 'display_errors', 0 );
				}
				$GLOBALS['wpdb']->hide_errors();
			}
	}
	private static function cus_chat_ajax_headers() {
			send_origin_headers();
			@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
			@header( 'X-Robots-Tag: noindex' );
			send_nosniff_header();
			nocache_headers();
			status_header( 200 );
		}
	public static function njt_cus_do_fb_cus_chat_ajax(){
			global $wp_query;
			if ( ! empty( $_GET['cus-chat-ajax'] )) {
				$wp_query->set( 'cus-chat-ajax', sanitize_text_field( $_GET['cus-chat-ajax'] ) );
			}
			if ( $action = $wp_query->get( 'cus-chat-ajax' ) ) {
				self::cus_chat_ajax_headers();
				do_action( 'wp_ajax_' . sanitize_text_field( $action ) );
				die();
			}
		}
		public static function add_ajax_events() {
			$ajax_events = array(
				'get__info__token'=>true,
				);
			foreach ( $ajax_events as $ajax_event => $nopriv ) {
				add_action( 'wp_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ));
				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_' . $ajax_event, array( __CLASS__, $ajax_event ) );
					add_action( 'wp_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
				}
			}
		}
		
		public static function get__info__token(){
			$token = isset($_POST['token'])?$_POST['token']:'';
			$app_id = isset($_POST['app_id'])?$_POST['app_id']:'';
			$secret = isset($_POST['secret'])?$_POST['secret']:'';
			//app_id
			//secret
			update_option('njt_customer_chat_app_token', $token );
			update_option('njt_customer_chat_app_id',$app_id);
			update_option('njt_customer_chat_app_serect',$secret);
			wp_send_json_success( );
			exit();
		}
		
}
FB_Customer_Chat_Ajax::init();