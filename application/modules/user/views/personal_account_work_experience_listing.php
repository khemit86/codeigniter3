<?php $user = $this->session->userdata('user');	?>	
<div class="default_page_heading">
	<h4><div><?php 
			if($user[0]->is_authorized_physical_person == 'Y') {
				echo $this->config->item('company_account_app_work_experience_section_headline_title');
			} else {
				echo $this->config->item('personal_account_work_experience_section_headline_title');
			}?></div>
	</h4>
	<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
</div>
<!-- work_experience Training End -->


<?php

$total_work_experience = count($work_experience_data);
$record_counter = 1;
foreach($work_experience_data as $work_experience_key => $work_experience_value){
$last_record_class_remove_border_bottom = '';
if($record_counter == $total_work_experience){
$last_record_class_remove_border_bottom = 'default_noborder';
}


?>
<!-- Education Training End -->
<div class="wkExp <?php echo $last_record_class_remove_border_bottom; ?>" id="<?php echo "work_experience_section_".$work_experience_value['id']; ?>">
	<?php echo $this->load->view('personal_account_work_experience_listing_entry_detail',array('work_experience_value'=>$work_experience_value)); ?>
</div>
<?php
	$record_counter++;
}
?>


<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($work_experience_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		$work_experience_section_listing_limit = ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_listing_limit'):$this->config->item('personal_account_work_experience_section_listing_limit')
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
			$rec_per_page = ($work_experience_count > $work_experience_section_listing_limit) ? $work_experience_section_listing_limit : $work_experience_count;
			?>
			<div class="pageOf">
			<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $work_experience_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($work_experience_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
		<div class="modePage">
		<?php echo $work_experience_pagination_links; ?>
		</div>
		</div>
		<?php }
		?>
	</div>
</div>
<div class="addAnother"><button type="button" class="btn default_btn blue_btn add_work_experience" data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add"><?php echo ($user[0]->is_authorized_physical_person == 'Y')?$this->config->item('company_account_app_work_experience_section_add_another_work_experience_btn_txt'):$this->config->item('personal_account_work_experience_section_add_another_work_experience_btn_txt'); ?></button></div>
<!-- work_experience Training End -->

