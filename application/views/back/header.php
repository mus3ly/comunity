<style type="text/css">
    .navbar-content{
        display:flex;
    }
    #days, #hours, #minutes, #seconds{
        font-size:16px;
    }
    #countdown{
        margin:auto 100px;
        text-transform: uppercase;
        border-radius: 9px;
        background-color: #FECB00;
        color: white;
    }
     #countdown span:after{
         
     }
    #countdown ul{
        list-style: none;
    }
    #countdown ul li{
        display: inline-block;
        text-align: center;
        width:100px;
    }
</style>
<header id="navbar">
    <?php if(demo()) { ?>
        <div class="nav text-center">
            <i class="text-danger blink_me fa fa-exclamation-triangle" style="font-size: 16px"></i>
            For demo purpose many operations including deletion,emailing,file uploading are <strong>DISABLED</strong>
        </div>
    <?php } ?>
    <div id="navbar-container" class="boxed">
        <!--Brand logo & name-->
        <div class="navbar-header">
            <a href="<?php echo base_url(); ?><?php echo $this->session->userdata('title'); ?>" class="navbar-brand">
                <img src="<?php echo $this->crud_model->logo('admin_login_logo'); ?>" alt="<?php echo $system_name;?>" class="brand-icon" style="padding:8px;">
                <div class="brand-title">
                    <span class="brand-text"><?php echo $system_name;?></span>
                </div>
            </a>
        </div>
        <!--End brand logo & name-->

        <!--Navbar Dropdown-->
        <div class="navbar-content clearfix">
            <ul class="nav navbar-top-links pull-left">
                <!--Navigation toogle button-->
                <li class="tgl-menu-btn">
                    <a class="mainnav-toggle">
                        <i class="fa fa-navicon fa-lg"></i>
                    </a>
                </li>
                <!--End Navigation toogle button-->
            </ul>

            <ul class="nav navbar-top-links pull-right">
                <li>
                    <div class="lang-selected" style="margin-top:10px;">
                            <?php
                                if($this->session->userdata('title') == 'admin'){
                            ?>
                                <a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-default">
                                    <i class="fa fa-desktop"></i>  <?php echo translate('visit_home_page');?>
                                </a>
                            <?php
                                } elseif ($this->session->userdata('title') == 'vendor') {
                                    $vend = $this->db->where('vendor_id',$this->session->userdata('vendor_id'))->get('vendor')->row();
                            ?>
                                <a href="<?php echo $this->crud_model->product_link($vend->bpage); ?>" target="_blank" class="btn btn-default">
                                    <i class="fa fa-desktop"></i>  <?php echo translate('visit_my_homepage');?>
                                </a>
                            <?php
                                }
                            ?>
                    </div>
                </li>
                <li id="dropdown-user" class="dropdown">
                    <a href="<?php echo base_url(); ?>template/back/#" data-toggle="dropdown" class="dropdown-toggle text-right">
                        <span class="pull-right">
                            <?php
                                if($this->session->userdata('title') == 'admin'){
                            ?>
                                <img class="img-circle img-user media-object" src="<?php echo base_url(); ?>template/back/img/av1.png" alt="Profile Picture">
                            <?php
                                } elseif ($this->session->userdata('title') == 'vendor') {
									if(file_exists("uploads/vendor_logo_image/logo_".$this->session->userdata('vendor_id').".png")){
                            ?>
                            	<img class="img-circle img-user media-object" src="<?php echo base_url(); ?>uploads/vendor_logo_image/logo_<?php echo $this->session->userdata('vendor_id'); ?>.png" alt="Profile Picture">
								<?php
                                    }else{
                                ?>
                                <img class="img-circle img-user media-object" src="<?php echo base_url(); ?>uploads/vendor_logo_image/default.jpg" alt="Profile Picture">
                            <?php
									}
                                }
                            ?>
                        </span>
                        <div class="username hidden-xs">
							<?php 
								if($this->session->userdata('title') == 'admin'){
									echo $this->session->userdata('admin_name');
								} elseif ($this->session->userdata('title') == 'vendor') {
									echo $this->session->userdata('vendor_name');	
								}
							?>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right with-arrow panel-default">
                        <!-- User dropdown menu -->
                        <ul class="head-list">
                            <li>
                                <?php 
                                    if($this->session->userdata('title') == 'admin'){
                                        $url = base_url().'admin/manage_admin/';
                                    } elseif ($this->session->userdata('title') == 'vendor') {
                                        $url = base_url().'vendor/manage_vendor/';  
                                    }
                                ?>
                                <a href="<?php echo $url; ?>">
                                    <i class="fa fa-user fa-fw fa-lg"></i> <?php echo translate('profile');?>
                                </a>
                            </li>
                        </ul>

                        <!-- Dropdown footer -->
                        <div class="pad-all text-right">
                            <a href="<?php echo base_url(); ?><?php echo $this->session->userdata('title'); ?>/logout/" class="btn btn-primary">
                                <i class="fa fa-sign-out fa-fw"></i> <?php echo translate('logout');?>
                            </a>
                        </div>
                    </div>
                </li>
                <!--End user dropdown-->
            </ul>
        </div>
    </div>
</header>
