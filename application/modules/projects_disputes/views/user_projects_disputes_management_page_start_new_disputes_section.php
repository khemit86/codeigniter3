<?php 
$user = $this->session->userdata ('user');
if($new_disputes_projects_listing_data_count > 0){	
?>
	<div class="newDispute">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12 openDispute_Select">
				<div class="default_dropdown_select">
				
					<select class="open_dispute">
					<?php if($new_disputes_projects_listing_data_count > 1){ ?>
					<option value="" style="display:none"><?php echo $this->config->item('user_projects_disputes_management_page_select_project_dropdown_option_txt'); ?></option>
					<?php } ?>
						<?php
						foreach($new_disputes_projects_listing_data as $key=>$value){
							if($value['account_type'] == USER_PERSONAL_ACCOUNT_TYPE){
								$name = $value['first_name']." ".$value['last_name'];
							}
							if($value['account_type'] == USER_COMPANY_ACCOUNT_TYPE){
								$name = $value['company_name'];
							}						
							
							
							$option = "(".$name." - ".str_replace(".00","",number_format($value['total_escrow_amount'],  2, '.', ' '))." ".CURRENCY.") ".htmlspecialchars(trim($value['project_title']), ENT_QUOTES);
							
							
						?>
						
						<option data-initiator-id="<?php echo $user[0]->user_id; ?>" data-po-id="<?php echo $value['project_owner_id']; ?>" data-sp-id="<?php echo $value['winner_id']; ?>" data-project-type="<?php echo $value['project_type']; ?>" data-project-id="<?php echo $value['project_id']; ?>"><?php echo $option; ?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-12">
				<?php if($new_disputes_projects_listing_data_count > 1){ ?>
				<button type="button" class="fullWidth btn green_btn default_btn open_dispute_button"  disabled><?php echo $this->config->item('user_projects_disputes_management_page_open_dispute_button_txt'); ?></button>
				<?php 
				}if($new_disputes_projects_listing_data_count == 1){
				?>
				<button type="button" class="fullWidth btn green_btn default_btn open_dispute_button"><?php echo $this->config->item('user_projects_disputes_management_page_open_dispute_button_txt'); ?></button>
				<?php
				}
				?>
			</div>
		</div>
	</div>
<?php
}else{
	if($view_type == 'po'){
		if($published_project_count == 0){
			echo '<div class="default_blank_message">'.$this->config->item('user_projects_disputes_management_page_no_published_project_message_po_view').'</div>';	
		}else if($projects_active_escrows_count == 0 && $published_project_count == 1){
			echo '<div class="default_blank_message">'.$this->config->item('user_projects_disputes_management_page_no_financial_activity_on_published_project_message_po_view_singular').'</div>';	
		}else if($projects_active_escrows_count == 0 && $published_project_count >= 1){
			echo '<div class="default_blank_message">'.$this->config->item('user_projects_disputes_management_page_no_financial_activity_on_published_projects_message_po_view_plural').'</div>';
		}
	}
	if($view_type == 'sp'){
		if($published_project_count == 0){
			echo '<div class="default_blank_message">'.$this->config->item('user_projects_disputes_management_page_no_project_message_sp_view').'</div>';	
		}else if($projects_active_escrows_count == 0 && $published_project_count == 1){
			echo '<div class="default_blank_message">'.$this->config->item('user_projects_disputes_management_page_no_financial_activity_on_project_message_sp_view_singular').'</div>';	
		}else if($projects_active_escrows_count == 0 && $published_project_count >= 1){
			echo '<div class="default_blank_message">'.$this->config->item('user_projects_disputes_management_page_no_financial_activity_on_projects_message_sp_view_plural').'</div>';
		}
	}			
	
}	
?>
<!--
<div class="col-md-12 col-sm-12 col-12">
	<button type="button" class="btn nxtBtn default_btn blue_btn">Next</button>
</div>-->
	
