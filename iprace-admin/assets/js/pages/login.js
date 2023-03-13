$(document).ready(function() {

	//------------- Login page simple functions -------------//
 	$("html").addClass("loginPage");

 	wrapper = $(".login-wrapper");
 	barBtn = $("#bar .btn");

 	//change the tabs
 	barBtn.click(function() {
	  btnId = $(this).attr('id');
	  wrapper.attr("data-active", btnId);
	  $("#bar").attr("data-active", btnId);
	});

 	//show register tab
	$("#register").click(function() {
	  btnId = "reg";
	  wrapper.attr("data-active", btnId);
	  $("#bar").attr("data-active", btnId);
	});

	//check if user is change remove avatar
	var userField = $("input#user");
	var avatar = $("#avatar>img");

	//if user change email or username change avatar
	userField.change(function() {
		
	});

	//------------- Validation -------------//
	$("#login-form").validate({ 
		rules: {
			username: {
				required: true
			}, 
			password: {
				required: true
			}
		}, 
		messages: {
			username: {
				required: "Please provide a username"
			},
			password: {
				required: "Please provide a password"
			}
		},
		submitHandler: function(form){
		$('#login_val').val(1);
	        var btn = $('#loginBtn');
	        btn.removeClass('btn-primary');
	        btn.addClass('btn-danger');
	        btn.text('Checking ...');
	        btn.attr('disabled', 'disabled');
	        setTimeout(function() {

	        	btn.removeClass('btn-danger');
	        	
	        }, 1500);
	        setTimeout(function () {
	        $(form).ajaxSubmit({
					target: 'body', 
					success: function(dd) { 
					
					}
		    }); 
				
				
				
				
	        }, 2000);
		}
	});
	
	$("#forgot-form").validate({ 
		rules: {
			user: {
				required: true
			}, 
			email: {
				required: true,
				email:true
			}
		}, 
		messages: {
			username: {
				required: "Please provide a username"
			},
			password: {
				required: "Please provide a email",
				email:"Please provide valid email"
			}
		},
		submitHandler: function(form){
		$('#forgot_val').val(1);
	        var btn = $('#forgotBtn');
	        btn.removeClass('btn-primary');
	        btn.addClass('btn-danger');
	        btn.text('Checking ...');
	        btn.attr('disabled', 'disabled');
	        setTimeout(function() {

	        	btn.removeClass('btn-danger');
	        	
	        }, 1500);
	        setTimeout(function () {
	      
				
				form.submit();
				
				
	        }, 2000);
		}
	});

});