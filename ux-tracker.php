<?php 

/*
Plugin Name: UX Tracker
Plugin URI: 
Description: Analyse user behaviour with Google Analytics and Inspectlet. Easily utilise custom dimensions and advanced heat mapping to determine to understand your website better.
Version: 1.01
Author: Ben Parry
Author URI: http://uiux.me
*/

if ( ! defined( 'ABSPATH' ) ) exit;


class UX_Tracker {

	public static $currentUser;
	public static $analyticsID;
	public static $custom_dim_1;
	public static $custom_dim_2;
	public static $inspectletID;
	public static $scrolldepthTracking;
	public static $trackEverything;

	protected function __construct() {

		self::$currentUser = false;
		$currentUser = is_user_logged_in();
		if($currentUser != false) {
			self::$currentUser = wp_get_current_user();
		}

		self::$analyticsID = get_option('ux_tracker_analytics_id', '');
		self::$inspectletID = get_option('ux_tracker_inspectlet_id', '');
		self::$custom_dim_1 = get_option('ux_tracker_analytics_custom_dim_1', '');
		self::$custom_dim_2 = get_option('ux_tracker_analytics_custom_dim_2', '');
		self::$scrolldepthTracking = get_option('ux_tracker_scroll_depth', false);
		self::$trackEverything = get_option('ux_tracker_track_everything', false);

	}

	protected function __clone() {

	}

	public static function Instance() {
		static $inst = null;
        if ($inst === null) {
            $inst = new UX_Tracker();
        }
        return $inst;
	}


	public static function spitAnalyticsEmbed() {

		$analyticsID = self::$analyticsID;

		if($analyticsID == '') {
			return '';
		}

		$customDimensions = self::CustomDimensions();
		$scrolldepth = self::ScrollDepthScript();
		$trackEverything = self::TrackEverything();

		ob_start();
		include 'analytics-embed.php';
		$embed_code = ob_get_contents();
		ob_end_clean();

		print $embed_code;

	}

	public static function spitInspectletEmbed() {

		$inspectletID = self::$inspectletID;

		if($inspectletID == '') {
			return '';
		}

		$inspectletEvent = self::InspectletEvent();

		ob_start();
		include 'inspectlet-embed.php';
		$embed_code = ob_get_contents();
		ob_end_clean();

		print $embed_code;

	}

	public static function ScrollDepthScript() {

		$scrolldepthScript = '';
		if(self::$scrolldepthTracking) {

			$scrolldepthScript = 'jQuery(function() {jQuery.scrollDepth();});';

		}

		return $scrolldepthScript;

	}

	public static function TrackEverything() {

		$trackEverythingScript = '';
		if(self::$trackEverything) {

			$trackEverythingScript = 'jQuery("body").track({options: {universal:true}});';

		}

		return $trackEverythingScript;

	}

	private static function CustomDimensions() {

		$dim1 = self::$custom_dim_1;
		if($dim1 == '' | is_numeric($dim1) == false) {
			$dim1 = 1;
		}
		$dim2 = self::$custom_dim_2;
		if($dim2 == '' | is_numeric($dim2) == false) {
			$dim2 = 2;
		}

		$customDimensionString = '';

		if(self::$currentUser != false) {

			//user_id
			$user_dimension = "ga('set', 'dimension".$dim1."', ".self::$currentUser->ID.");\n";
			$customDimensionString .= $user_dimension;

			//user unique identifier
			if(self::$currentUser->ux_tracker_user_identifier != '') {
				$unique_identifier = "ga('set', 'dimension".$dim2."', '".self::$currentUser->ux_tracker_user_identifier."');\n";
				$customDimensionString .= $unique_identifier;
			}

		}

		return $customDimensionString;

	}

	private static function InspectletEvent() {

		$inspectletEventString = '';

		if(self::$currentUser != false) {

			$userData = array(
				'userid'			=>	self::$currentUser->ID,
				'email'				=>	self::$currentUser->user_email
				);

			if(self::$currentUser->ux_tracker_user_identifier != '') {
				$userData['unique_identifier'] = self::$currentUser->ux_tracker_user_identifier;
			}

			$inspectletEventString .= "__insp.push(['tagSession', ".json_encode($userData)."]);\n";
		}

		return $inspectletEventString;

	}

}

//ux tracker admin settings

add_action('admin_menu', 'ux_tracker_admin_menu');

function ux_tracker_admin_menu() {
	add_menu_page('UX Tracker Plugin Settings', 'UX Tracker', 'administrator', __FILE__, 'ux_tracker_settings_page', 'dashicons-visibility', '105' );
	add_action( 'admin_init', 'ux_tracker_settings' );
}

function ux_tracker_settings() {
	register_setting( 'ux-tracker-settings-group', 'ux_tracker_analytics_id' );
	register_setting( 'ux-tracker-settings-group', 'ux_tracker_analytics_custom_dim_1' );
	register_setting( 'ux-tracker-settings-group', 'ux_tracker_analytics_custom_dim_2' );
	register_setting( 'ux-tracker-settings-group', 'ux_tracker_inspectlet_id' );
	register_setting( 'ux-tracker-settings-group', 'ux_tracker_scroll_depth' );
	register_setting( 'ux-tracker-settings-group', 'ux_tracker_track_everything' );
	
}

function ux_tracker_settings_page() {
	include 'admin/ux-tracker-admin.php';
}


//ux tracker frontend head
add_action('wp_head', 'ux_tracker_head_code');

function ux_tracker_head_code() {
	$uxtracker = UX_Tracker::Instance();
	$uxtracker::spitAnalyticsEmbed();
	$uxtracker::spitInspectletEmbed();
}


//ux tracker scroll depth
function ux_tracker_load_scrolldepth_js(){
	wp_register_script('ux_tracker_scrolldepth', plugins_url( 'js/scrolldepth.min.js', __FILE__ ), array( 'jquery' ) );
	wp_register_script('ux_tracker_trackeverything', plugins_url( 'js/trackeverything.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'ux_tracker_scrolldepth' );
	wp_enqueue_script( 'ux_tracker_trackeverything' );
}
add_action('wp_enqueue_scripts', 'ux_tracker_load_scrolldepth_js');


//displaying ux tracker extra user fields
add_action( 'show_user_profile', 'ux_tracker_extra_user_fields' );
add_action( 'edit_user_profile', 'ux_tracker_extra_user_fields' );
add_action( 'user_new_form', 'ux_tracker_extra_user_fields' );

function ux_tracker_extra_user_fields( $user ) {

	if( is_admin() ) {
		include 'admin/ux-tracker-extra-user-fields.php';
	}
}


//saving ux tracker extra user fields
add_action( 'personal_options_update', 'ux_tracker_save_extra_user_data' );
add_action( 'edit_user_profile_update', 'ux_tracker_save_extra_user_data' );
add_action('user_register', 'ux_tracker_save_extra_user_data');

function ux_tracker_save_extra_user_data( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) && !is_admin() )
		return false;

	update_user_meta( $user_id, 'ux_tracker_user_identifier', $_POST['ux_tracker_user_identifier'] );
}

?>