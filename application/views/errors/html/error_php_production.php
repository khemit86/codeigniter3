<?php
$CI = & get_instance();
$default_404_page_title_meta_tag = $CI->config->item('system_generated_404_page_title_meta_tag');
$default_404_page_description_meta_tag = $CI->config->item('system_generated_404_page_description_meta_tag');
$meta_tag = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
echo $CI->load->view('header_404_page',array('meta_tag'=>$meta_tag),true);	
?>
<!-- Body Section Start -->
<div class="dashTop">
	<div id="errorBody" class="errorBodySection">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">
				<div class="error_body_sectn">
					<h1><?php echo $CI->config->item('system_generated_404_page_heading'); ?></h1>
					<h3><?php echo $CI->config->item('system_generated_404_page_message'); ?> (err kod: p73HpOH5Yg89)</h3>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Body Section End -->

<!-- Footer Section Start -->
<div id="errorFooter" class="error_footer_sectn">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-12">
			<div class="error_footer_row">
				<h2><?php echo $CI->config->item('footer_message_404_page'); ?></h2>
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
	</body>
</html>