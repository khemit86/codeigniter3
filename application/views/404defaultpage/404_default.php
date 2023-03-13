<?php
if(isset($current_page) && $current_page == 'project_not_existent_404'){
	$heading = $this->config->item('project_not_existent_404_page_heading');
	//$message = $this->config->item('404_page_description_meta_tag');
	$message_404_with_login = $this->config->item('project_not_existent_404_page_message_with_login');
	$message_404_without_login = $this->config->item('project_not_existent_404_page_message_without_login');
}else{
	$heading = $this->config->item('404_page_heading');
	//$message = $this->config->item('404_page_description_meta_tag');
	$message_404_with_login = $this->config->item('message_404_with_login');
	$message_404_without_login = $this->config->item('message_404_without_login');
}



if(!empty($this->session->userdata ('user'))) {
	$user = $this->session->userdata ('user');			
}
?>
<div class="dashTop">
	<!-- Body Section Start -->
	<div id="errorBody" class="errorBodySection">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="error_body_sectn">
					<h1><?php echo $heading; ?></h1>
					<h3>
					<?php
					
					//$session_data = $CI_Session->session->userdata('user');

					if($this->session->userdata('user'))
					{
						echo $message_404_with_login;
					} else {
						echo $message_404_without_login;
					}   
					?>
					</h3>
				</div>
			</div>
		</div>
	</div>
	<!-- Body Section End -->
	</div>
	<!-- Footer Section Start -->
	<div id="errorFooter" class="error_footer_sectn">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="error_footer_row">
					<h2><?php echo $this->config->item('footer_message_404_page'); ?></h2>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer Section End -->
<script>
    $(document).ready(function () {
       var wheight = $(window).height();
		var bHeight = wheight - (parseInt($("#headerContent").outerHeight())+parseInt($("#errorFooter").outerHeight()));
		$("#errorBody").css('min-height', bHeight);

		var theight = parseInt($("#headerContent").outerHeight()) + parseInt($("#errorBody").outerHeight()) + parseInt($("#errorFooter").outerHeight());
		console.log("window=="+wheight+"===Body=="+theight+"===Header=="+parseInt($("#headerContent").outerHeight())+"===Footer=="+parseInt($("#errorFooter").outerHeight()));
		if (theight > wheight) {
			$("#errorFooter").removeClass("footerFixed").addClass("footerVisible");
		} else {
			$("#errorFooter").removeClass("footerVisible").addClass("footerFixed");
		}
    });
    $(window).resize(function () {
        var wheight = $(window).height();
		var bHeight = wheight - (parseInt($("#headerContent").outerHeight())+parseInt($("#errorFooter").outerHeight()));
		$("#errorBody").css('min-height', bHeight);

		var theight = parseInt($("#headerContent").outerHeight()) + parseInt($("#errorBody").outerHeight()) + parseInt($("#errorFooter").outerHeight());
		console.log("window=="+wheight+"===Body=="+theight+"===Header=="+parseInt($("#headerContent").outerHeight())+"===Footer=="+parseInt($("#errorFooter").outerHeight()));
		if (theight > wheight) {
			$("#errorFooter").removeClass("footerFixed").addClass("footerVisible");
		} else {
			$("#errorFooter").removeClass("footerVisible").addClass("footerFixed");
		}
    });
</script>