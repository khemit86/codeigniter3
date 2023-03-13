<?php
if($this->session->userdata ('user')){
	$user = $this->session->userdata ('user');
	if($user[0]->user_id == $bidder_data['user_id']){
		$bidder_bid_class = 'boder-color';
	}
}	
?>	

<div class="freeBid bidding_list fLancerbidding <?php echo $bidder_bid_class; ?>" id="<?php echo $project_data['project_type'] == 'fulltime' ? "bidder_list_".$bidder_data['employee_id'] : "bidder_list_".$bidder_data['bidder_id']; ?>">
	
    <div class="bidderList">
			<?php
			echo $this->load->view('bidding/bidder_bid_information', array('bidder_data'=>$bidder_data,'project_data'=>$project_data, 'project_status' => $project_status, 'user_profile_picture' => $user_profile_picture), true); 
			?>
    </div>
		
    <div id="<?php echo $project_data['project_type'] == 'fulltime' ? "edit_bid_form_container_".$bidder_data['employee_id'] : "edit_bid_form_container_".$bidder_data['bidder_id']; ?>"></div>
	<div class="clearfix"></div>
</div>