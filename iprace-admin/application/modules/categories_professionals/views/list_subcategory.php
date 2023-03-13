<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Sub-category list</a> </li>
            <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'categories_professionals/add_subcategory'; ?>');">Add Talents Sub-category</a></li>
            </ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i>Talents Sub-Category list</h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
					<table class="table table-hover table-bordered adminmenu_list">
					<tr>
                    <td align="left">
                         <select class="form-control col-lg-2" id="pa_id" name="parent_id">
                            <option value="">Please Select</option>
                            <?php
                            foreach ($cat as $key => $val) {
								 $selected = "";
								if(isset($parent_id) && !empty($parent_id) && $val['id'] == $parent_id){
								 $selected = "selected";
								}
                                ?>
                                <option <?php echo $selected; ?> value="<?php echo $val['id']; ?>"><?php echo $val['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
					<td colspan="5" align="right">
					<a href="<?=base_url().'categories_professionals/add_subcategory'?> ">	<input class="btn btn-default" type="button" name="add_subcategory" value="Add Sub-category">
					</td>
					</tr>
					</table>
		<?php
                    if ($this->session->flashdata('succ_msg')) {
                        ?>
                        <div class="alert alert-success" id="success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?= $this->session->flashdata('succ_msg') ?>
                        </div> 
                        <?php
                    }
                    if ($this->session->flashdata('error_msg')) {
                        ?>
                        <div class="alert alert-error" id="error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?= $this->session->flashdata('error_msg') ?>
                        </div>
						<?php
					}
					?>
                    <div class="alert alert-success" style="display:none;" id="success_ajax_msg">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?= $this->session->flashdata('succ_msg') ?>
                     </div> 
					  <div class="alert alert-error" id="error_ajax_msg" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?= $this->session->flashdata('error_msg') ?>
                        </div>
                    <div class="load_data">
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
<script type="text/javascript">

	<?php
	if(isset($parent_id) && !empty($parent_id)){
	?>
	var url = "<?php echo base_url(); ?>"
	var id = '<?php echo $parent_id; ?>';
	$.ajax({
		type: "POST",
		data: {"pa_id":id},
		url: url+"categories_professionals/loadajax",
		success: function (return_data) {            
			$('.load_data').html(return_data);
			
		}
	});   
	
	<?php	
	}
	?>
    $('#pa_id').change(function(){
        var url = "<?php echo base_url(); ?>"
        var id = $(this).val();
		if(id.length != 0){
			$.ajax({
				type: "POST",
				data: {"pa_id":id},
				url: url+"categories_professionals/loadajax",
				success: function (return_data) {            
					$('.load_data').html(return_data);
					
				}
			}); 
		}
    });
$(document).on('click', '.delete_category', function () {
	var section_id = $(this).attr('data-id');
	if (confirm('Are you sure you want to delete this?')) {
		$.ajax({
			url: '<?php echo base_url(); ?>'+'categories_professionals/delete_category',
			type: "POST",
			dataType: "json",
			data: {
				'section_id':section_id
			},
			success: function (response) {
				if(response['status'] == 200){
					html = '<button type="button" class="close" data-dismiss="alert">&times;</button><strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> '+response['message'];
					$("#success_ajax_msg").html(html);
					$("#error_ajax_msg").css('display','none');
					$("#success").css('display','none');
					$("#error").css('display','none');
					$("#success_ajax_msg").css('display','block');
					$("#row_"+section_id).remove();
					
				}
				else if (response['status'] == 400) {
					html = '<button type="button" class="close" data-dismiss="alert">&times;</button><strong><i class="icon24 i-close-4"></i> Oh snap!</strong> '+response['message'];
					$("#error_ajax_msg").html(html);
					$("#success_ajax_msg").css('display','none');
					$("#success").css('display','none');
					$("#error").css('display','none');
					$("#error_ajax_msg").css('display','block');
					
				}
			}
		});
	}
});

$(document).on('click', '.change_sub_category_status', function () {
	var section_id = $(this).attr('data-id');
	if (confirm($(this).attr('data-msg'))) {
		$.ajax({
			url: '<?php echo base_url(); ?>'+'categories_professionals/change_subcategory_status',
			type: "POST",
			dataType: "json",
			data: {
				'section_id':section_id,
				'c_type':'c',
				'data_type':$(this).attr('data-type'),
				'data_status':$(this).attr('data-status')
			},
			success: function (response) {
				if(response['status'] == 200){
					html = '<button type="button" class="close" data-dismiss="alert">&times;</button><strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> '+response['message'];
					$("#success_ajax_msg").html(html);
					$("#error_ajax_msg").css('display','none');
					$("#success_ajax_msg").css('display','block');
					$("#success").css('display','none');
					$("#error").css('display','none');
					$("#category_sub_status_"+section_id).html(response['data']);
				}
				else if (response['status'] == 400) {
					html = '<button type="button" class="close" data-dismiss="alert">&times;</button><strong><i class="icon24 i-close-4"></i> Oh snap!</strong> '+response['message'];
					$("#error_ajax_msg").html(html);
					$("#success_ajax_msg").css('display','none');
					$("#error_ajax_msg").css('display','block');
					$("#success").css('display','none');
					$("#error").css('display','none');
					
				}
			}
		});
	}
});
	
</script>
<script type="text/javascript">
$(document).ready(function() {
	if ("<?=$this->session->flashdata('succ_msg')?>" != "") {
		
		var obj = {
			type: "updateServerDiary",
			text: "areas_of_expertise",
			d: "ppp"
		}
		setTimeout(function() {
			
			window.ws.send(JSON.stringify(obj));
		},3000);
	}
});
</script>