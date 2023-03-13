<div class="row">
	<div class="col-md-12 col-sm-12 col-12 cdTAb">
		<!-- <label for="pwd"><i class="fas fa-globe icon_modify" aria-hidden="true"></i><b class="default_black_bold"><?php //echo $this->config->item('account_management_contact_details_tab_website'); ?></b></label> -->
		<div id="addeditWebsiteUrl" style="display:<?php echo empty($website_url)? "block":"none"; ?>">
			<div>
				<input type="text" class="form-control default_input_field avoid_space_input" placeholder="<?php echo $this->config->item('account_management_contact_details_website_url_input_placeholder'); ?>" value="" id="website_url_input">
			</div>
			<div class="error_div_sectn clearfix"><span class="error_msg" id="website_url_error"></span>
				<div class="amBtn">
					<button type="button" class="btn red_btn default_btn cancel_contact_details" data-action-type ="cancel" data-section-name="website_url" data-view-section="viewWebsiteUrl" data-add-edit-section="addeditWebsiteUrl"><?php echo $this->config->item('cancel_btn_txt'); ?></button><button type="button" class="btn blue_btn default_btn save_contact_details" data-section-name="website_url" id="save_website_url_button"  data-view-section="viewWebsiteUrl" data-add-edit-section="addeditWebsiteUrl" ><?php echo $this->config->item('save_btn_txt'); ?></button>
				</div>
			</div>
		</div>
		<div id="viewWebsiteUrl" style="display:<?php echo !empty($website_url)? "block":"none"; ?>">
			<div>
				<input type="text" id="website_url_input_view" class="form-control default_input_field"  disabled="disabled" value="<?php echo $website_url; ?>">
			</div>
			<div class="amBtn amEditBtn">
				<button type="button" class="btn red_btn default_btn delete_contact_details" data-section-name="website_url"    data-view-section="viewWebsiteUrl" data-add-edit-section="addeditWebsiteUrl"><?php echo $this->config->item('delete_btn_txt'); ?></button><button type="button" class="btn green_btn default_btn edit_contact_details" data-section-name="website_url" data-action-type ="edit" data-view-section="viewWebsiteUrl" data-add-edit-section="addeditWebsiteUrl"><?php echo $this->config->item('edit_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>