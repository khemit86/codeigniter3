<?php $user = $this->session->userdata('user');	?>	
<div class="default_page_heading">
	<h4><div><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_headline_title'):$this->config->item('personal_account_education_section_headline_title'); ?></div></h4>
	<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
</div>
<?php

$total_education_training = count($education_training_data);
$record_counter = 1;
foreach($education_training_data as $education_training_key => $education_training_value){
$last_record_class_remove_border_bottom = '';
if($record_counter == $total_education_training){
$last_record_class_remove_border_bottom = 'default_noborder';
}


?>
<!-- Education Training End -->
<div class="wkExp <?php echo $last_record_class_remove_border_bottom; ?>" id="<?php echo "education_section_".$education_training_value['id']; ?>">
	<?php echo $this->load->view('personal_account_education_training_listing_entry_detail',array('education_training_value'=>$education_training_value)); ?>
</div>
<?php
	$record_counter++;
}
?>


<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($education_training_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
			$listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_section_listing_limit'):$this->config->item('personal_account_education_section_listing_limit');
			$rec_per_page = ($education_training_count > $listing_limit) ? $listing_limit : $education_training_count;
			?>
			<div class="pageOf">
			<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $education_training_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($education_training_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
		<div class="modePage">
		<?php echo $education_training_pagination_links; ?>
		</div>
		</div>
		<?php } ?>
	</div>
</div>
<div class="addAnother"><button type="button" 
	 data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add" class="btn default_btn blue_btn add_education_training"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_education_training_section_add_another_education_btn_txt'):$this->config->item('personal_account_education_training_section_add_another_education_btn_txt') ?></button></div>
<!-- Education Training End -->