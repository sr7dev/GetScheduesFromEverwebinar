jQuery(document).ready(function ($) {
	function changedatetime(datetimestring) {
		console.log(datetimestring)
		let year = datetimestring.substring(0, 4);
		let month = datetimestring.substring(5, 2);
		let day = datetimestring.substring(8, 2);
		let hours = datetimestring.substring(11, 2);
		let mins = datetimestring.substring(14, 2);
		if (parseInt(hours) < 12) {
			return month+"-"+day+"-"+year+" "+hours+":"+mins+" AM";
		} else if (parseInt(hours) == 12) {
			return month+"-"+day+"-"+year+" "+hours+":"+mins+" PM";
		} else {
			if (parseInt(hours)-12 < 10) {
				return month+"-"+day+"-"+year+" 0"+(parseInt(hours)-12)+":"+mins+" PM";
			} else {
				return month+"-"+day+"-"+year+" "+(parseInt(hours)-12)+":"+mins+" PM";
			}
		}
	}
	function validate_form_fields() {
		$('#gform_1 input[type=text]').each(function () {
			let fields = $(this).closest('.error-message');
			let att_name = $(this).attr('name');
			let error_msg = "Field is required";
			if (!$(this).val() || $(this).val() == '') {
				if (att_name !== "input_5") {
					$(this).addClass('has-error');
					has_error = true;
					error_msg = "This field is required.";
				}
			} else {
				has_error = false;
				$(this).removeClass('has-error');
			}

			
			if (has_error && att_name !== "input_5") {
				
				if ($(this).parent().find(".error_text").length !== 0) {
					$(this).parent().find(".error_text").show();
				} else {
					$(this).parent().append('<p class="error_text">' + error_msg + '</p>');
				}

			} else {
				$(this).parent().find(".error_text").hide();
			}
		});
		return has_error;
	}
	$('#gform_submit_button_1').click(submit_schedule);
	function submit_schedule() {
		//history.pushState(null, '', '/en/step2');
		event.preventDefault();
		let has_errors = validate_form_fields();
		if 	(has_errors) {	
			return false;
		}
		let schedule = parseInt($("input[name='schedule']:checked").val());
		let checkedValId = $("input[name='schedule']:checked").attr('id');
		let scheduleTime = $('#'+checkedValId+'-label').find('p').text();
		
		data = {
			first_name: $('#input_1_1').val(),
			phone_country_code: "1",
			phone: $('#input_1_5').val(),
			email: $('#input_1_4').val(),
			schedule: schedule,
			action: 'form_handler'
		};
 
		$.ajax({
			url: "/wp-admin/admin-ajax.php",
			type: 'POST',
			data: data,
			success: function (resp) {
				if (resp) {
					let data = JSON.parse(resp);
					console.log(data);
					if (data.status == 'success') {
						$('#thank_you').show();
						$('#thank_you').find('#schedule_time p span').replaceWith(changedatetime(data.user.date));
						$('#live_room_btn').attr('href', data.user.live_room_url);
						history.pushState(null, '', '/thankyou/');
						return false;
					} else {
						console.log(data.message);
						return false;
					}
				}
			}
		});
	}

	$('#gform_submit_button_1_ny').click(submit_schedule_ny);
	function submit_schedule_ny() {
		//history.pushState(null, '', '/en/step2');
		event.preventDefault();
		let has_errors = validate_form_fields();
		if 	(has_errors) {	
			return false;
		}
		let schedule = parseInt($("input[name='schedule']:checked").val());
		let checkedValId = $("input[name='schedule']:checked").attr('id');
		let scheduleTime = $('#'+checkedValId+'-label').find('p').text();
		
		data = {
			first_name: $('#input_1_1').val(),
			phone_country_code: "1",
			phone: $('#input_1_5').val(),
			email: $('#input_1_4').val(),
			schedule: schedule,
			action: 'form_handler_ny'
		};
 
		$.ajax({
			url: "/wp-admin/admin-ajax.php",
			type: 'POST',
			data: data,
			success: function (resp) {
				if (resp) {
					let data = JSON.parse(resp);
					// console.log(data);
					if (data.status == 'success') {
						$('#thank_you').show();
						$('#thank_you').find('#schedule_time p span').replaceWith(changedatetime(data.user.date));
						$('#live_room_btn').attr('href', data.user.live_room_url);
						history.pushState(null, '', '/thankyou-ny/');
						return false;
					} else {
						console.log(data.message);
						return false;
					}
				}
			}
		});
	}
});