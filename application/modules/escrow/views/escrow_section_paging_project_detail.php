<?php
if($escrow_count > 0){	
?>
<div class="paging_section">	
	<!-- Pagination Start -->
	<div class="pagnOnly radio_bttmBdr">
		<div class="row">
			<?php
			$manage_paging_width_class = "col-md-12 col-sm-12"; 
			if(!empty($generate_pagination_links_escrow)){
			$manage_paging_width_class = "col-md-7 col-sm-7"; 
			}
			?>
			<div class="<?php echo $manage_paging_width_class; ?> col-12">
				<?php
					$rec_per_page = ($escrow_count > $escrow_paging_limit) ? $escrow_paging_limit : $escrow_count;
					?>
				<div class="pageOf">
					<label><?php echo $this->config->item('showing_pagination_txt') ?> <span class="page_no">1</span> - <span class="rec_per_page"><?php echo $rec_per_page; ?></span> <?php echo $this->config->item('out_of_total_pagination_txt') ?> <span class="total_rec"><?php echo $escrow_count; ?></span> <?php echo $this->config->item('listings_pagination_txt') ?></label>
				</div>
			</div>
			<?php 
			if(!empty($generate_pagination_links_escrow)){
			?>
			<div class="col-md-5 col-sm-5 col-12">
				<div class="modePage">
					<?php echo $generate_pagination_links_escrow; ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- Pagination End -->	
<?php
}	
?>