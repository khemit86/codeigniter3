<?php
$hidden_project_page_heading = $this->config->item('hidden_project_page_heading');
if(!empty($this->session->userdata ('user'))) {
	$user = $this->session->userdata ('user');			
}
?>	

<!-- Body Section Start -->
	<div class="dashTop">
		<div id="errorBody" class="errorBodySection">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<div class="error_body_sectn">
						<h1><?php echo $hidden_project_page_heading; ?></h1>
						<h3>
						<?php
						

						if($this->session->userdata('user'))
						{
							echo $this->config->item('hidden_project_page_message_with_login');
						} else {
							echo $this->config->item('hidden_project_page_message_without_login');
						}   
						?>
						</h3>
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
					<h2><?php echo $this->config->item('footer_message_404_page'); ?></h2>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer Section End -->
	<div class="modal fade anModal" id="applyNow">
		<div class="modal-dialog modal-dialog">
			<div class="modal-content">
				
			</div>
		</div>
	</div>
<script>
    $(document).ready(function () {
        var wheight = $(window).height();
        var bHeight = wheight - (parseInt($("#headerContent").outerHeight())+parseInt($("#errorFooter").outerHeight()));
        $("#errorBody").css('min-height', bHeight);

        var theight = parseInt($("#headerContent").outerHeight()) + parseInt($("#errorBody").outerHeight()) + parseInt($("#errorFooter").outerHeight());
        console.log("window=="+wheight+"===Body=="+theight+"===Header=="+parseInt($("#headerContent").height())+"===Footer=="+parseInt($("#errorFooter").height()));
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
        console.log("window=="+wheight+"===Body=="+theight+"===Header=="+parseInt($("#headerContent").height())+"===Footer=="+parseInt($("#errorFooter").height()));
        if (theight > wheight) {
            $("#errorFooter").removeClass("footerFixed").addClass("footerVisible");
        } else {
            $("#errorFooter").removeClass("footerVisible").addClass("footerFixed");
        }
    });
</script>