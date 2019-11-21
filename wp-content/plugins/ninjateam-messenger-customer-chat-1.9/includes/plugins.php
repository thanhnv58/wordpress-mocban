<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly;

if(!class_exists('NJT_CUSTOMER_CHAT_PLUGINS')){

	class NJT_CUSTOMER_CHAT_PLUGINS{

		public function __construct() {

			add_action('init', array($this,'njt_advoid_do_output_buffer')); //(1)

			add_action('wp_footer',array($this,'njt_customer_chat_wp_head'));

			add_action('wp_enqueue_scripts', array( $this, 'frontend_styles' ) );

			

		}

		

		public function njt_advoid_do_output_buffer(){

			ob_start(); // fixed : Warning: Cannot modify header information - headers already 



		}

		

		public function njt_customer_chat_wp_head(){

			$type = get_option("njt_customer_chat_facebook_messenger_hide_display");

		

			$app_display=get_option('njt_customer_chat_display');

			if(empty($app_display) || $app_display=="both") $check_display=1;

			if ($app_display=="mobile") $check_display==2;

			if ($app_display=="desktop") $check_display==3;

		

			if(empty($app_display) || $app_display=="both"){

				njt_show_customer_chat_messenger($type);

			}else if ($app_display=="desktop"){

				if (!wp_is_mobile() ){

					njt_show_customer_chat_messenger($type);

				}

			}else if ($app_display=="mobile"){

				if (wp_is_mobile() ){

					njt_show_customer_chat_messenger($type);

				}

			}

			

		} 

		public function frontend_styles(){

		

		}

		

	

	    /**/

	    

	}

}

new NJT_CUSTOMER_CHAT_PLUGINS();