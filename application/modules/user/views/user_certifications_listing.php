<div class="default_page_heading">
	<h4><div><?php 
			$user = $this->session->userdata('user');
			if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
				echo $this->config->item('pa_user_certifications_section_headline_title'); 
			}else if($user[0]->account_type == USER_COMPANY_ACCOUNT_TYPE && $user[0]->is_authorized_physical_person == 'Y') { 
				echo $this->config->item('ca_app_user_certifications_section_headline_title'); 
			}  else {
				echo $this->config->item('ca_user_certifications_section_headline_title'); 
			}
		?></div></h4>
	<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
</div>
<!-- Education Training End -->
<?php
$user = $this->session->userdata('user');
$total_certifications = count($certifications_data);
$record_counter = 1;
foreach($certifications_data as $certifications_key => $certifications_value){
$last_record_class_remove_border_bottom = '';
if($record_counter == $total_certifications){
$last_record_class_remove_border_bottom = 'default_noborder';
}

if($user[0]->account_type == USER_PERSONAL_ACCOUNT_TYPE) { 
	$user_certifications_section_add_another_certifications_btn_txt = $this->config->item('pa_user_certifications_section_add_another_certifications_btn_txt'); 
} else {
	$user_certifications_section_add_another_certifications_btn_txt = $this->config->item('ca_user_certifications_section_add_another_certifications_btn_txt');
}
?>
<div class="wkExp <?php echo $last_record_class_remove_border_bottom; ?>" id="<?php echo "certifications_section_".$certifications_value['id']; ?>" >
	<?php echo $this->load->view('user_certifications_listing_entry_detail',array('certifications_value'=>$certifications_value)); ?>
</div>
<?php
	$record_counter++;
}
?>
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($certifications_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
			$rec_per_page = ($certifications_count > $this->config->item('user_certifications_section_listing_limit')) ? $this->config->item('user_certifications_section_listing_limit') : $certifications_count;
			?>
			<div class="pageOf">
			<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $certifications_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($certifications_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
		<div class="modePage">
		<?php echo $certifications_pagination_links; ?>
		</div>
		</div>
		<?php } ?>
	</div>
</div>

<div class="addAnother"><button type="button" class="btn default_btn blue_btn add_certifications" data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add"><?php echo $user_certifications_section_add_another_certifications_btn_txt; ?></button></div>
<!-- Education Training End -->