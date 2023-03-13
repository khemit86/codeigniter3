<?php
if($po_published_projects_count == 0){
	echo '<div class="default_blank_message padding_top2">'.$this->config->item('no_published_project_message_po_view').'</div>';	
}else if($po_all_projects_created_escrows_count == 0 && $po_published_projects_count == 1){
	echo '<div class="default_blank_message padding_top2">'.$this->config->item('no_financial_activity_on_published_project_message_po_view_singular').'</div>';
}else if($po_all_projects_created_escrows_count == 0 && $po_published_projects_count >= 1){
	echo '<div class="default_blank_message padding_top2">'.$this->config->item('no_financial_activity_on_published_projects_message_po_view_plural').'</div>';
}else if($po_all_projects_created_escrows_count > 0){
?>	
	<?php
	if($po_all_projects_dropdown_list_count >1){	
	?>	
	<div class="resetField">
		<label class="selectDropdown">
			<div class="form-group default_dropdown_select">
				<select id="project_dropdown" disabled>
					<option value=""><?php echo $this->config->item('user_projects_payments_overview_page_select_project_dropdown_option_txt'); ?></option>
					<?php
					foreach($po_all_projects_dropdown_list as $project_key=>$project_value)
					{
						echo '<option data-project-type-attr = "'.$project_value['project_type'].'" value="'.$project_value['project_id'].'">'.$project_value['project_title'].'</option>';
					}	
					?>
				</select>
			</div>
		</label><label>
			<button class="btn default_btn red_btn reset_project_drop_down_button" disabled><?php echo $this->config->item('user_projects_payments_overview_page_reset_field_btn_txt'); ?></button>
		</label>
	</div>
	<?php
	}	
	?>	
	<div class="radioBtn_height_adjust">
		<div class="default_radio_button radio_bttmBdr radio_left_side">
			<section>
				<div data-toggle="tooltip" data-placement="top">
					<input class="payments_tab"  type="radio" id="poRqstPym" name="po_inProgress_payments" value="po_incoming_payment_requests" data-tab-type="requested_escrows">
					<label class="doubleLine_radioBtn" for="poRqstPym">
						<span><?php echo $this->config->item('user_projects_payments_overview_page_incoming_payment_requests_tab_po_view'); ?></span>
					</label>
				</div>
				<div data-toggle="tooltip" data-placement="top">
					<input type="radio" class="payments_tab" name="po_inProgress_payments" value="po_outgoing_escrowed_payments" data-tab-type="active_escrows" id="poEscPym">
					<label class="doubleLine_radioBtn" for="poEscPym">
						<span><?php echo $this->config->item('user_projects_payments_overview_page_outgoing_escrowed_payments_tab_po_view'); ?></span>
					</label>
				</div>
				<div data-toggle="tooltip" data-placement="top">
					<input type="radio" class="payments_tab"  name="po_inProgress_payments" value="po_cancelled_escrow_payments" data-tab-type="cancelled_escrows" id="poCancelEscPym">
					<label class="doubleLine_radioBtn" for="poCancelEscPym">
						<span><?php echo $this->config->item('user_projects_payments_overview_page_cancelled_escrowed_payments_tab'); ?></span>
					</label>
				</div>
				<div data-toggle="tooltip" data-placement="top">
					<input type="radio" class="payments_tab"  name="po_inProgress_payments" value="po_released_payments" data-tab-type="released_escrows" id="poPaidPym">
					<label class="doubleLine_radioBtn" for="poPaidPym">
						<span><?php echo $this->config->item('user_projects_payments_overview_page_released_payments_tab_po_view'); ?></span>
					</label>
				</div>
				<div data-toggle="tooltip" data-placement="top">
					<input type="radio" class="payments_tab" name="po_inProgress_payments" value="po_rejected_payment_requests" data-tab-type="rejected_requested_escrows" id="poRejRqstPym">
					<label class="doubleLine_radioBtn" for="poRejRqstPym">
						<span><?php echo $this->config->item('user_projects_payments_overview_page_rejected_payment_requests_tab_po_view'); ?></span>
					</label>
				</div>
			</section>
		</div>
		<div class="radio_right_side">
			<div id="po_escrows_container" style="display: none;" >
				<?php
				echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_requested_payments_section',array(), true);
				?>	
			</div>
			<?php
			/* <div id="requested_escrows_container" style="display: none;">
				<?php
				echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_requested_payments_section',array(), true);
				?>
			</div>
			<div id="active_escrows_container" style="display: none">
				<?php
				echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_active_escrows_section',array(), true);
				?>
			</div>
			<div id="cancelled_escrows_container" style="display: none">
				<?php
				echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_cancelled_escrows_section',array(), true);
				?>
			</div>
			<div id="released_escrows_container" style="display: none;">
				<?php
				echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_released_escrows_section',array(), true);
				?>
				
			</div>
			<div id="rejected_requested_escrows_container" style="display: none;">
				<?php
				echo $this->load->view('user_projects_payments_overview/user_projects_payments_overview_page_rejected_requested_payments_section',array(), true);
				?>
			</div> */
			?>
		</div>
		<div class="clearfix"></div>
	</div>
<?php
}	
?>	