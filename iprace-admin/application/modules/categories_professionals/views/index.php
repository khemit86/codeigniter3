<?php // $this->load->library('session'); 
 //echo DEMO;
 ?>

<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Category list</a> </li>
            <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'categories_professionals/add'; ?>');">Add Talents Category</a></li>
            </ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i>Talents Category list</h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
					<table class="table table-hover table-bordered adminmenu_list">
					<tr>
					<td colspan="5" align="right">
					<a href="<?=base_url().'categories_professionals/add'?> ">	<input class="btn btn-default" type="button" name="add_category" value="Add Category">
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
                    <table class="table table-hover table-bordered adminmenu_list">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th >Category Name</th>
                                <th>Status</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
					$attr = array(
					'onclick' => "javascript: return confirm('Do you want to delete?');",
					'class' => 'i-cancel-circle-2 red',
					'title' => 'Delete'
					);
					$atr3 = array(
						'onclick' => "javascript: return confirm('Do you want to active this?');",
						'class' => 'i-checkmark-3 red',
						'title' => 'Inactive'
					);
					$atr4 = array(
						'onclick' => "javascript: return confirm('Do you want to inactive this?');",
						'class' => 'i-checkmark-3 green',
						'title' => 'Active'
					);
foreach ($list as $key => $menu) {  
    ?>
                           <!-- <tr onclick="displaySubadminmenu(<?php echo $menu['id']; ?>);" class="pointer_class">-->
                            <tr id="<?php echo "row_".$menu['id'];?>">
                                    <td><?php echo $menu['id']; ?></td>
                                    <td><?php echo $menu['name']; ?></td>
                                    <td align="center" id="<?php echo "category_status_".$menu['id'];?>">
                                        <?php
                                         if ($menu['status'] == 'Y') {
										//echo anchor(base_url() . 'categories_professionals/change_category_status/' . $menu['id'].'/inact/'.$menu['status'].'/c', '&nbsp;', $atr4);
											$data_type = 'inact';
											$class = 'i-checkmark-3 green';
											$msg = 'Do you want to inactive this?';
											
										} else {
											$data_type = 'act';
										//echo anchor(base_url() . 'categories_professionals/change_category_status/' . $menu['id'].'/act/'.$menu['status'].'/c', '&nbsp;', $atr3);
											$class = 'i-checkmark-3 red';
											$msg = "Do you want to active this?";
										}
                                        ?>
										<a href="javascript:;" class="<?php echo $class ?> change_category_status" title="Delete" data-msg="<?php echo $msg; ?>"  data-type="<?php echo $data_type; ?>" data-status ="<?php echo $menu['status']; ?>" data-id="<?php echo $menu['id']; ?>">&nbsp;</a>
										
                                    </td>
                                    <td align="center"><?php
                                    $atr1 = array('class' => 'i-plus-circle-2', 'title' => 'Add', 'style' => 'text-decoration:none',);
                                    $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);
									$atr7=array('class' => 'i-wand', 'title' => 'Add Skill', 'style' => 'text-decoration:none',);
									$atr8=array('class' => 'i-hammer', 'title' => 'View Skill', 'style' => 'text-decoration:none',);
 
                                    //echo anchor(base_url() . 'categories/add/' . $menu['id'], '&nbsp;', $atr1);
                                    echo anchor(base_url() . 'categories_professionals/edit/' . $menu['id'], '&nbsp;', $atr2);
                                   // echo anchor(base_url() . 'categories_professionals/delete_category/' . $menu['id'], '&nbsp;', $attr);
                                        ?>
									<a href="javascript:;" class="i-cancel-circle-2 red delete_category" title="Delete" data-id="<?php echo $menu['id']; ?>">&nbsp;</a>
                                    </td>
                                </tr>
                                <?php
                                if (count($menu['childs']) > 0) {
                                    $childs = $menu['childs'];

                                    if (count($childs) != 0) {
                                        foreach ($childs as $k => $child) {
                                            ?>
                                            <tr class="submenulist  sub_trno_<?php echo $menu['id']; ?>" style="display:none;">
                                                <td colspan="2"></td>
                                                <td><?php echo $child->name; ?></td>
                                            
                                                <td align="center"><?php
                            if ($child->status == 'N') {
                                echo '<i class="i-close-4 red"></i>';
                            } else {
                                echo '<i class="i-checkmark-4 green"></i>';
                            }
                                            ?></td>
                                                <td align="center"><?php
                                    echo anchor(base_url() . 'categories_professionals/edit/' . $child->id, '&nbsp;', $atr2);

                                    echo anchor(base_url() . 'categories_professionals/delete_category/' . $child->id, '&nbsp;', $attr);
									
                                            ?>
                                                </td>
                                            </tr>
                                            <?php
                                        } //4each
                                    }//if
                                }
                                ?>
                            <?php } ?>
                        </tbody>
                    </table>
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
					$("#error_ajax_msg").css('display','block');
					$("#success").css('display','none');
					$("#error").css('display','none');
					
				}
			}
		});
	}
});

$(document).on('click', '.change_category_status', function () {
	var section_id = $(this).attr('data-id');
	if (confirm($(this).attr('data-msg'))) {
		$.ajax({
			url: '<?php echo base_url(); ?>'+'categories_professionals/change_category_status',
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
					$("#success").css('display','none');
					$("#error").css('display','none');
					$("#success_ajax_msg").css('display','block');
					$("#category_status_"+section_id).html(response['data']);
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
function refreshTallentsList() {
	
	window.location.reload();
}
</script>
