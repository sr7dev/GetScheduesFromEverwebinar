<?php

class GetSchedules extends ET_Builder_Module {

	public $slug       = 'get_schedules';
	public $vb_support = 'on';
	
	public $uber_url = 'rideshare_uber_shortcode';
	public $uber_btn = 'rideshare_uber_btn_shortcode';
	public $lyft_url = 'rideshare_lyft_shortcode';
	public $lyft_btn = 'rideshare_lyft_btn_shortcode';
	public $rideshare_btn = 'rideshare_btn_shortcode';
	public $rideshare_btn_org = 'rideshare_btn_org_shortcode';

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => 'Artem Makhulov',
		'author_uri' => '',
	);

	public function init() {
		$this->name = esc_html__( 'RideShare', 'divi-rideshare' );
		
		wp_enqueue_style( 
			'ride-sharing', 
			plugins_url('/rideshare/includes/modules/RideSharing/style.css') 
		);
		
		// Toggle settings
		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'button_type'=> esc_html__( 'RideShare Type', 'ride_sharing' ),
				),
			),
		);

		$this->main_css_element = '%%order_class%%';

		$this->custom_css_fields = array(
			'uber_container' => array(
				'label'    => esc_html__( 'Uber Button Container Style', 'et_builder' ),
				'selector' => '%%order_class%% .uber-ride-container',
			),
			'uber_container-a' => array(
				'label'    => esc_html__( 'Uber Button Style', 'et_builder' ),
				'selector' => '%%order_class%% .uber-ride-container a',
			),
			'uber_container-before' => array(
				'label'    => esc_html__( 'Uber Button Before Style', 'et_builder' ),
				'selector' => '%%order_class%% .uber-ride-container a:before',
			),
			'lyft_container' => array(
				'label'    => esc_html__( 'Lyft Button Container Style', 'et_builder' ),
				'selector' => '%%order_class%% .lyft-ride-container',
			),
			'lyft_container-a' => array(
				'label'    => esc_html__( 'Lyft Button Style', 'et_builder' ),
				'selector' => '%%order_class%% .lyft-ride-container a',
			),
			'lyft_container-before' => array(
				'label'    => esc_html__( 'Lyft Button Before Style', 'et_builder' ),
				'selector' => '%%order_class%% .lyft-ride-container a:before',
			),
		);
	}

	public function get_fields() {
		return array(
			'button_type' => array(
				'label'           => esc_html__( 'Type', 'divi-rideshare' ),
				'description'     => esc_html__( 'Select rideshare button type.', 'divi-rideshare' ),
				'type'				=> 'select',
				'option_category'	=> 'basic_option',
				'options'			=> array(
					'uber'	=> esc_html__( 'Uber', 'divi-rideshare' ),
					'lyft'	=> esc_html__( 'Lyft', 'divi-rideshare' ),
				),
				'default'			=> 'uber',
				'toggle_slug'     => 'button_type',
			),
			'uber_api_key' => array(
				'label'           => esc_html__( 'API Key', 'divi-rideshare' ),
				'description'     => esc_html__( 'Input Uber API Key.', 'divi-rideshare' ),
				'type'				=> 'text',
				'option_category'	=> 'basic_option',
				'default'			=> 'Kqc4_ey5tqo-SZNBF3hXt8gW1uh2EyvR',
				'toggle_slug'     => 'button_type',
				'show_if'				=> array(
						'button_type' 	=> 'uber'
				),
			),
			'lyft_api_key' => array(
				'label'           => esc_html__( 'API Key', 'divi-rideshare' ),
				'description'     => esc_html__( 'Input Lyft API Key.', 'divi-rideshare' ),
				'type'				=> 'text',
				'option_category'	=> 'basic_option',
				'default'			=> 'R9Mwmd-SzCYr',
				'toggle_slug'     => 'button_type',
				'show_if'				=> array(
						'button_type' 	=> 'lyft'
				),
			),
			'nickname' => array(
				'label'           => esc_html__( 'Name', 'divi-rideshare' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the nickname of the destination.', 'divi-rideshare' ),
				'toggle_slug'     => 'button_type',
				'dynamic_content' => 'text',
			),
			'address' => array(
				'label'           => esc_html__( 'Adress', 'divi-rideshare' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the address of the destination.', 'divi-rideshare' ),
				'toggle_slug'     => 'button_type',
				'dynamic_content' => 'text',
			),
			'latitude' => array(
				'label'           => esc_html__( 'Latitude', 'divi-rideshare' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the latitude of the destination.', 'divi-rideshare' ),
				'toggle_slug'     => 'button_type',
				'dynamic_content' => 'text',
			),
			'longitude' => array(
				'label'           => esc_html__( 'Longitude', 'divi-rideshare' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the longitude of the destination.', 'divi-rideshare' ),
				'toggle_slug'     => 'button_type',
				'dynamic_content' => 'text',
			),
		);
	}

	public function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if (isset($_SERVER['REMOTE_ADDR'])) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}

	function rideshare_uber_shortcode($atts) {
		if (!$atts)
			return;

		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		$userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);
		
		$dest_nickname = $atts['destination'];
		$address = $atts['address'];
		$dest_addr = rawurlencode($dest_nickname . ", " . $address);
		$dest_lat = $atts['latitude'];
		$dest_long = $atts['longitude'];
		$client_key = 'Kqc4_ey5tqo-SZNBF3hXt8gW1uh2EyvR';// $this->props['uber_api_key'];

		return $this->uber_url = sprintf(
			'https://m.uber.com/?action=setPickup&client_id=%1$s&pickup[nickname]=MyLocation&pickup[formatted_address]=%8$s&pickup[latitude]=%5$s&pickup[longitude]=%6$s&dropoff[nickname]=%7$s&dropoff[formatted_address]=%2$s&dropoff[latitude]=%3$s&dropoff[longitude]=%4$s&_ga=2.53701928.1689161207.1573021625-829624440.1572458222', 
			$client_key, 
			$dest_addr, 
			$dest_lat, 
			$dest_long,
			$userLat,
			$userLng,
			$dest_nickname,
			$userAddr
		);
	}
	
	function rideshare_lyft_shortcode($atts) {
		if (!$atts)
			return;

		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		// $userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);
		
		// $dest_nickname = $this->props['nickname'];
		// $address = $this->props['address'];
		// $dest_addr = rawurlencode($dest_nickname . ", " . $address);
		$dest_lat = $atts['latitude'];
		$dest_long = $atts['longitude'];
		$client_key = 'R9Mwmd-SzCYr';// $this->props['lyft_api_key'];

		return $this->lyft_url = sprintf(
			'https://lyft.com/ride?id=lyft&pickup[latitude]=%4$s&pickup[longitude]=%5$s&partner=%1$s&destination[latitude]=%2$s&destination[longitude]=%3$s', 
			$client_key, //R9Mwmd-SzCYr
			$dest_lat, 
			$dest_long,
			$userLat,
			$userLng
		);
	}
	
	function rideshare_uber_btn_shortcode($atts) {
		if (!$atts)
			return;

		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		$userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);
		
		global $post;
		$address = $atts['address'];// get_field("acf_address", $post->ID);
		$hospitalLat = $atts['latitude'];// get_field("acf_lat", $post->ID);
		$hospitalLng = $atts['longitude'];// get_field("acf_lng", $post->ID);
		$destName = $atts['destination'];
		$hospital_address = rawurlencode($destName . ", " . $address);// ($post->post_title . ", " . $address);
		$hospital_nickname = $destName;// $post->post_title;
		$dest_nickname = $hospital_nickname;
		// $address = $hospital_address;
		$dest_addr = $hospital_address;
		$dest_lat = $hospitalLat;
		$dest_long = $hospitalLng;
		$client_key = 'Kqc4_ey5tqo-SZNBF3hXt8gW1uh2EyvR';// $this->props['uber_api_key'];

		return $this->uber_btn = sprintf(
			'<div class="uber-ride-container">
				<div class="uber-ride-inner">
					<a href="https://m.uber.com/?action=setPickup&client_id=%1$s&pickup[nickname]=MyLocation&pickup[formatted_address]=%8$s&pickup[latitude]=%5$s&pickup[longitude]=%6$s&dropoff[nickname]=%7$s&dropoff[formatted_address]=%2$s&dropoff[latitude]=%3$s&dropoff[longitude]=%4$s&_ga=2.53701928.1689161207.1573021625-829624440.1572458222">
						<div class="btn-description-container">
							<div class="ride-title">
								<div>Get a ride</div>
							</div>
							<div class="btn-description"> 
								<div>3 MIN AWAY</div>
								<div>$8-10 on UberX</div>
							</div>
						</div>
					</a>
				</div>
			</div>', 
			$client_key, 
			$dest_addr, 
			$dest_lat, 
			$dest_long,
			$userLat,
			$userLng,
			$dest_nickname,
			$userAddr
		);
	}

	function rideshare_lyft_btn_shortcode($atts) {
		if (!$atts)
			return;
			
		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		// $userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);
		
		// $dest_nickname = $this->props['nickname'];
		// $address = $this->props['address'];
		// $dest_addr = rawurlencode($dest_nickname . ", " . $address);
		global $post;
		// $address = get_field("acf_address", $post->ID);
		$hospitalLat = $atts['latitude'];// get_field("acf_lat", $post->ID);
		$hospitalLng = $atts['longitude'];// get_field("acf_lng", $post->ID);
		
		// $hospital_address = rawurlencode($post->post_title . ", " . $address);
		// $hospital_nickname = $post->post_title;
		$dest_lat = $hospitalLat;
		$dest_long = $hospitalLng;
		$client_key = 'R9Mwmd-SzCYr'; //$this->props['lyft_api_key'];

		return $this->lyft_btn = sprintf(
			'<div class="lyft-ride-container">
				<div class="lyft-ride-inner">
					<a href="https://lyft.com/ride?id=lyft&pickup[latitude]=%4$s&pickup[longitude]=%5$s&partner=%1$s&destination[latitude]=%2$s&destination[longitude]=%3$s">
						<div class="btn-description-container">
							<div class="lyft-ride-title">
								<div>Get a ride</div>
								<div class="lyft-btn-description-1">Lyft in 4min</div>
							</div>
							<div class="lyft-btn-description-2"> 
								<div>$8-10</div>
							</div>
						</div>
					</a>
				</div>
			</div>', 
			$client_key, //R9Mwmd-SzCYr
			$dest_lat, 
			$dest_long,
			$userLat,
			$userLng
		);
	}

	function rideshare_btn_shortcode($atts, $content = null) {
		$pieces = explode(", ", $content);
		// print_r($pieces);
		$hospital = $city = $state = $zipcode = $lat = $lng = '';
		foreach ($pieces as $piece) {
			if (strpos($piece, 'hospital') !== false) {
				$hospital = substr($piece, strpos($piece, 'hospital') + strlen('hospital') + 1);
			} else if (strpos($piece, 'city') !== false) {
				$city = substr($piece, strpos($piece, 'city') + strlen('city') + 1);
			} else if (strpos($piece, 'state') !== false) {
				$state = substr($piece, strpos($piece, 'state') + strlen('state') + 1);
			} else if (strpos($piece, 'zipcode') !== false) {
				$zipcode = substr($piece, strpos($piece, 'zipcode') + strlen('zipcode') + 1);
			} else if (strpos($piece, 'lat') !== false) {
				$lat = substr($piece, strpos($piece, 'lat') + strlen('lat') + 1);
			} else if (strpos($piece, 'lng') !== false) {
				$lng = substr($piece, strpos($piece, 'lng') + strlen('lng') + 1);
			} else {
				continue;
			}
		}
		$hospital_address = rawurlencode($hospital . ", " . $city . " " . $state . ", " . $zipcode . ", " . "USA");

		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		$userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);

		$uber_key = 'Kqc4_ey5tqo-SZNBF3hXt8gW1uh2EyvR';
		$lyft_key = 'R9Mwmd-SzCYr';
		return $this->rideshare_btn = sprintf(
			'<div class="rideshare-container">
				<div class="black-blur-screen">
					<div class="hospital_name">%9$s</div>
					<div class="rideshare_btn_container">
						<div class="lyft-btn">
							<a href="https://lyft.com/ride?id=lyft&pickup[latitude]=%7$s&pickup[longitude]=%8$s&partner=%2$s&destination[latitude]=%4$s&destination[longitude]=%5$s">
								Get a ride
							</a>
						</div>
						<div class="uber-btn">
							<a href="https://m.uber.com/?action=setPickup&client_id=%1$s&pickup[nickname]=MyLocation&pickup[formatted_address]=%6$s&pickup[latitude]=%7$s&pickup[longitude]=%8$s&dropoff[nickname]=%9$s&dropoff[formatted_address]=%3$s&dropoff[latitude]=%4$s&dropoff[longitude]=%5$s&_ga=2.53701928.1689161207.1573021625-829624440.1572458222">
								Get a ride
							</a>
						</div>
					</div>
				</div>
			</div>',
			$uber_key, // 1
			$lyft_key, // 2
			$hospital_address, // 3
			$lat, // 4
			$lng, // 5
			$userAddr, // 6
			$userLat, // 7
			$userLng, // 8
			$hospital // 9
		);

	}

	function rideshare_btn_org_shortcode($atts, $content = null) {
		$pieces = explode(", ", $content);
		// print_r($pieces);
		$hospital = $city = $state = $zipcode = $lat = $lng = '';
		foreach ($pieces as $piece) {
			if (strpos($piece, 'hospital') !== false) {
				$hospital = substr($piece, strpos($piece, 'hospital') + strlen('hospital') + 1);
			} else if (strpos($piece, 'city') !== false) {
				$city = substr($piece, strpos($piece, 'city') + strlen('city') + 1);
			} else if (strpos($piece, 'state') !== false) {
				$state = substr($piece, strpos($piece, 'state') + strlen('state') + 1);
			} else if (strpos($piece, 'zipcode') !== false) {
				$zipcode = substr($piece, strpos($piece, 'zipcode') + strlen('zipcode') + 1);
			} else if (strpos($piece, 'lat') !== false) {
				$lat = substr($piece, strpos($piece, 'lat') + strlen('lat') + 1);
			} else if (strpos($piece, 'lng') !== false) {
				$lng = substr($piece, strpos($piece, 'lng') + strlen('lng') + 1);
			} else {
				continue;
			}
		}
		$hospital_address = rawurlencode($hospital . ", " . $city . " " . $state . ", " . $zipcode . ", " . "USA");

		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		$userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);

		$uber_key = 'Kqc4_ey5tqo-SZNBF3hXt8gW1uh2EyvR';
		$lyft_key = 'R9Mwmd-SzCYr';
		return $this->rideshare_btn_org = sprintf(
			'<div class="rideshare-org-container">
				<div class="rideshare_btn_org_container">
					<div class="uber-ride-container">
						<div class="uber-ride-inner">
							<a href="https://m.uber.com/?action=setPickup&client_id=%1$s&pickup[nickname]=MyLocation&pickup[formatted_address]=%6$s&pickup[latitude]=%7$s&pickup[longitude]=%8$s&dropoff[nickname]=%9$s&dropoff[formatted_address]=%3$s&dropoff[latitude]=%4$s&dropoff[longitude]=%5$s&_ga=2.53701928.1689161207.1573021625-829624440.1572458222">
								<div class="btn-description-container">
									<div class="ride-title">
										<div>Get a ride</div>
									</div>
									<div class="btn-description"> 
										<div>3 MIN AWAY</div>
										<div>$8-10 on UberX</div>
									</div>
								</div>
							</a>
						</div>
					</div>
					<div class="lyft-ride-container">
						<div class="lyft-ride-inner">
							<a href="https://lyft.com/ride?id=lyft&pickup[latitude]=%7$s&pickup[longitude]=%8$s&partner=%2$s&destination[latitude]=%4$s&destination[longitude]=%5$s">
								<div class="btn-description-container">
									<div class="lyft-ride-title">
										<div>Get a ride</div>
										<div class="lyft-btn-description-1">Lyft in 4min</div>
									</div>
									<div class="lyft-btn-description-2"> 
										<div>$8-10</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>',
			$uber_key, // 1
			$lyft_key, // 2
			$hospital_address, // 3
			$lat, // 4
			$lng, // 5
			$userAddr, // 6
			$userLat, // 7
			$userLng, // 8
			$hospital // 9
		);

	}
	

	public function render( $attrs, $content = null, $render_slug ) {
		// global $post;
		// $address = get_field("acf_address", $post->ID);
		// $hospitalLat = get_field("acf_lat", $post->ID);
		// $hospitalLng = get_field("acf_lng", $post->ID);
		
		// $hospital_address = rawurlencode($post->post_title . ", " . $address);
		// $hospital_nickname = $post->post_title;
		
		$PublicIP = $this->get_client_ip();
		$user_addr= @unserialize(file_get_contents('http://ip-api.com/php/'.$PublicIP));
		$userLat = $user_addr['lat'];
		$userLng = $user_addr['lon'];
		$userAddr = rawurlencode($user_addr['city'] . " " . $user_addr['regionName'] . ", " . $user_addr['zip'] . ", " . $user_addr['countryCode']);
		
		$rideType = $this->props['button_type'];
		$dest_nickname = $this->props['nickname'];
		$address = $this->props['address'];
		$dest_addr = rawurlencode($dest_nickname . ", " . $address);
		$dest_lat = $this->props['latitude'];
		$dest_long = $this->props['longitude'];

		if ($rideType == 'uber') {
			$client_key = $this->props['uber_api_key'];
			return sprintf(
				'<div class="uber-ride-container">
					<div class="uber-ride-inner">
						<a href="https://m.uber.com/?action=setPickup&client_id=%1$s&pickup[nickname]=MyLocation&pickup[formatted_address]=%8$s&pickup[latitude]=%5$s&pickup[longitude]=%6$s&dropoff[nickname]=%7$s&dropoff[formatted_address]=%2$s&dropoff[latitude]=%3$s&dropoff[longitude]=%4$s&_ga=2.53701928.1689161207.1573021625-829624440.1572458222">
							<div class="btn-description-container">
								<div class="ride-title">
									<div>Get a ride</div>
								</div>
								<div class="btn-description"> 
									<div>3 MIN AWAY</div>
									<div>$8-10 on UberX</div>
								</div>
							</div>
						</a>
					</div>
				</div>', 
				$client_key, 
				$dest_addr, 
				$dest_lat, 
				$dest_long,
				$userLat,
				$userLng,
				$dest_nickname,
				$userAddr
			);
		} else {
			$client_key = $this->props['lyft_api_key'];
			return sprintf(
				'<div class="lyft-ride-container">
					<div class="lyft-ride-inner">
						<a href="https://lyft.com/ride?id=lyft&pickup[latitude]=%4$s&pickup[longitude]=%5$s&partner=%1$s&destination[latitude]=%2$s&destination[longitude]=%3$s">
							<div class="btn-description-container">
								<div class="lyft-ride-title">
									<div>Get a ride</div>
									<div class="lyft-btn-description-1">Lyft in 4min</div>
								</div>
								<div class="lyft-btn-description-2"> 
									<div>$8-10</div>
								</div>
							</div>
						</a>
					</div>
				</div>', 
				$client_key, //R9Mwmd-SzCYr
				$dest_lat, 
				$dest_long,
				$userLat,
				$userLng
			);
		}
	}
}
$getSchedules = new GetSchedules;
add_shortcode('rideshare_uber_url', array($getSchedules, 'rideshare_uber_shortcode')); 
add_shortcode('rideshare_lyft_url', array($getSchedules, 'rideshare_lyft_shortcode')); 
add_shortcode('rideshare_lyft_btn', array($getSchedules, 'rideshare_lyft_btn_shortcode')); 
add_shortcode('rideshare_uber_btn', array($getSchedules, 'rideshare_uber_btn_shortcode')); 
add_shortcode('rideshare_btn', array($getSchedules, 'rideshare_btn_shortcode')); 
add_shortcode('rideshare_btn_org', array($getSchedules, 'rideshare_btn_org_shortcode')); 
