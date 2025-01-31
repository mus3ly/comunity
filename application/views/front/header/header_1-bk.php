
<!-- Header top bar -->

<div class="top-bar">

    <div class="container">

        <div class="top-bar-left">

            <!--<ul class="list-inline">

                <li class="dropdown flags">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <?php

                            if($set_lang = $this->session->userdata('language')){} else {

                                $set_lang = $this->db->get_where('general_settings',array('type'=>'language'))->row()->value;

                            }

                            $lid = $this->db->get_where('language_list',array('db_field'=>$set_lang))->row()->language_list_id;

                            $lnm = $this->db->get_where('language_list',array('db_field'=>$set_lang))->row()->name;

                        ?>

                        <img src="<?php echo $this->crud_model->file_view('language_list',$lid,'','','no','src','','','.jpg') ?>" width="20px;" alt=""/> <span class="hidden-xs"><?php echo $lnm; ?></span><i class="fa fa-angle-down"></i></a>

                        <ul role="menu" class="dropdown-menu">

                            <?php

                                $langs = $this->db->get_where('language_list',array('status'=>'ok'))->result_array();

                                foreach ($langs as $row)

                                {

                            ?>

                                <li <?php if($set_lang == $row['db_field']){ ?>class="active"<?php } ?> >

                                    <a class="set_langs" data-href="<?php echo base_url(); ?>home/set_language/<?php echo $row['db_field']; ?>">

                                        <img src="<?php echo $this->crud_model->file_view('language_list',$row['language_list_id'],'','','no','src','','','.jpg') ?>" width="20px;" alt=""/>

                                        <?php echo $row['name']; ?>

                                        <?php if($set_lang == $row['db_field']){ ?>

                                            <i class="fa fa-check"></i>

                                        <?php } ?>

                                    </a>

                                </li>

                            <?php

                                }

                            ?>

                    </ul>

                </li>

                <li class="dropdown flags" style="z-index: 1001;">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <?php                                            

                            if($currency_id = $this->session->userdata('currency')){} else {

                                $currency_id = $this->db->get_where('business_settings', array('type' => 'currency'))->row()->value;

                            }

                            $symbol = $this->db->get_where('currency_settings',array('currency_settings_id'=>$currency_id))->row()->symbol;

                            $c_name = $this->db->get_where('currency_settings',array('currency_settings_id'=>$currency_id))->row()->name;

                        ?>

                        <span class="hidden-xs"><?php echo $c_name; ?></span> (<?php echo $symbol; ?>)

                        <i class="fa fa-angle-down"></i>

                    </a>

                    <ul role="menu" class="dropdown-menu">

                        <?php

                            $currencies = $this->db->get_where('currency_settings',array('status'=>'ok'))->result_array();

                            foreach ($currencies as $row)

                            {

                        ?>

                            <li <?php if($currency_id == $row['currency_settings_id']){ ?>class="active"<?php } ?> >

                                <a class="set_langs" data-href="<?php echo base_url(); ?>home/set_currency/<?php echo $row['currency_settings_id']; ?>">

                                    <?php echo $row['name']; ?> (<?php echo $row['symbol']; ?>)

                                    <?php if($currency_id == $row['currency_settings_id']){ ?>

                                        <i class="fa fa-check"></i>

                                    <?php } ?>

                                </a>

                            </li>

                        <?php

                            }

                        ?>

                    </ul>

                </li>

                <?php if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){ ?>

                    <li class="dropdown flags" style="z-index: 1001;">

                        <a href="<?=base_url()?>home/premium_package" class="" >

                            <i class="fa fa-gift"></i> <?php echo translate('premium_packages');?>

                        </a>

                    </li>

                <?php } ?>

                <?php if(demo()) { ?>

                    <li class="dropdown flags" style="z-index: 1001;">

                        <i class="text-danger blink_me fa fa-exclamation-triangle" style="font-size: 16px"></i>

                        For demo purpose many operations including deletion,emailing,file uploading are <strong>DISABLED</strong>

                    </li>

                <?php } ?>

            </ul>-->
            <div style="margin: 4px 0 0;" class="welcome">Welcome to Our Marketplace</div>
        </div>

        <div class="top-bar-right">

            ----- ----- -----

        </div>

    </div>

</div>

<!-- /Header top bar -->



<!-- HEADER -->

<header class="header header-logo-left">

    <div class="header-wrapper">

        <div class="container">

            <div class="flex-row">

                <div class="flex-col-6 flex-col-lg-auto">

                    <!-- Logo -->

                    <div class="logo">

                        <?php

                            $home_top_logo = $this->db->get_where('ui_settings',array('type' => 'home_top_logo'))->row()->value;

                        ?>

                        <a href="<?php echo base_url();?>">

                            <img src="<?php echo base_url(); ?>uploads/logo_image/logo_<?php echo $home_top_logo; ?>.png" alt="SuperShop"/>

                        </a>

                    </div>

                    <!-- /Logo -->

                </div>

                <div class="flex-col flex-order-last flex-order-lg-0">

                    <!-- Header search -->

                    <div class="header-search mt-4 mt-lg-0 px-xl-5">                            

                        <?php

                            echo form_open(base_url() . 'home/text_search/', array(

                                'method' => 'post',

                                'accept-charset' => "UTF-8"

                            ));

                        ?>

                            <div class="d-flex position-relative">

                                <div class="flex-grow-1">

                                    <input class="form-control" type="text" name="query"  accept-charset="utf-8" placeholder="<?php echo translate('what_are_you_looking_for');?>?"/>

                                </div>

                                <div>

                                    <select

                                        class="selectpicker header-search-select cat_select hidden-xs" data-live-search="true" name="category"

                                        data-toggle="tooltip" title="<?php echo translate('select');?>">

                                        <option value="0"><?php echo translate('all_categories');?></option>

                                        <?php 

                                            $categories = $this->db->get('category')->result_array();

                                            foreach ($categories as $row1) {

                                                if($this->crud_model->if_publishable_category($row1['category_id'])){

                                        ?>

                                        <option value="<?php echo $row1['category_id']; ?>"><?php echo $row1['category_name']; ?></option>

                                        <?php 

                                                }

                                            }

                                        ?>

                                    </select>

                                </div>

                                <div>

                                    <?php

                                        if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {

                                    ?>

                                    <select

                                        class="selectpicker header-search-select d-none" data-live-search="true" name="type" onchange="header_search_set(this.value);"

                                        data-toggle="tooltip" title="<?php echo translate('select');?>">

                                        <option value="product"><?php echo translate('product');?></option>

                                        <option value="vendor"><?php echo translate('vendor');?></option>                    

                                    </select>

                                </div>

                                <?php

                                    }

                                ?>

                                <button class="shrc_btn"><i class="fa fa-search"></i></button>

                            </div>

                        </form>

                    </div>

                    <!-- /Header search -->

                </div>

                <div class="flex-col-6 flex-col-lg-auto text-right">

                    <!-- Header shopping cart -->

                    <div class="header-cart">

                        <div class="cart-wrapper">

                            <a href="<?php echo base_url(); ?>home/compare" class="btn btn-theme-transparent" id="compare_tooltip" data-toggle="tooltip" data-original-title="<?php echo $this->crud_model->compared_num(); ?>" data-placement="right" >

                                <i class="fa fa-exchange"></i>

                                <span class="hidden-md hidden-sm hidden-xs"><?php echo translate('compare'); ?></span>

                                (

                                <span id="compare_num">

                                    <?php echo $this->crud_model->compared_num(); ?>

                                </span>

                                )

                            </a>

                            <a href="#" class="btn btn-theme-transparent" data-toggle="modal" data-target="#popup-cart">

                                <i class="fa fa-shopping-cart"></i> 

                                <span class="hidden-xs"> 

                                    <span class="cart_num"></span> 

                                    <?php echo translate('item(s)'); ?>

                                

                                </span>  

                                <i class="fa fa-angle-down"></i>

                            </a>

                            <!-- Mobile menu toggle button -->

                            <a href="#" class="menu-toggle btn btn-theme-transparent"><i class="fa fa-bars"></i></a>

                            <!-- /Mobile menu toggle button -->

                        </div>

                    </div>

                    <!-- Header shopping cart -->

                </div>

            </div>

        </div>

    </div>

    <!--<div class="navigation-wrapper">

        <div class="container">

            <?php

            	$others_list=$this->uri->segment(3);

			?>

            <nav class="navigation closed clearfix">

                <a href="#" class="menu-toggle-close btn"><i class="fa fa-times"></i></a>

                <ul class="nav sf-menu">

                    <?php if($this->db->get_where('ui_settings',array('type'=>'header_homepage_status'))->row()->value == 'yes'){?>

                    <li <?php if($asset_page=='home'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home">

                            <?php echo translate('homepage');?>

                        </a>

                    </li>

                    <?php } if($this->db->get_where('ui_settings',array('type'=>'header_all_categories_status'))->row()->value == 'yes'){?>

                    <li class="hidden-sm hidden-xs <?php if($asset_page=='all_category'){ echo 'active'; } ?>">

                        <a href="<?php echo base_url(); ?>home/all_category">

							<?php echo translate('all_categories');?>

                        </a>

                        <ul>

                        	<?php

								$all_category = $this->db->get('category')->result_array();

								foreach($all_category as $row)

								{

									if($this->crud_model->if_publishable_category($row['category_id'])){

							?>

                            <li>

                                <a href="<?php echo base_url(); ?>home/category/<?php echo $row['category_id']; ?>">

                                    <?php echo $row['category_name']; ?>

                                </a>

                            </li>

                            <?php

									}

								}

							?>

                        </ul>

                    </li>

                    <?php } ?>

                    <li class="hidden-lg hidden-md <?php if($asset_page=='all_category'){ echo 'active'; } ?>">

                        <a href="#">

							<?php echo translate('all_categories');?>

                        </a>

                        <ul>

                        	<?php

								$all_category = $this->db->get('category')->result_array();

								foreach($all_category as $row)

								{

									if($this->crud_model->if_publishable_category($row['category_id'])){

							?>

                            <li>

                                <a href="<?php echo base_url(); ?>home/category/<?php echo $row['category_id']; ?>">

                                    <?php echo $row['category_name']; ?>

                                </a>

                            </li>

                            <?php

									}

								}

							?>

                        </ul>

                    </li>

                    <li class="hidden-lg hidden-md <?php if($asset_page=='all_category'){ echo 'active'; } ?>">

                        <a href="<?php echo base_url(); ?>home/all_category">

                            <?php echo translate('all_sub_categories');?>

                        </a>

                    </li>

                    <?php if($this->db->get_where('ui_settings',array('type'=>'header_featured_products_status'))->row()->value == 'yes'){?>

                    <li class="<?php if($others_list=='featured'){ echo 'active'; } ?>">

                        <a href="<?php echo base_url(); ?>home/others_product/featured">

                            <?php echo translate('featured_products');?>

                        </a>

                    </li>

                    <?php } if($this->db->get_where('ui_settings',array('type'=>'header_todays_deal_status'))->row()->value == 'yes'){?>

                    <li class="<?php if($others_list=='todays_deal'){ echo 'active'; } ?>">

                        <a href="<?php echo base_url(); ?>home/others_product/todays_deal">

                            <?php echo translate('todays_deal');?>

                        </a>

                    </li>

                    <?php }?>

                    <?php if($this->crud_model->get_type_name_by_id('general_settings','82','value') == 'ok'){

                            if($this->db->get_where('ui_settings',array('type'=>'header_bundled_product_status'))->row()->value == 'yes'){ ?>

                    <li <?php if($page_name=='bundled_product'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/bundled_product">

                            <?php echo translate('bundled_product');?>

                        </a>

                    </li>

                     <?php } }?>

                    <?php if(0){

                            if(1){ ?>

                    <li <?php if($page_name=='customer_product_bulk_upload'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/customer_product_bulk_upload">

                            <?php echo translate('Bulk upload');?>

                        </a>

                    </li>

                    <?php }} if($this->crud_model->get_type_name_by_id('general_settings','83','value') == 'ok'){

                                if($this->db->get_where('ui_settings',array('type'=>'header_classifieds_status'))->row()->value == 'yes'){?>

                    <li <?php if($page_name=='customer_products'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/customer_products">

                            <?php echo translate('classifieds');?>

                        </a>

                    </li>

                    <?php }} if ($this->crud_model->get_type_name_by_id('general_settings','58','value') !== 'ok') {

                                if($this->db->get_where('ui_settings',array('type'=>'header_latest_products_status'))->row()->value == 'yes'){

					?>

                    <li class="<?php if($others_list=='latest'){ echo 'active'; } ?>">

                        <a href="<?php echo base_url(); ?>home/others_product/latest">

                            <?php echo translate('latest_products');?>

                        </a>

                    </li>

                    <?php

						}}

					?>

                    <?php

                    	if ($this->crud_model->get_type_name_by_id('general_settings','68','value') == 'ok') {

                            if($this->db->get_where('ui_settings',array('type'=>'header_all_brands_status'))->row()->value == 'yes') {

					?>

                    <li <?php if($asset_page=='all_brands'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/all_brands">

                            <?php echo translate('all_brands');?>

                        </a>

                    </li>

                    <?php

						}

                    }

					?>

                    <?php

                    	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {

                            if ($this->crud_model->get_type_name_by_id('general_settings','81','value') == 'ok'){

                                if($this->db->get_where('ui_settings',array('type'=>'header_all_vendors_status'))->row()->value == 'yes') {

					?>

                    <li <?php if($asset_page=='all_vendor'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/all_vendor/">

                            <?php echo translate('all_vendors');?>

                        </a>

                    </li>

                    <?php

                                }

						    } 

                        }

					?>

                    <?php if($this->db->get_where('ui_settings',array('type'=>'header_blogs_status'))->row()->value == 'yes') {?>

                    <li class="hidden-sm hidden-xs <?php if($asset_page=='blog'){ echo 'active'; } ?>">

                        <a href="<?php echo base_url(); ?>home/blog">

                            <?php echo translate('blogs');?>

                        </a>

                        <ul>

                        	<?php

								$blogs=$this->db->get('blog_category')->result_array();

								foreach($blogs as $row){

							?>

                            <li>

                                <a href="<?php echo base_url(); ?>home/blog/<?php echo $row['blog_category_id']; ?>">

                                    <?php echo $row['name']; ?>

                                </a>

                            </li>

                            <?php

								}

							?>

                        </ul>

                    </li>

                    <?php }?>

                    <li class="hidden-lg hidden-md <?php if($asset_page=='blog'){ echo 'active'; } ?>">

                        <a href="#">

                            <?php echo translate('blogs');?>

                        </a>

                        <ul>

                        	<?php

								$blogs=$this->db->get('blog_category')->result_array();

								foreach($blogs as $row){

							?>

                            <li>

                                <a href="<?php echo base_url(); ?>home/blog/<?php echo $row['blog_category_id']; ?>">

                                    <?php echo $row['name']; ?>

                                </a>

                            </li>

                            <?php

								}

							?>

                        </ul>

                    </li>

                    <?php

                    	if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok' && $this->crud_model->get_type_name_by_id('general_settings','81','value') == 'ok') {

                            if($this->db->get_where('ui_settings',array('type'=>'header_store_locator_status'))->row()->value == 'yes') {

					?>

                    <li <?php if($asset_page=='store_locator'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/store_locator">

                            <?php echo translate('store_locator');?>

                        </a>

                    </li>

                    <?php

                            }

						}

					?>

                    <?php if($this->db->get_where('ui_settings',array('type'=>'header_contact_status'))->row()->value == 'yes') {?>

                    <li <?php if($asset_page=='contact'){ ?>class="active"<?php } ?>>

                        <a href="<?php echo base_url(); ?>home/contact">

                            <?php echo translate('contact');?>

                        </a>

                    </li>

                    <?php } if($this->db->get_where('ui_settings',array('type'=>'header_more_status'))->row()->value == 'yes') {?>

                    <li>

                        <a href="#">

							<?php echo translate('more');?>

                        </a>

                        <ul>

                            <?php

								if ($this->crud_model->get_type_name_by_id('general_settings','58','value') == 'ok') {

							?>

							<li class="<?php if($others_list=='latest'){ echo 'active'; } ?>">

								<a href="<?php echo base_url(); ?>home/others_product/latest">

									<?php echo translate('latest_products');?>

								</a>

							</li>

							<?php

								}

							?>

                            <?php

							$this->db->where('status','ok');

                            $all_page = $this->db->get('page')->result_array();

							foreach($all_page as $row2){

							?>

                            <li>

                                <a href="<?php echo base_url(); ?>home/page/<?php echo $row2['parmalink']; ?>">

                                    <?php echo $row2['page_name']; ?>

                                </a>

                            </li>

                            <?php

							}

							?>

                        </ul>

                    </li>

                    <?php }?>

                </ul>

            </nav>
        </div>

    </div>-->
<section class="category_header">

    <div class="container">

        <div class="row">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu"><i class="fa fa-bars"></i></button>
          <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="col-lg-12 col-md-12 col-sm-12 menu_bar">

                <nav class="navbar_items">

                  <div class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">

                    <i class="fa fa-close"></i>

                  </div>

                    <ul class="nav navbar-nav-items">

                        <li class="active"><a href="index.html">Home</a></li>
                        <li><a href="#">Browse</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="https://marketplace.hypeplug.paris/home/about">About</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="https://marketplace.hypeplug.paris/home/vendor_logup/registration">Become A Vendor</a></li>
                        <!--<div class="drop1down">

                          <a class="drop1btn">Sneaker Resources<i class="fa fa-angle-down" aria-hidden="true"></i></a>

                          <div class="drop1down-content">

                            <a href="#">Retail Sneaker Raffles</a>

                            <a href="#">*HypePlug Membership*</a>

                            <div class="drop1down1">

                              <a class="drop1btn1">Nike Shoe Size Conversion<i class="fa fa-angle-right" aria-hidden="true"></i></a>

                              <div class="drop1down1-content">

                                <a href="#">Nike Men’s Shoe Size Chart</a>

                                <a href="#">Nike Women’s Shoe Size Chart</a>

                                

                              </div>

                            </div>

                            <div class="drop1down11">

                              <a class="drop1btn11">Adidas Shoe Size Conversion<i class="fa fa-angle-right" aria-hidden="true"></i></a>

                              <div class="drop1down11-content">

                                <a href="#">Adidas Men’s Shoe Size Chart</a>

                                <a href="#">Adidas Women’s Shoe Size Chart</a>

                                

                              </div>

                            </div>

                            

                          </div>

                        </div>-->

                    

                      
                        

                    </ul>

                </nav>

            </div>
          </div>
        </div>

    </div>
</section>
</header>

<!-- /HEADER -->

<script type="text/javascript">

    $(document).ready(function(){

        $('.set_langs').on('click',function(){

            var lang_url = $(this).data('href');                                    

            $.ajax({url: lang_url, success: function(result){

                location.reload();

            }});

        });

        $('.top-bar-right').load('<?php echo base_url(); ?>home/top_bar_right');

    });

</script>

<?php

if ($this->crud_model->get_type_name_by_id('general_settings','58','value') !== 'ok') {

?>

<style>

.header.header-logo-left .header-search .header-search-select .dropdown-toggle {

    right: 40px !important;

}

</style>

<?php

}

?>