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

		$response = json_decode(curl_exec($curl), true);

		curl_close($curl);
		if (strcasecmp($response['status'], 'success') == 0) {
			return $response;
		} else {
			return false;
		}
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
		} else {
			return $i;
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
	function change12($i) {
		$hour_num = (int)substr($i, 0, 2);
		if ($hour_num < 12) {
			return $i.' AM';
		} else if ($hour_num == 12) {
			return $i.' PM';
		} else {
			if ($hour_num-12 < 10) {
				return '0'.($hour_num-12).substr($i, 2).' PM';
			} else {
				return ($hour_num-12).substr($i, 2).' PM';
			}
		}
	}
	function get_countdown_shortcode() {
		date_default_timezone_set('America/New_York');
		$localTime = (int)date('H');
		$localDay = (int)date('d');
		$times  = '';
		$schedulesData = $this->getEWData();
		$schedules = $schedulesData['webinar']['schedules'];
		
		if (count($schedules) > 0) {
			$times = sprintf('
				<input required="required" type="radio" name="schedule" id="first-option" checked="checked" class="desktop-selector" value="%3$s"> 
				<label for="first-option" id="first-option-label" class="desktop-selector">
					<p>%1$s at %2$s</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="second-option" class="desktop-selector" value="%6$s"> 
				<label for="second-option" id="second-option-label" class="desktop-selector">
					<p>%4$s at %5$s</p>
				</label> 
				<input required="required" type="radio" name="schedule" id="third-option" class="desktop-selector" value="%9$s"> 
				<label for="third-option" id="third-option-label" class="desktop-selector">
					<p>%7$s at %8$s</p>
				</label> 
				<select id="mobile-selector">
					<option value="%3$s">%1$s at %2$s</option> 
					<option value="%6$s">%4$s at %5$s</option> 
					<option value="%9$s">%7$s at %8$s</option>
				</select>',
				((int)(substr($schedules[0]['date'], strlen($schedules[0]['date'])-8, 2)) == $localDay) ? "Today" : "Tomorrow",//1
				$this->change12(substr($schedules[0]['date'], strlen($schedules[0]['date'])-5)),//2
				$schedules[0]['schedule'],//3
				((int)substr($schedules[1]['date'], strlen($schedules[1]['date'])-8, 2) == $localDay) ? "Today" : "Tomorrow",//4
				$this->change12(substr($schedules[1]['date'], strlen($schedules[1]['date'])-5)),//5
				$schedules[1]['schedule'],//6
				((int)substr($schedules[2]['date'], strlen($schedules[2]['date'])-8, 2) == $localDay) ? "Today" : "Tomorrow",//7
				$this->change12(substr($schedules[2]['date'], strlen($schedules[2]['date'])-5)),//8
				$schedules[2]['schedule']//9
			);
		} 
		return $this->get_times = sprintf('
			<form method="post" enctype="multipart/form-data" id="gform_1" class="booking-form">
				<div class="gform_body">
					<ul id="gform_fields_1" class="gform_fields top_label form_sublabel_below description_below">
						<li id="field_1_7" class="gfield schedule-hours gfield_html gfield_html_formatted gfield_no_follows_desc field_sublabel_below field_description_below gfield_visibility_visible">
							%1$s
						</li>
						<li id="field_1_1" class="gfield gfield_contains_required field_sublabel_below field_description_below hidden_label gfield_visibility_visible">
							<label class="gfield_label" for="input_1_1">
								<span class="gfield_required">*</span>
							</label>
							<div class="ginput_container ginput_container_text">
								<input name="input_1" id="input_1_1" type="text" value="" class="large" placeholder="First Name" aria-required="true" aria-invalid="false">
							</div>
						</li>
						<li id="field_1_4" class="gfield gfield_contains_required field_sublabel_below field_description_below hidden_label gfield_visibility_visible">
							<label class="gfield_label" for="input_1_4">
								Email<span class="gfield_required">*</span>
							</label>
							<div class="ginput_container ginput_container_email">
								<input name="input_4" id="input_1_4" type="text" value="" class="large" placeholder="Email Address" aria-required="true" aria-invalid="false">
							</div>
						</li>
						<li id="field_1_5" class="gfield field_sublabel_below field_description_below hidden_label gfield_visibility_visible">
							<label class="gfield_label" for="input_1_5">Phone</label>
							<div class="ginput_container ginput_container_phone">
								<input name="input_5" id="input_1_5" type="text" value="" class="large" placeholder="Phone Number(Optional)" aria-invalid="false">
							</div>
						</li>
					</ul>
				</div>
				<div class="gform_footer top_label"> 
					<input type="submit" id="gform_submit_button_1" class="gform_button button" value="Claim my free spot now"> 
					<input type="hidden" name="gform_ajax" value="form_id=1&amp;title=&amp;description=&amp;tabindex=0">
					<input type="hidden" class="gform_hidden" name="is_submit_1" value="1">
					<input type="hidden" class="gform_hidden" name="gform_submit" value="1">
					<input type="hidden" class="gform_hidden" name="gform_unique_id" value="">
					<input type="hidden" class="gform_hidden" name="state_1" value="WyJbXSIsIjJhNzJhNDAzYTBhYjMyODZkNzUzNmVlNWRmNTA2MWEwIl0=">
					<input type="hidden" class="gform_hidden" name="gform_target_page_number_1" id="gform_target_page_number_1" value="0">
					<input type="hidden" class="gform_hidden" name="gform_source_page_number_1" id="gform_source_page_number_1" value="1">
					<input type="hidden" name="gform_field_values" value="">
        </div>
			</form>
			',
			$times
		);
	}

	public function render( $attr, $content = null, $render_slug ) {
		return $content;
	}
}
$getSchedules = new GetSchedules;
add_shortcode('get_times', array($getSchedules, 'get_times_shortcode')); 
add_shortcode('get_countdown', array($getSchedules, 'get_countdown_shortcode')); 
