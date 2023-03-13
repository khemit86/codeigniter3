<?php
$user = $this->session->userdata ('user');	


if(empty($mark_complete_project_request_data) && $user[0]->user_id == $po_id){	
?>	
<div  class="<?php echo "cmRBtn mark_complete_tab mark_complete_request_data_section_initial_view_".$sp_id; ?>">
	<div class="default_blank_message"><?php 
	if($user[0]->user_id == $po_id){
		echo $this->config->item('project_details_page_no_mark_project_complete_request_msg_po_view');
	}
	?></div>
</div>
<?php
}	
?>	
<?php
$show_next_project_complete_request_style = "display:none;";	 
if(!empty($mark_complete_project_request_data) && $user[0]->user_id == $po_id){
//$show_next_project_complete_request_style = "display:block";

	$latest_project_complete_request_detail = get_latest_project_complete_request_detail(array('winner_id'=>$sp_id,'project_owner_id'=>$po_id,'project_id'=>$project_id),$project_type);
	
	if($latest_project_complete_request_detail['request_status'] == 'expired' || $latest_project_complete_request_detail['request_status'] == 'declined' || $latest_project_complete_request_detail['request_status'] == 'active'){
		
		
		if($latest_project_complete_request_detail['request_status'] == 'declined'){
			$response_time = $latest_project_complete_request_detail['request_declined_on'];
		}
		if($latest_project_complete_request_detail['request_status'] == 'expired'){
			$response_time = $latest_project_complete_request_detail['request_expires_on'];
		}
		if($latest_project_complete_request_detail['request_status'] == 'active'){
			$response_time = $latest_project_complete_request_detail['request_expires_on'];
		
		}
		
		$time_arr = explode(':', $this->config->item('po_send_mark_project_complete_request_time_left_till_next_resent'));
		$next_request_send_date = date('Y-m-d H:i:s',strtotime('+'.(int)$time_arr[0].' hour +'.(int)$time_arr[1].' minutes +'.(int)$time_arr[2].' seconds',strtotime($response_time)));
		
		if($user[0]->user_id == $po_id && strtotime($next_request_send_date) > time()){
			$show_next_project_complete_request_style = "display:block";
		}	
	}	
	


	
}	
?>
<div class="row next_project_complete_request_sent_message_container" style="<?php echo $show_next_project_complete_request_style; ?>">
	<div class="col-md-12 col-sm-12 col-12">
	<h6 class="mpc default_black_bold">
		<?php
				echo '<div>'.$this->config->item('project_details_page_mark_complete_project_request_listing_time_left_send_next_request_txt_po_view')." ".secondsToWordsResponsive(strtotime($next_request_send_date) -time()).'</div>';
		?>
		</h6>
	</div>
</div>
<div  class="<?php echo "payDTls mark_complete_request_data_section_".$sp_id; ?>">
<?php
if(!empty($mark_complete_project_request_data)){	
	foreach($mark_complete_project_request_data as $mark_complete_project_request_key=>$mark_complete_project_request_value){ ?>
	<?php
	echo $this->load->view('bidding/mark_project_complete_request_row_detail_project_detail',array('mark_complete_project_request_value'=>$mark_complete_project_request_value,'winner_id'=>$sp_id,'project_owner_id'=>$po_id,'project_id'=>$project_id,'project_type'=>$project_type,'project_owner_name'=>$project_owner_name,'section_name'=>$section_name), true);
	?>
	<?php
	}
}
?>
</div>
<?php
$show_project_complete_request_style = "display:none;margin: auto;";	
if($user[0]->user_id == $po_id && (empty($mark_complete_project_request_data) || strtotime($next_request_send_date) < time())){
	$show_project_complete_request_style = "display:block;margin: auto;";
}
?>
<div class="mark_completed_btn" style="<?php echo $show_project_complete_request_style; ?>"><button style="<?php echo $show_project_complete_request_style; ?>" type="button" align="center" id="<?php echo "mark_project_complete_button_".$sp_id; ?>" class="btn blue_btn default_btn create_mark_complete_request_confirmation_po" data-section-name="<?php echo $section_name ?>"  data-section-id="<?php echo $sp_id; ?>"  data-project-id="<?php echo $project_id; ?>" data-po-id="<?php echo Cryptor::doEncrypt($po_id) ?>" data-sp-id="<?php echo Cryptor::doEncrypt($sp_id) ?>" ><?php echo $this->config->item('project_details_page_request_project_mark_as_complete_po_view_btn_txt'); ?></button></div>


