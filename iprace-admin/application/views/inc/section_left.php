<aside id="sidebar">
    <div class="side-options">
        <ul class="list-unstyled">
            <li>
                <a href="#" id="collapse-nav" class="act act-primary tip" title="Collapse navigation">
                    <i class="icon16 i-arrow-left-7"></i>
                </a>
            </li>
        </ul>
    </div>
    <?php 
        $controller = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        // pre($controller.' '.$method);
    ?>
    <div class="sidebar-wrapper">
        <nav id="mainnav">
            <ul class="nav nav-list">
                <li class="<?php echo ($controller == 'dashboard') ? 'main_active' : '' ?>">
                    <a href="<?php echo VPATH; ?>">
                        <span class="icon"><i class="icon20 i-screen"></i></span>
                        <span class="txt">Dashboard</span>
                    </a>
                </li>
				<li class="<?php echo ($controller == 'categories_mapping') ? 'main_active' : '' ?>">
                    <a href="<?php echo  base_url() . 'category-mapping'; ?>">
                        <span class="icon"><i class="icon20 i-screen"></i></span>
                        <span class="txt">Category Mapping</span>
                    </a>
                </li>
                <?php
                $cur_page = $this->router->fetch_class();
                $sess_var = $this->session->userdata('user');
                $menu = $this->db->get_where('adminmenu', ['parent_id' => 0])->result_array();
                $menu = array_column($menu, 'id');
                
                for ($i = 1; $i <= sizeof($data); $i++) {
                    if (in_array($data[$i]['id'], $menu)) {
                        $chlid     = $this->auto_model->leftpanelchild($data[$i]['id']);
                        $parent    = $this->auto_model->get_current_controller($data[$i]['id']);
                        $style     = 'class="main_active"';
                        $sub_style = '';
                        if (count($chlid) > 0) {
                            $style = 'class="hasSub"';
                            if ($cur_page != "") {
                                if ($parent['parent_url'] == $cur_page) {
                                    $style     = 'class="hasSub current main_active"';
                                    $sub_style = " show";
                                }
                            }
                        } ?>

                        <li <?php echo $style; ?>>
                            <a href="javascript:void(0);" class="tip" >
                                <span class="icon"><i class="icon20 <?= $data[$i]['style_class'] ?>"></i></span>
                                <span class="txt"><?php echo $data[$i]['name']; ?></span>
                            </a>
                            <ul class="sub<?php echo $sub_style; ?>">
                                <?php 
                                    
                                    foreach ($chlid as $childmenu) { 
                                        $sub_menu_cls = '';
                                        if($method != 'index') {
                                            if(preg_match('/\b('.$method.')\b/', $childmenu['url'])) {
                                                $sub_menu_cls = 'active';
                                            }
                                        } else if($method == 'index') {
                                            if($controller == str_replace("/", "", $childmenu['url'])) {
                                                $sub_menu_cls = 'active';
                                            }
                                        }
                                ?>

                                    <li class="<?php echo $sub_menu_cls;?>" data-val="<?php echo $controller.' '.$method.' '.$childmenu['url']; ?>">
                                        <a href="<?php echo  base_url() . $childmenu['url']; ?>" class="tip" >
                                            <span class="icon"><i class="icon20 i-stack-list"></i></span>
                                            <span class="txt"><?php echo $childmenu['name']; ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>
        <!-- End #mainnav -->
    </div>
    <!-- End .sidebar-wrapper  -->
</aside><!-- End #sidebar  -->

