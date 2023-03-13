<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Sub-category list</a> </li>
            <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'categories_projects/add_subcategory'; ?>');">Add Sub-category</a></li>
            </ul>
        </div>
        <div class="container-fluid">
            <div id="heading" class="page-header">
                <h1><i class="icon20 i-table-2"></i> Category list</h1>
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
                                ?>
                                <option value="<?php echo $val['id']; ?>"><?php echo $val['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
					<td colspan="5" align="right">
					<a href="<?=base_url().'categories_projects/add_subcategory'?> ">	<input class="btn btn-default" type="button" name="add_subcategory" value="Add Sub-category">
					</td>
					</tr>
					</table>
		<?php
                    if ($this->session->flashdata('succ_msg')) {
                        ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> <?= $this->session->flashdata('succ_msg') ?>
                        </div> 
                        <?php
                    }
                    if ($this->session->flashdata('error_msg')) {
                        ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong><i class="icon24 i-close-4"></i> Oh snap!</strong> <?= $this->session->flashdata('error_msg') ?>
                        </div>
    <?php
}
?>
                    
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
    $('#pa_id').change(function(){
        var url = "<?php echo base_url(); ?>"
        var id = $(this).val();
        $.ajax({
            type: "POST",
            data: {"pa_id":id},
            url: url+"categories_projects/loadajax",
            success: function (return_data) {            
                $('.load_data').html(return_data);                
            }
        });   
    });
</script>