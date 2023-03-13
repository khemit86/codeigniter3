<?php
if($disputed_initiated_status ==0){
?>

<p><span>Dispute:</span> <?php echo $dispute_initiated_by_user_name; ?> vs <?php echo $disputed_against_user_name;?><small>(<?php echo $project_dispute_stage; ?>)</small><span>Total Amount Dispute <?php echo str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY; ?></span></p>

<?php
}else{	
?>	
<p><span>Dispute:</span> <?php echo $dispute_initiated_by_user_name; ?> vs <?php echo $disputed_against_user_name;?><small>&nbsp;</small><span>Total Amount Dispute <?php echo str_replace(".00","",number_format($disputed_amount,  2, '.', ' '))." ".CURRENCY; ?></span></p>
<div id="dispute_heading">
<?php
if($project_dispute_status == 1){
?>
	<p><span><?php echo $dispute_started_heading; ?></span></p>
	<p>Dispute status:<span> <?php echo $project_dispute_stage; ?></span></p>
<?php
}else{
	if($project_dispute_status == 2){
?>
	
	<p>Dispute status:<span> <?php echo $project_dispute_stage; ?></span>&nbsp;&nbsp;Dispute Closed Time : <span><?php echo date(DATE_TIME_FORMAT_EXCLUDE_SECOND,strtotime($dispute_end_date)); ?></span></p> 
	<?php
	}
	?>
	
<?php
}
?>
</div>
<?php
}	
?>	