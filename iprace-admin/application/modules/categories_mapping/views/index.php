<style>
	.allCategory .row{
		margin-bottom:0px !important;
	}
	.allCategory .i-cancel-disabled {
		color: #f39898 !important
	}
</style>
<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Category Mapping</a> </li>
			</ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i>Categories Mapping</h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
					<div class="succ-alert">

					</div>    
					<!-- <div class="alert alert-success" style="display:none;">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> Category mapping data saved successfully
					</div>-->
					<div class="catMapp">
						<div class="catHead">
							<div class="">
								<div class="col-md-5 col-sm-5 col-12">
									<label>project category</label>
								</div>
								<div class="col-md-5 col-sm-5 col-12">
									<label>professionals category</label>
								</div>
								<div class="col-md-2 col-sm-2 col-12"></div>
							</div>
						</div>
						<?php
							if(!empty($categories_mapping_data)) {
								$professionals_category_id = array_column($categories_mapping_data, 'professionals_category_id');
								$projects_category_id = array_column($categories_mapping_data, 'projects_category_id');
								$first = current($categories_mapping_data);
								unset($categories_mapping_data[0]);
								$categories_mapping_data = array_values($categories_mapping_data);
								$first_option_display = 'block';
								if(!empty($categories_mapping_data) && count($categories_mapping_data) > 0) {
									$first_option_display = 'none';
								}
							}
							
						?>
						<div id='TextBoxesGroup'>
							<div class="allCategory">
								<div class="parent" id="TextBoxDiv1">
									<div class="col-md-5 col-sm-5 col-12">
										<div class="form-group">
											<select name="name[]" class="form-control proj_cate" >
												<option value="" <?php echo empty($first) ? 'selected' : '' ?> style="display : <?php echo $first_option_display; ?>">Select Project Category</option>
												<?php
													foreach($projects_category as $value) { 
												?>				
												<option value="<?php echo $value['id'] ?>" <?php echo ( $value['id'] == $first['projects_category_id']) ? 'selected' : ''?>><?php echo $value['name'] ?></option>
												<?php
													}
												?>						
											</select>
										</div>
									</div>
									<div class="col-md-5 col-sm-5 col-12">
										<div class="form-group">
											<select  name="plant[]" class="form-control professional_cate" <?php echo empty($first) ? 'disabled' : '' ?> >
												<option value="" style="display : <?php echo $first_option_display; ?>">Select Professional Category</option>
												<?php
													$hide_option_id = !empty($professionals_category_id) ? $professionals_category_id : [];
													if (($key = array_search($first['professionals_category_id'], $hide_option_id)) !== false) {
														unset($hide_option_id [$key]);
													}
													foreach($professional_category as $value) { 
														$display = 'block';
														if(in_array($value['id'], $hide_option_id)) {
															$display = 'none';
														}
												?>				
												<option value="<?php echo $value['id'] ?>" style="display:<?php echo $display;?>" <?php echo ($value['id'] == $first['professionals_category_id']) ? 'selected' : ''?>><?php echo $value['name'] ?></option>
												<?php
													}
												?>	
											</select>
										</div>
									</div>
									<div class="col-md-2 col-sm-2 col-12">
										<button  type="button" name="btnSubmit" class="btn btn-danger remove" >
											<i class="i-cancel-circle-2 red <?php echo (empty($categories_mapping_data) ? 'i-cancel-disabled' : ''); ?> " style="color:#fff"></i>
										</button>
									</div>
								</div>

								<?php
									foreach($categories_mapping_data as $key => $map_data) {
										$hide_option_id = !empty($professionals_category_id) ? $professionals_category_id : [];
										if (($key = array_search($map_data['professionals_category_id'], $hide_option_id)) !== false) {
											unset($hide_option_id [$key]);
										}
								?>
								<div class="child" >
									<div class="col-md-5 col-sm-5 col-12">
										<div class="form-group">
											<select name="name[]" class="form-control proj_cate" id="proj_cate_<?php echo $key; ?>">
												<option value="" style="display:none;">Select Project Category</option>
												<?php
													foreach($projects_category as $value) { 
														
												?>				
												<option value="<?php echo $value['id'] ?>"  <?php echo ($value['id'] == $map_data['projects_category_id']) ? 'selected' : ''?>><?php echo $value['name'] ?></option>
												<?php
													}
												?>						
											</select>
										</div>
									</div>
									<div class="col-md-5 col-sm-5 col-12">
										<div class="form-group">
											<select  name="plant[]" id="professional_cate_<?php echo $key; ?>" class="form-control professional_cate" <?php echo empty($first) ? 'disabled' : '' ?>>
												<option value="" style="display:none;">Select Professional Category</option>
												<?php
													foreach($professional_category as $value) { 
														$display = 'block';
														if(in_array($value['id'], $hide_option_id)) {
															$display = 'none';
														}
												?>				
												<option value="<?php echo $value['id'] ?>" style="display:<?php echo $display; ?>" <?php echo ($value['id'] == $map_data['professionals_category_id']) ? 'selected' : ''?>><?php echo $value['name'] ?></option>
												<?php
													}
												?>	
											</select>
										</div>
									</div>
									<div class="col-md-2 col-sm-2 col-12">
										<button  type="button" name="btnSubmit" class="btn btn-danger remove" style="display:block;"><i class="i-cancel-circle-2 red" style="color:#fff"></i></button>
									</div>
								</div>
								<?php
									}
								?>
							</div>
							<div class="moreBtn">
								<div class="row">
									<div class="col-md-5 col-sm-5 col-12">
										<button type="button" name="btnSubmit" id='addButton' class="btn btn-warning" <?php echo empty($first) ? 'disabled' : '' ?>>Add More Category</button>
									</div>
									<div class="col-md-5 col-sm-5 col-12">
										<div class="row">
											<div class="col-md-6 col-sm-6 col-12">
												<button type="button" id="saveButton" class="btn btn-primary" disabled>Save</button>
											</div>
										</div>							
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End .col-lg-6  -->
				</div>
				<!-- End .row-fluid  -->
			</div>
        <!-- End .container-fluid  -->
    </div>
    <!-- End .wrapper  -->
</section>
<script>
var counter = '<?php echo !empty($categories_mapping_data) ? count($categories_mapping_data) : 0;  ?>';
var professional_category_limit = '<?php echo !empty($professional_category) ? count($professional_category) : 0;  ?>';
var professional_category_id = '<?php echo json_encode(!empty($professionals_category_id) ? $professionals_category_id : []) ?>';
var projects_category_id = '<?php echo json_encode(!empty($projects_category_id) ? $projects_category_id : []);  ?>';
professional_category_id = JSON.parse(professional_category_id);
projects_category_id = JSON.parse(projects_category_id);
var professional_category_length = professional_category_id.length;

if(professional_category_id.length == 0) {
	$('.professional_cate option[value=""]').prop('selected', true);
} else if(professional_category_id.length > 1) {
	$('.allCategory').children().first().find('.professional_cate option[value=""]').hide();
}

if(projects_category_id.length == 0) {
	$('.proj_cate option[value=""]').prop('selected', true);
} else if(projects_category_id.length > 1) {
	$('.allCategory').children().first().find('.proj_cate option[value=""]').hide();
}

$.each($('.proj_cate'), function(key, val) {
	$('option[value="'+projects_category_id[key]+'"]', $(this)).prop('selected', true);
});

$.each($('.professional_cate'), function(key, val) {
	$('option[value="'+professional_category_id[key]+'"]', $(this)).prop('selected', true);
});
$('#saveButton').prop('disabled', true);
</script>
<script type="text/javascript" src="<?php echo JS.'modules/categories_mapping.js'?>"></script>