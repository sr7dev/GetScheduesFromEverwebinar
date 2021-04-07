<?php

class GetSchedules extends ET_Builder_Module {

	public $slug       = 'get_schedules';
	public $vb_support = 'on';
	
	public $get_times = 'get_times_shortcode';
	public $get_countdown = 'get_countdown_shortcode';

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => 'Armels Carciu',
		'author_uri' => '',
	);

	public function init() {
		$this->name = esc_html__( 'GetSchedules', 'get_schedules' );
		
		// Toggle settings
		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'button_type'=> esc_html__( 'GetSchedules Type', 'get_schedules' ),
				),
			),
		);

		$this->main_css_element = '%%order_class%%';

		$this->custom_css_fields = array();
	}

	public function get_fields() {
		return array(
			'api_key' => array(
				'label'           => esc_html__( 'API Key', 'get_schedules' ),
				'description'     => esc_html__( 'Input API Key.', 'get_schedules' ),
				'type'				=> 'text',
				'option_category'	=> 'basic_option',
				'default'			=> '93197255-f292-4650-a973-5bd4a0ffaf53',
				'toggle_slug'     => 'button_type',
			),
			'id' => array(
				'label'           => esc_html__( 'id', 'get_schedules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the id of the webinar.', 'get_schedules' ),
				'toggle_slug'     => 'button_type',
				'dynamic_content' => 'text',
				'default'					=> '1'
			),
		);
	}

	public function getEWData()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.webinarjam.com/everwebinar/webinar",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "api_key=93197255-f292-4650-a973-5bd4a0ffaf53&webinar_id=1",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded",
				"Cookie: __cfduid=d0a6cf6c8b50dd4725266715ff4735cd91617788952; wj4s=u5NBNu7NveymPp8In55Ixzl4YEWlNiClqnWSlHlv"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	function ordinal_suffix_of($i) {
		$j = $i % 10;
		$k = $i % 100;
		if ($j == 1 && $k != 11) {
				return $i . "st";
		}
		if ($j == 2 && $k != 12) {
				return $i . "nd";
		}
		if ($j == 3 && $k != 13) {
				return $i . "rd";
		}
		return $i . "th";
	}

	function get_month($m) {
		switch($m) {
			case 1:
				$m = "January";
				break;
			case 2:
				$m = "February";
				break;
			case 3:
				$m = "March";
				break;
			case 4:
				$m = "April";
				break;
			case 5:
				$m = "May";
				break;
			case 6:
				$m = "June";
				break;
			case 7:
				$m = "July";
				break;
			case 8:
				$m = "August";
				break;
			case 9:
				$m = "September";	
				break;
			case 10:
				$m = "October";
				break;
			case 11:
				$m = "November";
				break;
			case 12:
				$m = "December";
				break;
		}
		return $m;
	}

	function get_date($day) {
		switch ($day) {
			case 0:
				$day = "Sunday";
				break;
			case 1:
				$day = "Monday";
				break;
			case 2:
				$day = "Tuesday";
				break;
			case 3:
				$day = "Wednesday";
				break;
			case 4:
				$day = "Thursday";
				break;
			case 5:
				$day = "Friday";
				break;
			case 6:
				$day = "Saturday";
		}
		return $day;
	}

	function add_zero($i) {
		if ($i < 10) {
			return "0" . $i;
		}
 	}

	function get_times_shortcode() {
		date_default_timezone_set('America/New_York');
		$localTime = (int)date('H');
		$localDay = (int)date('d');
		$localMonth = $this->get_month((int)date('m'));
		$localDate = date("l");
		$month_day = $localMonth . ' ' . $this->ordinal_suffix_of($localDay);

		$date_t = strtotime("tomorrow");
		$localDay_t = (int)date("d", $date_t);
		$localMonth_t = $this->get_month((int)date("m", $date_t));
		$localDate_t = date("l", $date_t);
		$month_day_t = $localMonth_t . ' ' . $this->ordinal_suffix_of($localDay_t);

		
		if ($localTime >= 18) {
			return $this->get_times = sprintf('
				<div class="date-container">
					<div class="month-day-container">
						<p id="month">%1$s</p>
						<p id="day">%2$s</p>
					</div>
					<div class="month-day-date-container">
						<p id="date">%3$s</p>
						<p id="month_day">%4$s</p>
						<p id="time">at 8 am, 10 am, 12 pm, 4 pm, 6 pm</p>
					</div>
				</div>',
				$localMonth_t,
				$this->add_zero($localDay_t),
				$localDate_t,
				$month_day_t
			);
		} else if ($localTime >= 16) {
			return $this->get_times = sprintf('
				<div class="date-container">
					<div class="month-day-container">
						<p id="month">%1$s</p>
						<p id="day">%2$s</p>
					</div>
					<div class="month-day-date-container">
						<p id="date">%3$s</p>
						<p id="month_day">%4$s</p>
						<p id="time">at 6 pm & 8 am, 10 am, 12 pm, 4 pm</p>
					</div>
				</div>',
				$localMonth,
				$this->add_zero($localDay),
				$localDate,
				$month_day
			);
		} else if ($localTime >= 12) {
			return $this->get_times = sprintf('
				<div class="date-container">
					<div class="month-day-container">
						<p id="month">%1$s</p>
						<p id="day">%2$s</p>
					</div>
					<div class="month-day-date-container">
						<p id="date">%3$s</p>
						<p id="month_day">%4$s</p>
						<p id="time">at 4 pm, 6 pm & 8 am, 10 am, 12 pm</p>
					</div>
				</div>',
				$localMonth,
				$this->add_zero($localDay),
				$localDate,
				$month_day
			);
		} else if ($localTime >= 10) {
			return $this->get_times = sprintf('
				<div class="date-container">
					<div class="month-day-container">
						<p id="month">%1$s</p>
						<p id="day">%2$s</p>
					</div>
					<div class="month-day-date-container">
						<p id="date">%3$s</p>
						<p id="month_day">%4$s</p>
						<p id="time">at 12 pm, 4 pm, 6 pm & 8 am, 10 am</p>
					</div>
				</div>',
				$localMonth,
				$this->add_zero($localDay),
				$localDate,
				$month_day
			);
		} else if ($localTime >= 8) {
			return $this->get_times = sprintf('
				<div class="date-container">
					<div class="month-day-container">
						<p id="month">%1$s</p>
						<p id="day">%2$s</p>
					</div>
					<div class="month-day-date-container">
						<p id="date">%3$s</p>
						<p id="month_day">%4$s</p>
						<p id="time">at 10 am, 12 pm, 4 pm, 6 pm & 8 am</p>
					</div>
				</div>',
				$localMonth,
				$this->add_zero($localDay),
				$localDate,
				$month_day
			);
		}	else {
			return $this->get_times = sprintf('
				<div class="date-container">
					<div class="month-day-container">
						<p id="month">%1$s</p>
						<p id="day">%2$s</p>
					</div>
					<div class="month-day-date-container">
						<p id="date">%3$s</p>
						<p id="month_day">%4$s</p>
						<p id="time">at 8 am, 10 am, 12 pm, 4 pm, 6 pm</p>
					</div>
				</div>',
				$localMonth,
				$this->add_zero($localDay),
				$localDate,
				$month_day
			);
		}
	}
		
	function get_countdown_shortcode() {
		date_default_timezone_set('America/New_York');
		$localTime = (int)date('H');

		if ($localTime >= 18) {
			return $this->get_times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="0"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>Tomorrow at 8:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="1"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>Tomorrow at 10:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="2"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>Tomorrow at 12:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fourth-option" class="desktop-selector" value="3"> 
				<label for="fourth-option" id="fourth-option-label" class="desktop-selector">
					<p>Tomorrow at 4:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fifth-option" class="desktop-selector" value="4"> 
				<label for="fifth-option" id="fifth-option-label" class="desktop-selector">
					<p>Tomorrow at 6:00 PM</p>
				</label> 
				<select id="mobile-selector">
					<option value="0">Tomorrow at 8:00 AM</option> 
					<option value="1">Tomorrow at 10:00 AM</option> 
					<option value="2">Tomorrow at 12:00 PM</option>
					<option value="3">Tomorrow at 4:00 PM</option> 
					<option value="4">Tomorrow at 6:00 PM</option>
				</select>'
			);
		} else if ($localTime >= 16) {
			return $this->get_times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="0"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>Today at 6:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="1"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>Tomorrow at 8:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="2"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>Tomorrow at 10:00 AM</p>
				</label>
				<input required="required" type="radio" name="schedule" id="fourth-option" class="desktop-selector" value="3"> 
				<label for="fourth-option" id="fourth-option-label" class="desktop-selector">
					<p>Tomorrow at 12:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fifth-option" class="desktop-selector" value="4"> 
				<label for="fifth-option" id="fifth-option-label" class="desktop-selector">
					<p>Tomorrow at 4:00 PM</p>
				</label>  
				<select id="mobile-selector">
					<option value="0">Today at 6:00 pM</option> 
					<option value="1">Tomorrow at 8:00 AM</option> 
					<option value="2">Tomorrow at 10:00 AM</option>
					<option value="1">Tomorrow at 12:00 PM</option> 
					<option value="2">Tomorrow at 4:00 PM</option>
				</select>'
			);
		} else if ($localTime >= 12) {
			return $this->get_times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="0"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>Today at 4:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="1"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>Today at 6:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="2"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>Tomorrow at 8:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fourth-option" class="desktop-selector" value="3"> 
				<label for="fourth-option" id="fourth-option-label" class="desktop-selector">
					<p>Tomorrow at 10:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fifth-option" class="desktop-selector" value="4"> 
				<label for="fifth-option" id="fifth-option-label" class="desktop-selector">
					<p>Tomorrow at 12:00 PM</p>
				</label> 
				<select id="mobile-selector">
					<option value="0">Today at 4:00 PM</option> 
					<option value="1">Today at 6:00 PM</option> 
					<option value="2">Tomorrow at 8:00 AM</option>
					<option value="3">Tomorrow at 10:00 AM</option> 
					<option value="4">Tomorrow at 12:00 PM</option>
				</select>'
			);
		} else if ($localTime >= 10) {
			return $this->get_times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="0"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>Today at 12:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="1"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>Today at 4:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="2"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>Today at 6:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fourth-option" class="desktop-selector" value="3"> 
				<label for="fourth-option" id="fourth-option-label" class="desktop-selector">
					<p>Tomorrow at 8:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fifth-option" class="desktop-selector" value="4"> 
				<label for="fifth-option" id="fifth-option-label" class="desktop-selector">
					<p>Tomorrow at 10:00 AM</p>
				</label> 
				<select id="mobile-selector">
					<option value="0">Today at 12:00 PM</option> 
					<option value="1">Today at 4:00 PM</option> 
					<option value="2">Today at 6:00 PM</option>
					<option value="3">Tomorrow at 8:00 AM</option> 
					<option value="4">Tomorrow at 10:00 AM</option>
				</select>'
			);
		} else if ($localTime >= 8) {
			return $this->get_times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="0"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>Today at 10:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="1"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>Today at 12:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="2"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>Today at 4:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fourth-option" class="desktop-selector" value="3"> 
				<label for="fourth-option" id="fourth-option-label" class="desktop-selector">
					<p>Today at 6:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fifth-option" class="desktop-selector" value="4"> 
				<label for="fifth-option" id="fifth-option-label" class="desktop-selector">
					<p>Tomorrow at 8:00 AM</p>
				</label> 
				<select id="mobile-selector">
					<option value="0">Today at 10:00 AM</option> 
					<option value="1">Today at 12:00 PM</option> 
					<option value="2">Today at 4:00 PM</option>
					<option value="3">Today at 6:00 PM</option>
					<option value="4">Tomorrow at 8:00 AM</option>
				</select>'
			);
		}	else {
			return $this->get_times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="0"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>Today at 8:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="1"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>Today at 10:00 AM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="2"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>Today at 12:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fourth-option" class="desktop-selector" value="3"> 
				<label for="fourth-option" id="fourth-option-label" class="desktop-selector">
					<p>Today at 4:00 PM</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="fifth-option" class="desktop-selector" value="4"> 
				<label for="fifth-option" id="fifth-option-label" class="desktop-selector">
					<p>Today at 6:00 PM</p>
				</label> 
				<select id="mobile-selector">
					<option value="0">Today at 8:00 AM</option> 
					<option value="1">Today at 10:00 AM</option> 
					<option value="2">Today at 12:00 PM</option>
					<option value="3">Today at 4:00 PM</option>
					<option value="4">Today at 6:00 PM</option>
				</select>'
			);
		}
	}

	public function render( $attr, $content = null, $render_slug ) {
		return $content;
	}
}
$getSchedules = new GetSchedules;
add_shortcode('get_times', array($getSchedules, 'get_times_shortcode')); 
add_shortcode('get_countdown', array($getSchedules, 'get_countdown_shortcode')); 
