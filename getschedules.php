<?php
/*
Plugin Name: Get EW Schedules
Plugin URI:  
Description: Get EverWebinar Schedules
Version:     1.0.0
Author:      Armels
Author URI:  
License:     
License URI: 
Text Domain: divi-get-ew-schedules
Domain Path: /languages
*/


if ( ! function_exists( 'divi_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 2.0.0
 */
function divi_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Getschedules.php';
}
add_action( 'divi_extensions_init', 'divi_initialize_extension' );
endif;

add_action( 'wp_enqueue_scripts', 'lead_enqueues' );
add_action('wp_ajax_form_handler', 'form_handler');
add_action('wp_ajax_nopriv_form_handler', 'form_handler');
add_action('wp_ajax_form_handler_ny', 'form_handler_ny');
add_action('wp_ajax_nopriv_form_handler_ny', 'form_handler_ny');

function lead_enqueues() 
{
	global $post;
	if (is_a($post, 'WP_Post')) {
		wp_enqueue_script('script_sec', plugins_url('/js/script.js', __File__), array('jquery', 'jquery-ui-core', 'jquery-ui-autocomplete'), '1.1.0');
	}
	if (isset($_SERVER['HTTP_REFERER']) && filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL)) {
		$referer = $_SERVER['HTTP_REFERER'];
		$user_agent = filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_SPECIAL_CHARS);
		wp_localize_script('script_sec', 'myAjax_sec', array('ajaxurl' => admin_url('admin-ajax.php'), 'ip' => $ip, 'referer' => $referer, 'user_agent' => $user_agent));

	} else {
			$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
			$user_agent = isset($_SERVER['HTTP_REFERER']) ? filter_var($_SERVER['HTTP_REFERER'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
			wp_localize_script('script_sec', 'myAjax_sec', array('ajaxurl' => admin_url('admin-ajax.php'), 'ip' => $ip, 'referer' => $referer, 'user_agent' => $user_agent));
	}
}

function form_handler()
{

	$post_fields = 'api_key=93197255-f292-4650-a973-5bd4a0ffaf53&webinar_id=1&first_name='.$_POST['first_name'].'&phone_country_code='.$_POST['phone_country_code'].'&phone='.$_POST['phone'].'&email='.$_POST['email'].'&schedule='.$_POST['schedule'];
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.webinarjam.com/everwebinar/register',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $post_fields,
		CURLOPT_HTTPHEADER => array(
		'Content-Type: application/x-www-form-urlencoded',
		'Cookie: __cfduid=d0a6cf6c8b50dd4725266715ff4735cd91617788952; wj4s=u5NBNu7NveymPp8In55Ixzl4YEWlNiClqnWSlHlv; XSRF-TOKEN=eyJpdiI6ImhXdWFvNmY3WVlxUTQ1TEJKanRYZVE9PSIsInZhbHVlIjoiaEFHajdpZkN2REdvSUVSVkNuVzhPR2I1VlVMSHJrM3dqSHFyUjhTNkVkYWlCNm1pWnlCMFU0b28zczhWaWg2SCIsIm1hYyI6IjBiNWY0YTRjYjU4ZjNiMzE1NWVhMTQ3N2E0YTk1NDg3ZmRkMmQxYTE4YmU0ZjA2NzBiZjMwZjMzMDRjNTkxZDgifQ%3D%3D'
		),
	));

	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);
	
	if (strcasecmp($response['status'], 'success') == 0) {
		echo json_encode($response);
	} else {
		echo json_encode(['status' => false, 'message' => $response['message']]);
	}
	
	die();
}

function form_handler_ny()
{

	$post_fields = 'api_key=93197255-f292-4650-a973-5bd4a0ffaf53&webinar_id=2&first_name='.$_POST['first_name'].'&phone_country_code='.$_POST['phone_country_code'].'&phone='.$_POST['phone'].'&email='.$_POST['email'].'&schedule='.$_POST['schedule'];
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.webinarjam.com/everwebinar/register',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $post_fields,
		CURLOPT_HTTPHEADER => array(
		'Content-Type: application/x-www-form-urlencoded',
		'Cookie: __cfduid=d0a6cf6c8b50dd4725266715ff4735cd91617788952; wj4s=u5NBNu7NveymPp8In55Ixzl4YEWlNiClqnWSlHlv; XSRF-TOKEN=eyJpdiI6ImhXdWFvNmY3WVlxUTQ1TEJKanRYZVE9PSIsInZhbHVlIjoiaEFHajdpZkN2REdvSUVSVkNuVzhPR2I1VlVMSHJrM3dqSHFyUjhTNkVkYWlCNm1pWnlCMFU0b28zczhWaWg2SCIsIm1hYyI6IjBiNWY0YTRjYjU4ZjNiMzE1NWVhMTQ3N2E0YTk1NDg3ZmRkMmQxYTE4YmU0ZjA2NzBiZjMwZjMzMDRjNTkxZDgifQ%3D%3D'
		),
	));

	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);
	
	if (strcasecmp($response['status'], 'success') == 0) {
		echo json_encode($response);
	} else {
		echo json_encode(['status' => false, 'message' => $response['message']]);
	}
	
	die();
}
