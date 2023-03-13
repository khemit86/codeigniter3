<?php
if($disputes_listing_project_data_count > 0){	
?>	
<!-- Pagination Start -->
<div class="pagnOnly">
	<div class="row">
		<?php
		$manage_paging_width_class = "col-md-12 col-sm-12"; 
		if(!empty($generate_pagination_links_user_projects_disputes_management)){
		$manage_paging_width_class = "col-md-7 col-sm-7"; 
		}
		?>
		<div class="<?php echo $manage_paging_width_class; ?> col-12">
			<?php
				$rec_per_page = ($disputes_listing_project_data_count > $disputes_listing_project_data_paging_limit) ? $disputes_listing_project_data_paging_limit : $disputes_listing_project_data_count;
				?>
			<div class="pageOf">
				<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $disputes_listing_project_data_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
			</div>
		</div>
		<?php 
		if(!empty($generate_pagination_links_user_projects_disputes_management)){
		?>
		<div class="col-md-5 col-sm-5 col-12">
			<div class="modePage">
				<?php echo $generate_pagination_links_user_projects_disputes_management; ?>
			</div>
		</div>
		<?php
		}
		?>
	</div>
</div>
<!-- Pagination End -->	
<?php
}	
?>