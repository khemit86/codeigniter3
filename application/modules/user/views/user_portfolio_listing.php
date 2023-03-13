<?php
$user = $this->session->userdata('user');	
?>
<div class="default_page_heading">
	<h4><div><?php echo $this->config->item('user_portfolio_section_headline_title'); ?></div></h4>
	<span><sup>__________</sup><sub><i class="fas fa-check"></i></sub><sup>__________</sup></span>
</div>
<!-- portfolio Management Text End -->
<!-- portfolio Nodata Found Start -->
<?php
$total_portfolio = count($portfolio_data);
$record_counter = 1;
foreach($portfolio_data as $portfolio_key => $portfolio_value){
$last_record_class_remove_border_bottom = '';
if($record_counter == $total_portfolio){
$last_record_class_remove_border_bottom = 'default_noborder';
}
?>
<div class="wkExp <?php echo $last_record_class_remove_border_bottom; ?>" id="<?php echo "portfolio_section_".$portfolio_value['portfolio_id']; ?>">
<?php
echo $this->load->view('user_portfolio_listing_entry_detail',array('portfolio_value'=>$portfolio_value)); 
?>
</div>
<?php
	$record_counter++;
}
?>
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($portfolio_pagination_links)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
			$rec_per_page = ($portfolio_count > $this->config->item('user_portfolio_section_listing_limit')) ? $this->config->item('user_portfolio_section_listing_limit') : $portfolio_count;
			?>
			<div class="pageOf">
			<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $portfolio_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($portfolio_pagination_links)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
		<div class="modePage">
		<?php echo $portfolio_pagination_links; ?>
		</div>
		</div>
		<?php }
		?>
	</div>
</div>
<?php

if($user_detail['current_membership_plan_id'] == '1'){ // for free
		
	$portfolio_section_number_portfolio_slots_allowed = $this->config->item('user_portfolio_page_section_free_membership_subscriber_number_portfolio_slots_allowed');	
	
	if ($portfolio_section_number_portfolio_slots_allowed > $portfolio_count){
		$add_portfolio_button_style = "";
		$add_portfolio_button_free_member_style = "display:none";
	
	}else{
		
		$add_portfolio_button_style = "display:none";
		$add_portfolio_button_free_member_style = "";
	}
	if($portfolio_count >= $this->config->item('user_portfolio_page_section_gold_membership_subscriber_number_portfolio_slots_allowed')){
		
		$add_portfolio_button_style = "display:none";
		$add_portfolio_button_free_member_style = "display:none";
	}
	
}else{	
	$portfolio_section_number_portfolio_slots_allowed = $this->config->item('user_portfolio_page_section_gold_membership_subscriber_number_portfolio_slots_allowed');	
	$add_portfolio_button_style = "display:none";
	$add_portfolio_button_free_member_style = "display:none";
	
	if ($portfolio_section_number_portfolio_slots_allowed > $portfolio_count){
		
		$add_portfolio_button_style = "";
	}
}
?>
<div class="addAnother">
	<button type="button" id="add_another_portfolio" style="<?php echo $add_portfolio_button_style; ?>" class="btn default_btn blue_btn add_portfolio" data-uid="<?php echo Cryptor::doEncrypt($user[0]->user_id); ?>" data-attr = "add"><?php echo $this->config->item('portfolio_section_add_another_portfolio_btn_txt'); ?></button>
	
	<?php /* <a href = "<?php echo VPATH. $this->config->item("membership_page_url"); ?>" class="membership_page_link" id="addPortfolioBtnFreeSubscriber" style="<?php echo $add_portfolio_button_free_member_style; ?>"><?php echo $this->config->item('user_portfolio_section_user_portfolio_free_subscribers_upgrade_gold_membership_plan_portfolio_btn'); ?></a> */ ?>
	<?php
	/* <button type="button" class="btn default_btn orange_btn margin_top15" id="addPortfolioBtnFreeSubscriber" style="<?php echo $add_portfolio_button_free_member_style; ?>"><?php echo $this->config->item('user_portfolio_section_user_portfolio_free_subscribers_upgrade_gold_membership_plan_portfolio_btn'); ?></button> */?>
	
	
	
</div>
<span class="free_subscriber_max_entries_membership_upgrade_calltoaction" style="<?php echo $add_portfolio_button_free_member_style; ?>">
	<?php  echo str_replace("{membership_page_url}",VPATH. $this->config->item("membership_page_url"),$this->config->item('user_portfolio_page_free_membership_subscriber_max_portfolio_entries_membership_upgrade_calltoaction')); ?>
</span>
 <!---portfolio End -->
										