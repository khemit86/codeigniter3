<?php
/* <?php
if(count($get_portfolio_images) >11){
?>	
<!-- Slider Start -->
<div id="<?php  echo "slider".$portfolio_id; ?>" class="carousel slide" data-ride="carousel">
	<div class="container carousel-inner no-padding">
		<div class="carousel-item active">
			<div class="row">
				<?php foreach($get_portfolio_images as $portfolio_image_key=>$portfolio_image_value){ 
				$thumb_image_name = explode('.',$portfolio_image_value['portfolio_image_name']);
				$thumb_image_path= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_image_value['portfolio_id'].'/'.$thumb_image_name[0].'_thumb.jpg';
				?>
				<div class="col-md-1 col-sm-1 col-1 nopadding default_uploaded_image_border">
					<div class="portSlider">
					<div class="portSliImg" style="background-image: url('<?php echo $thumb_image_path; ?>');"></div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="carousel-item">
			<div class="row">
				<?php foreach($get_portfolio_images as $portfolio_image_key=>$portfolio_image_value){ 
				$thumb_image_name = explode('.',$portfolio_image_value['portfolio_image_name']);
				$thumb_image_path= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_image_value['portfolio_id'].'/'.$thumb_image_name[0].'_thumb.jpg';	
				?>
				<div class="col-md-1 col-sm-1 col-1 nopadding default_uploaded_image_border">
					<div class="portSlider">
						<div class="portSliImg" style="background-image: url('<?php echo $thumb_image_path; ?>');"></div>
					</div>
				</div> 
				<?php } ?>
			</div>
		</div>
	</div>									
	<!-- Left and right controls Start -->
	<a class="carousel-control-prev" href="#<?php  echo "slider".$portfolio_image_value['portfolio_id']; ?>" data-slide="prev">
		<span class="carousel-control-prev-icon"></span>
	</a>
	<a class="carousel-control-next" href="#<?php  echo "slider".$portfolio_image_value['portfolio_id']; ?>" data-slide="next">
		<span class="carousel-control-next-icon"></span>
	</a>									
	<!-- Left and right controls End -->
</div>
<!-- Slider End --> */
?>
<?php
//}else if(count($get_portfolio_images) >0 ){
?>
<div class="container portfolio_listing_container">
		<div class="row">
			<?php foreach($get_portfolio_images as $portfolio_image_key=>$portfolio_image_value){ 
			$thumb_name = explode('.',$portfolio_image_value['portfolio_image_name']);
			$thumb_image= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_image_value['portfolio_id'].'/'.$thumb_name[0].'_thumb.jpg';	
			?>
			<div class="col-md-1 col-sm-1 col-1 nopadding default_uploaded_image_border">
				<div class="portSlider">
					<div class="portSliImg" style="background-image: url('<?php echo $thumb_image; ?>');"></div>
				</div>
			</div> 
			<?php } ?>
		</div>
</div>									
		
<?php
//}
?>	