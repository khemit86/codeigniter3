<?php // $this->load->library('session'); 
 //echo DEMO;
 ?>

<section id="content">
    <div class="wrapper">
        <div class="crumb">
            <ul class="breadcrumb">
            <li class="active"><a href="<?= base_url()?>"><i class="icon16 i-home-4"></i>Home</a></li>
            <li class="active">Category list</a> </li>
            <li class="active"><a onclick="redirect_to('<?php echo base_url() . 'categories_projects/add'; ?>');">Add Category</a></li>
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
					<td colspan="5" align="right">
					<a href="<?=base_url().'categories_projects/add'?> ">	<input class="btn btn-default" type="button" name="add_category" value="Add Category">
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
                            <tr onclick="displaySubadminmenu(<?php echo $menu['id']; ?>);" class="pointer_class">
                                    <td><?php echo $menu['id']; ?></td>
                                    <td><?php echo $menu['name']; ?></td>
                                    <td align="center">
                                        <?php
                                         if ($menu['status'] == 'Y') {
										echo anchor(base_url() . 'categories_projects/change_category_status/' . $menu['id'].'/inact/'.$menu['status'].'/c', '&nbsp;', $atr4);
									
										} else {
									
										echo anchor(base_url() . 'categories_projects/change_category_status/' . $menu['id'].'/act/'.$menu['status'].'/c', '&nbsp;', $atr3);
										}
                                        ?>
                                    </td>
                                    <td align="center"><?php
                                    $atr1 = array('class' => 'i-plus-circle-2', 'title' => 'Add', 'style' => 'text-decoration:none',);
                                    $atr2 = array('class' => 'i-highlight', 'title' => 'Edit', 'style' => 'text-decoration:none',);
									$atr7=array('class' => 'i-wand', 'title' => 'Add Skill', 'style' => 'text-decoration:none',);
									$atr8=array('class' => 'i-hammer', 'title' => 'View Skill', 'style' => 'text-decoration:none',);
 
                                    //echo anchor(base_url() . 'categories_projects/add/' . $menu['id'], '&nbsp;', $atr1);
                                    echo anchor(base_url() . 'categories_projects/edit/' . $menu['id'], '&nbsp;', $atr2);
                                    echo anchor(base_url() . 'categories_projects/delete_category/' . $menu['id'], '&nbsp;', $attr);
                                        ?>
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
                                    echo anchor(base_url() . 'categories_projects/edit/' . $child->id, '&nbsp;', $atr2);

                                    echo anchor(base_url() . 'categories_projects/delete_category/' . $child->id, '&nbsp;', $attr);
								
									
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
