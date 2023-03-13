<?php
$user = $this->session->userdata('user');
$portfolio_tag_data = array();
if(isset($portfolio_data['portfolio_id']) && !empty($portfolio_data['portfolio_id'])){
	$portfolio_tag_data = get_portfolio_tags(array('portfolio_id'=>$portfolio_data['portfolio_id']));
}

if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
	$number_tag_allowed = $this->config->item('user_portfolio_page_free_membership_subscriber_number_tags_allowed_per_portfolio_slot');
	$number_image_allowed = $this->config->item('user_portfolio_page_free_membership_subscriber_number_images_allowed_per_portfolio_slot');	
	
}else{	
	$number_tag_allowed = $this->config->item('user_portfolio_page_gold_membership_subscriber_number_tags_allowed_per_portfolio_slot');
	$number_image_allowed = $this->config->item('user_portfolio_page_gold_membership_subscriber_number_images_allowed_per_portfolio_slot');
}

?>	
<form id="portfolio_form">
	<input type="hidden" name="section_id" value="<?php if(isset($portfolio_data['portfolio_id'])){ echo Cryptor::doEncrypt($portfolio_data['portfolio_id']); } ?>"/>
	<input type="hidden" name="u_id" value="<?php echo Cryptor::doEncrypt($user[0]->user_id);  ?>"/>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="default_black_bold_medium"><?php echo $this->config->item('user_portfolio_section_portfolio_title'); ?></label>
                <input type="text" id="portfolio_title" name="portfolio_title" class="form-control avoid_space default_input_field" placeholder="<?php echo $this->config->item('user_portfolio_section_portfolio_title_placeholder'); ?>" maxlength="<?php echo $this->config->item('user_portfolio_section_section_portfolio_title_characters_maximum_length_characters_limit'); ?>" value="<?php if(isset($portfolio_data['title'])){ echo htmlentities($portfolio_data['title'],ENT_QUOTES); } ?>">
                <div class="error_div_sectn clearfix default_error_div_sectn">
				
					<?php 
					$portfolio_title_remaining_characters = $this->config->item('user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit');
					if(isset($portfolio_data['title'])){ 
						
						if($this->config->item('user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit') - mb_strlen(trim(preg_replace('/\s+/', ' ',$portfolio_data['title']))) >= 0)
						{
							$portfolio_title_remaining_characters = $this->config->item('user_portfolio_section_portfolio_title_characters_maximum_length_characters_limit') - mb_strlen(trim(preg_replace('/\s+/', ' ',$portfolio_data['title'])));
						}else{
						
							$portfolio_title_remaining_characters = 0;
						}
					} 
					?>
                    <span class="content-count portfolio_title_length_count_message"><?php echo $portfolio_title_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span> 
					<span class="error_msg portfolio_title_required" id="portfolio_title_error"></span>
                </div>
            </div>
        </div>
        

        <div class="col-md-12">
            <div class="form-group">
                <label class="default_black_bold_medium desc_bottom default_label_bottom_gap" for="comment"><?php echo $this->config->item('user_portfolio_section_description'); ?></label>
                <textarea class="avoid_space_textarea default_textarea_field" rows="5" name="description" id="description" maxlength="<?php echo $this->config->item('user_portfolio_section_description_characters_maximum_length_characters_limit'); ?>"><?php if(isset($portfolio_data['description'])){ echo $portfolio_data['description']; } ?></textarea>
                <div class="error_div_sectn clearfix default_error_div_sectn">
                        
                        <?php 
						$portfolio_description_remaining_characters = $this->config->item('user_portfolio_section_description_characters_maximum_length_characters_limit');
						if(isset($portfolio_data['description'])){ 
							
							if($this->config->item('user_portfolio_section_description_characters_maximum_length_characters_limit') - mb_strlen(trim(preg_replace('/\s+/', ' ',$portfolio_data['description']))) >= 0)
							{
								$portfolio_description_remaining_characters = $this->config->item('user_portfolio_section_description_characters_maximum_length_characters_limit') - mb_strlen(trim(preg_replace('/\s+/', ' ',$portfolio_data['description'])));
							}else{
							
								$portfolio_description_remaining_characters = 0;
							}
						} 
						?>
                        <span class="content-count description_length_count_message"><?php echo $portfolio_description_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span> 
						<span class="error_msg description_required" id="description_error"></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="receive_notification reference_popup" id="referenceSL">
                <a class="rcv_notfy_btn" onclick="showReference()"><?php echo $this->config->item('user_portfolio_section_referrence'); ?> <small><?php if(isset($portfolio_data['reference_url']) && !empty($portfolio_data['reference_url'])){ echo "( - )"; } else {echo "( + )";} ?></small></a>
                <input type="hidden" id="moreReference" name="display_reference_url" value="<?php if(!empty($portfolio_data['reference_url'])){ echo "0"; } else {echo "1";}?>">
            </div>
            <div id="more_reference" class="collapse row mBAdjust" style="<?php echo (isset($portfolio_data['reference_url']) && !empty($portfolio_data['reference_url'])) ? 'display:block;' : 'display:none;'; ?>">
                <div class="col-md-12">
                    <input type="text" id="reference_url" name="reference_url" class="form-control avoid_space default_input_field" placeholder="<?php echo $this->config->item('user_portfolio_section_referrence_placeholder'); ?>" value="<?php if(isset($portfolio_data['reference_url'])){ echo $portfolio_data['reference_url']; } ?>">
                    <div class="error_div_sectn clearfix default_error_div_sectn">
                        <span class="error_msg" id="reference_url_error"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
			<div class="bdrUpload">
				<div class="receive_notification" id="tagSL">
					<a class="rcv_notfy_btn" onclick="showMoreTags(this)" data-section-id="<?php if(isset($portfolio_data['portfolio_id'])){ echo $portfolio_data['portfolio_id']; }else { echo "0";} ?>" id="project_tag_heading_section"><?php echo $this->config->item('user_portfolio_section_tags'); ?> <small><?php if(!empty($portfolio_tag_data)){ echo "( - )"; } else {echo "( + )";} ?></small></a>
					<input type="hidden" name="display_tag" id="moreTags" value="<?php if(!empty($portfolio_tag_data)){ echo "0"; } else {echo "1";}?>">
				</div>
				<div id="more_tags" class="collapse" style="<?php echo (!empty($portfolio_tag_data)) ? 'display:block;' : 'display:none;'; ?>">
					<div class="tagBottom">
						<input type="text" id="input_tags" name="" class="form-control avoid_space default_input_field" maxlength="<?php echo $this->config->item('user_portfolio_section_tags_characters_maximum_length_characters_limit'); ?>">
						<div class="error_div_sectn clearfix default_error_div_sectn">
							<?php 
							$tag_remaining_characters = $this->config->item('user_portfolio_section_tags_characters_maximum_length_characters_limit');
							?>
							<span class="content-count portfolio_tag_length_count_message"><?php echo $tag_remaining_characters.' '.$this->config->item('characters_remaining_message'); ?></span>
							<span class="error_save">
								<span id="tag_error" class="error_msg" style="display:none;"></span>
								
								<div class="saveTAg_responsive" id="save_tag_button_section_responsive"><button type="button" class="btn default_btn blue_btn default_popup_width_btn" disabled><?php echo $this->config->item('save_btn_txt'); ?></button></div>
							</span>
						</div>
						<ul id="tags-list" class="default_cross_tag">
							<?php
							if(!empty($portfolio_tag_data)){
								$tag_counter = 0;
								foreach($portfolio_tag_data as $portfolio_tag){
									
							?>
								<li class="tag_name" id="<?php echo 'portfolio_tag_'.$portfolio_tag['id']."_".$portfolio_data['portfolio_id'] ?>"> <span><small><?php echo $portfolio_tag['portfolio_tag_name']; ?></small><input type="hidden" name="portfolio_tag[<?php echo $tag_counter; ?>][tag_name]" value="<?php echo $portfolio_tag['portfolio_tag_name']; ?>" /><i class="fa fa-times delete_portfolio_tag_row_data" data-attr="<?php echo 'portfolio_tag_'.$portfolio_tag['id']."_".$portfolio_tag['portfolio_id'] ?>"></i> </span></li>
							
							<?php
									$tag_counter ++;
								}
							}	
							?>
						</ul>
					</div>
					<div class="saveTAg" id="save_tag_button_section"><button type="button" class="btn default_btn blue_btn default_popup_width_btn" disabled><?php echo $this->config->item('save_btn_txt'); ?></button></div>
					<div class="clearfix"></div>
				</div>
            </div>
        </div>
		<div class="col-md-12">
            <div class="row mr-0 ml-0 imgTemplate" id="image_template" style="display:<?php echo !empty($portfolio_images)?"block":"none";  ?>">
				<?php
				if(!empty($portfolio_images)){
					foreach($portfolio_images as $image_key=>$image_value){
					$thumb_image_name = explode('.',$image_value['portfolio_image_name']);
					$thumb_image_path= CDN_SERVER_LOAD_FILES_PROTOCOL.CDN_SERVER_DOMAIN_NAME.USERS_FTP_DIR.$user_detail['profile_name'].USER_PORTFOLIO.$portfolio_data['portfolio_id'].'/'.$thumb_image_name[0].'_thumb.jpg';
				?>
					<div class="col-md-1 col-sm-1 col-1 nopadding portfolio_image_row default_uploaded_image_border" id="<?php echo "portfolio_image_row".$image_value['id'] ?>">
						<div class="portSlider">
							<div class="portSliImg" style="background-image: url('<?php echo $thumb_image_path; ?>');"></div>
							<div class="popup_img_delete portfolio_image_delete" data-row-id="<?php echo $image_value['id']; ?>" data-portfolio-id="<?php echo $image_value['portfolio_id']; ?>" data-image-name="<?php echo $image_value['portfolio_image_name']; ?>"><i class="fas fa-trash-alt"></i></div>
						</div>
					</div>    
				<?php
					
					}
				}
				?>
				
            </div>
			<!-- Default Image Upload Button Start -->
			<?php
			/* <div class="defaule_upload_btn">
				<label for="upload" class="btn default_btn blue_btn"><i class="fa fa-cloud-upload"></i>
                <?php echo $this->config->item('user_portfolio_section_upload_images'); ?></label>
				<input id="upload" type="file" name="file-upload">
			</div> */
			$button_style = "display:none;";
			if(count($portfolio_images) < $number_image_allowed){
			$button_style = "display:block;";
			}
			
			?>
			<div class="default_upload_btn drop_zone" style="<?php echo $button_style; ?>">
				 <div id="overlay" class="d-none">
					<div class="text"><?php echo $this->config->item('drop_zone_drag_and_drop_files_area_message_txt') ?></div>
				 </div>
				<div class="upload_file_wrapper">
				<input type="file" class="portfolio_imgupload" accept="<?php echo $this->config->item('pictures_allowed_extensions_input_file_type'); ?>" style="display:none"/>
                <button type="button" class="OpenImgUpload btn default_btn blue_btn"><i class="fa fa-cloud-upload"></i><?php echo $this->config->item('user_portfolio_section_upload_images'); ?></button>
				</div>
				
			</div>
			<!-- Default Image Upload Button End -->
			
			<div class="error_div_sectn clearfix default_error_div_sectn portfolio_image_error"><span id="portfolio_image_error" class="error_msg required"></span></div>
        </div>
		
    </div>
    <div class="default_popup_close text-right">
        <div class="row">
            <!--<div class="col-md-8 leftButton"></div>-->
            <div class="col-md-12 col-sm-12 rightButton"><button type="button" class="btn default_btn red_btn default_popup_width_btn btnCancel" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn default_btn blue_btn default_popup_width_btn" id="save_portfolio"><?php echo $this->config->item('save_btn_txt'); ?> <i id="spin_loader" style="display:none;" class="fa fa-spinner fa-spin"></i></button></div>
        </div>
    </div>
</form>	
<script>
<?php

if(count($portfolio_tag_data) >= $number_tag_allowed){
?>
	$("#input_tags").css('display','none');
	$("#input_tags").next().css('display','none');
	$("#save_tag_button_section,#save_tag_button_section_responsive").css('display','none');
	$("#save_tag_button_section .btn, #save_tag_button_section_responsive .btn").prop('disabled',true);
<?php
}
?>
</script>

<script>
/* var user_portfolio_section_custom_portfolio_image_allowed_file_extensions = [<?php echo $this->config->item('user_portfolio_section_custom_portfolio_image_allowed_file_extensions'); ?>];

var user_portfolio_section_portfolio_image_invalid_file_extension_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_invalid_file_extension_validation_message'); ?>";

var portfolio_image_maximum_size_limit = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_maximum_size_limit'); ?>";

var popup_alert_heading = "<?php echo $this->config->item('popup_alert_heading'); ?>";

var upload_blank_attachment_alert_message = "<?php echo $this->config->item('upload_blank_attachment_alert_message'); ?>";	

var portfolio_image_maximum_size_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_maximum_size_validation_project_bid_form_message'); ?>";

var portfolio_image_allowed_files_validation_message = "<?php echo $this->config->item('user_portfolio_section_portfolio_image_allowed_files_validation_message'); ?>";

var bid_attachment_invalid_file_extension_validation_message = "<?php echo $this->config->item('project_details_page_bid_attachment_invalid_file_extension_validation_project_bid_form_message'); ?>";

var portfolio_section_plugin_portfolio_image_allowed_file_extensions = '<?php echo $this->config->item('user_portfolio_section_plugin_portfolio_image_allowed_file_extensions'); ?>';	 */

/* if($('.upload-portfolio-image').length > 0){
	var number_of_files_bid = 1;
	$(document).on('click', '.fileinput-button-update-bid', function () {	
	number_of_files_bid = 1;
	});
	var allowed_file_type = user_portfolio_section_custom_portfolio_image_allowed_file_extensions;	 

	
	var myDropzone = new Dropzone('.upload-portfolio-image ', { // Make the whole body a dropzone
		  
		init: function () {
			this.on("addedfile", function(file) {
				$(".dz-preview").css('display','none');
				if(number_of_files_bid > 1){
					this.removeFile(file);
				}else{
					file_name = file.name;
					file_name_array = file_name.split(".");
					last_value = file_name_array[file_name_array.length - 1];
					if($.inArray( last_value, allowed_file_type ) == '-1'){
						
						if($("#error_popup").css('display') == 'none'){
						$('#portfolioModalCenter').modal('hide');
						$("#error_popup_heading").html(popup_alert_heading);
						$("#error_popup_body").html(user_portfolio_section_portfolio_image_invalid_file_extension_validation_message);
						$('#error_popup').modal('show');	
						}
						this.removeFile(file);	
						
					}
					else if(file.size == 0){
						if($("#error_popup").css('display') == 'none'){
							$('#portfolioModalCenter').modal('hide');
							$("#error_popup_heading").html(popup_alert_heading);
							$("#error_popup_body").html(upload_blank_attachment_alert_message);
							$('#error_popup').modal('show');	
						}
						this.removeFile(file);	
					
					}
					else if(file.size > portfolio_image_maximum_size_limit){
						
						if($("#error_popup").css('display') == 'none'){
							$('#portfolioModalCenter').modal('hide');
							size_mb  = file.size/1048576;
							portfolio_image_maximum_size_validation_message = portfolio_image_maximum_size_validation_message.replace("{file_size_mb}", size_mb.toFixed(2));
							$("#error_popup_heading").html(popup_alert_heading);
							$("#error_popup_body").html(portfolio_image_maximum_size_validation_message);
							$('#error_popup').modal('show');	
						}
						this.removeFile(file);	
					}else{
						
						myDropzone.options.autoProcessQueue = true;
					}
				}
			});
			
			this.on('drop', function(file) {
				number_of_files_bid = file.dataTransfer.files.length;
				if(file.dataTransfer.files.length> 1){
					$('#portfolioModalCenter').modal('hide');
					$("#error_popup_heading").html(popup_alert_heading);
					$("#error_popup_body").html(portfolio_image_allowed_files_validation_message);
					$('#error_popup').modal('show');	
					myDropzone.options.autoProcessQueue = false; 
				}else{
					file_name = file.dataTransfer.files[0].name;
					file_name_array = file_name.split(".");
					last_value = file_name_array[file_name_array.length - 1];
					if($.inArray( last_value, allowed_file_type ) == '-1'){
						myDropzone.options.autoProcessQueue = false; 
						$('#portfolioModalCenter').modal('hide');
						$("#error_popup_heading").html(popup_alert_heading);
						$("#error_popup_body").html(user_portfolio_section_portfolio_image_invalid_file_extension_validation_message);
						$('#error_popup').modal('show');	
					}else{
						myDropzone.options.autoProcessQueue = true; 
					}
				}
			});
			this.on('sending', function(file, response) {
				$(".upload-btn-wrapper").css("pointer-events","none").css("opacity",0.5).css("cursor","not-allowed");
				
			});
			this.on('success', function(file, response) {
				$(".upload-btn-wrapper").css("pointer-events","unset").css("opacity",1).css("cursor","pointer");
				this.removeFile(file);	
				var obj = JSON.parse(response);
				if(obj.status == 'OK'){
					var section_id = 0;
					if(obj.action_type == 'update_bid'){
						section_id  = obj.section_id;
					}
					$('#bid_attachment_container').append('<div class="default_download_attachment upValue bid_attachment_row" id="bid_attachment_row_'+obj.id+'"><a class="download_attachment download_text download_bid_form_attachment" data-action-type="'+obj.action_type+'" data-section-id = '+ section_id +'   style="cursor:pointer;" data-attr="'+obj.attachment_id+'" ><label>'+obj.filename+'</label></a><label class="delete_icon"><a class="bid_attachment_row_delete" data-file-attr = "'+obj.filename+'" id="'+obj.id+'"  data-action-type="'+obj.action_type+'"><i class="fa fa-trash-o delete-btn" aria-hidden="true" ></i></a></label><div class="clearfix"></div></div>');
					if(obj.upload_button_status == '1'){
						$(".upBtn").css('display','block');
					}else{
						$(".upBtn").css('display','none');
					}
				} else if(obj.status == 400) {
					if(obj.location != ''){
						window.location.replace(obj.location);
					}else{ 
						if($("#error_popup").css('display') == 'none'){
							$('#portfolioModalCenter').modal('hide');	
							//$(".upload-btn-wrapper").css('display','none');
							$("#error_popup").addClass("page_refresh");
							$("#error_popup_heading").html(obj.popup_heading);
							$("#error_popup_body").html(obj.error);
							$('#error_popup').modal('show');
						}
					
					}
				} else {
					if($("#error_popup").css('display') == 'none'){
						$("#error_popup_heading").html(obj.popup_heading);
						$("#error_popup_body").html(obj.error);
						$('#error_popup').modal('show');
					}
				}
				
			});
		},
		url: SITE_URL + "user/upload_porfolio_image/", // Set the url
		thumbnailWidth: 80,
		thumbnailHeight: 80,
		parallelUploads: 1,
		acceptedFiles: portfolio_section_plugin_portfolio_image_allowed_file_extensions,
		maxFiles:1,
		maxFilesize: portfolio_image_maximum_size_limit, // MB
		autoProcessQueue : false, // Make sure the files aren't queued until manually added
		//previewsContainer: "#previews", // Define the container to display the previews
		clickable: ".upload-portfolio-image",// Define the element that should be used as click trigger to select files.
		

	});
}	 */
</script>	