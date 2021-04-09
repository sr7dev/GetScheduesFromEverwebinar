jQuery(document).ready(function ($) {
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
		
		data = {
			first_name: $('#input_1_1').val(),
			phone_country_code: "1",
			phone: $('#input_1_5').val(),
			email: $('#input_1_4').val(),
			schedule: "1",
			action: 'form_handler'
		};
 
		$.ajax({
			url: "/wp-admin/admin-ajax.php",
			type: 'POST',
			data: data,
			success: function (resp) {
				if (resp) {
					let data = JSON.parse(resp);
					if (data.status) {
						let checkedValue = parseInt($("input[name='schedule']:checked").val());
						var today = new Date();
						var tomorrow = new Date(today);
						tomorrow.setDate(today.getDate() + 1);
						today.toLocaleString('en-US', { timeZone: 'America/New_York' });
						tomorrow.toLocaleString('en-US', { timeZone: 'America/New_York' });
						var dd = today.getDate();
						var mm = today.getMonth()+1;
						var yyyy = today.getFullYear();
						today = yyyy+'-'+mm+'-'+dd;						
						var tomorrow = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate();
						var scheduleTime;
						
						switch (checkedValue) {
							case 0:
								scheduleTime = today + " 8:00 AM";
								break;
							case 1:
								scheduleTime = today + " 10:00 AM";
								break;
							case 2:
								scheduleTime = today + " 12:00 PM";
								break;
							case 3:
								scheduleTime = today + " 4:00 PM";
								break;
							case 4:
								scheduleTime = today + " 6:00 PM";
								break;
							case 5:								
								scheduleTime = tomorrow + " 8:00 AM";
								break;
							case 6:								
								scheduleTime = tomorrow + " 10:00 AM";
								break;
							case 7:
								scheduleTime = tomorrow + " 12:00 PM";
								break;
							case 8:
								scheduleTime = tomorrow + " 4:00 PM";
								break;
							case 9:
								scheduleTime = tomorrow + " 6:00 PM";
								break;
						}
						
						$('#thank_you').show();
						$('#thank_you').find('#schedule_time p span').replaceWith(scheduleTime);
						history.pushState(null, '', '/thankyou/');
						return false;
					} else {
						alert("f")
						return false;
					}
				}
			}
		});
	}
});
