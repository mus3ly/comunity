<style>
    #sidebar ul li a{
        color:#000;
    }
</style>
<aside class="col-md-2 sidebar new_sidebar" id="sidebar">
    <!-- widget shop categories -->
    <!-- widget price filter -->
               <?php
            if(isset($is_listing) && ($is_listing == 'shop_listing' || $is_listing == 'car_listing')){
                    ?>
            <div class="widget widget-filter-price">
                <div class="Amenities1">
                    <div class="left_filter to_left_f">
                     <h3>Filter Your Search</h3>
                </div>
                 
                 <div class="push_left">
           <!-- <span class="btn btn-theme-transparent pull-left hidden-lg hidden-md" onClick="open_sidebar();">
                <i class="fa fa-bars"></i>
            </span>-->
            <a class="btn-theme-light make_it_flexi" onClick="set_view('grid')" href="#"><img src="<?php echo base_url(); ?>/white_grid.png" alt=""/></a>
            <a class="btn btn-theme-light make_it_flexi" onClick="set_view('list')" href="#"><img src="<?php echo base_url(); ?>/white_icon.png" alt=""/></a>
        </div>
                </div>
                <div class="range_slider new_rang">
                    <div class="row">
                        <div class="col-sm-12">
                          <div id="slider-range"></div>
                        </div>
                    </div>
                    <div class="row slider-labels">
                        <div class="col-xs-6 p-0 caption">
                          <strong>Min:</strong> <span id="slider-range-value1"></span>
                        </div>
                        <div class="col-xs-6 p-0 text-right caption">
                          <strong>Max:</strong> <span id="slider-range-value2"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            }
            else
            {
                ?>
            <div class="widget widget-filter-price">
                <div class="Amenities1">
                 <div class="left_filter to_left_f">
                     <h3>Search Filter</h3>
                </div>
                   <div class="push_left">
           <!-- <span class="btn btn-theme-transparent pull-left hidden-lg hidden-md" onClick="open_sidebar();">
                <i class="fa fa-bars"></i>
            </span>-->
            <a class="btn-theme-light make_it_flexi" onClick="set_view('grid')" href="#"><img src="<?php echo base_url(); ?>/white_grid.png" alt=""/></a>
            <a class="btn btn-theme-light make_it_flexi" onClick="set_view('list')" href="#"><img src="<?php echo base_url(); ?>/white_icon.png" alt=""/></a>
        </div>
                </div>
                <div class="new_title_1">
                    <h3>Distance Range</h3>
                </div>
                <div class="range_slider new_rang">
                    <div class="row">
                        <div class="col-sm-12">
                          <div id="slider-range"></div>
                        </div>
                    </div>
                    <div class="row slider-labels">
                        <div class="col-xs-6 caption">
                          <strong>Min:</strong> <span id="slider-range-value1"></span>
                        </div>
                        <div class="col-xs-6 text-right caption">
                          <strong>Max:</strong> <span id="slider-range-value2"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
    <?php
    
            if(isset($is_listing) && $is_listing == 'car_listing'){
                $all_makes =  $this->db->get('makes')->result_array();

                    ?>
                    <select name="select_make" onchange="do_product_search('0')" id="select_make" class="form-control">
                        <option value="0">Make</option>
                        <?php
                        foreach($all_makes as $k => $v){
                        ?>
                        <option value="<?= $v['id']?>"><?= $v['name']?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="from_group">
                        <!--<label>Model from</label>-->
                        <input id="modelf_input" class="form-control"  onchange="do_product_search('0')" type="text" placeholder="Model From" />
                    </div>
                    <div class="from_group1">
                        <!--<label>Model to</label>-->
                        <input id="modelt_input" class="form-control" onchange="do_product_search('0')" type="text" placeholder="Model To" />
                    </div>
                    <div class="from_group1">
                        <!--<label>No of seats</label>-->
                    <input type="number" id="seats_input" onkeyup="do_product_search('0')" placeholder="No: of Seats" name="seats"  class="form-control"> 
                    </div>
                    <?php
                }
                ?>
                <?php
            if(isset($is_listing) && $is_listing == 'property_listing'){
                    ?>
                    
                 
                    <input type="number" placeholder="No: of Bedrooms" onkeyup="do_product_search('0')" id="bedrooms_input"  class="margin_added form-control"> 
                       <select name="select_property_type" onchange="do_product_search('0')" id="select_property_type" class="margin_added form-control">
                       <option value="" >Type</option>
                        <option value="detached">Detached </option>
                        <option value="apartment ">Apartment </option>
                        <option value="house">House</option>
                        <option value="rent">Rent</option>
                        <option value="sale">Sale</option>
                        <option value="furnished">Furnished</option>
                        <option value="unfurnished">Unfurnished</option>
                       
                    </select>
                    <?php
                }
                ?>
                <?php
            if(isset($is_listing) && $is_listing == 'jobs_listing'){
                    ?>
                    
                 
                        <select name="select_job_hours" onchange="do_product_search('0')"  id="select_job_hours" class="margin_added form-control">
                                <option value="">select job hours</option>
                                <option value="fulltime">Full Time</option>
                                <option value="parttime ">Part Time</option>
                                <option value="rotation ">Rotation</option>
                                <option value="two_years ">Two Years</option>
                               
                            </select>
                      <select name="select_job_type" onchange="do_product_search('0')"  id="select_job_type" class="margin_added form-control">
                                    <option value="">select job type</option>
                                    <option value="paermanent">Permanent</option>
                                    <option value="temporary">Temporary</option>
                                    <option value="contract">Contract</option>
                                    <option value="volunteer">Volunteer</option>
                                    <option value="apprenticeship ">Apprenticeship</option>
                                    <option value="internship ">Internship</option>
                                   
                                </select>
                    <?php
                }
                ?>       <?php
            if(isset($is_listing) && $is_listing == 'event_listing'){
                    ?>
                    
                        <input type="date" id="event_date_input" onchange="do_product_search('0')" class="add_padding form-control">
                        <select id="event_type_input"class="form-control add_padding" onchange=" do_product_search('0')">
                            <option value="">Event type</option>
                            <option value="wedding">Wedding</option>
                            <option value="festival">Festival</option>
                            <option value="concert">Concert</option>
                            <option value="party">Party</option>
                            <option value="get_to_gether">Get To Gether</option>
                            <option value="music">Music</option>
                        </select>
                       <select id="age_restriction_input" class="form-control add_padding" onchange=" do_product_search('0')">
                                   <option value="">Age Restrictions</option>
                                    <option value="family">Family Friendly</option>
                                    <option value="kids">Kids Friendly</option>
                                    <option value="18+">18+</option>
                                    <option value="12+">12+</option>
                                    <option value="all_ages">All Ages</option>
                                   
                                </select>
                     
                    <?php
                }
                ?>
    <!-- /widget price filter -->
    <span class="btn btn-theme-transparent pull-left hidden-lg hidden-md" onClick="close_sidebar();" style="border-radius:50%; position: absolute; top:0;right:10px;color:white;">
        <i class="fa fa-times"></i>
    </span>
    <div class="widget shop-categories">
        <div class="widget-content">
             <h3 class="title-for-list" >
                    <span class="arrow search_cat search_cat_click all_category_set" style="display:none;" data-cat="0" 
                        data-min="<?php echo floor($this->crud_model->get_range_lvl('product_id !=', '0', "min")); ?>" 
                           data-max="<?php echo ceil($this->crud_model->get_range_lvl('product_id !=', '0', "max")); ?>" 
                            data-brands="<?php echo $this->db->get_where('general_settings',array('type'=>'data_all_brands'))->row()->value; ?>"
                                data-vendors="<?php echo $this->db->get_where('general_settings',array('type'=>'data_all_vendors'))->row()->value; ?>"
                           >
                                    <i class="fa fa-angle-down"></i>
                    </span>
                    <a href="#" class="search_cat" style="color:white;" data-cat="0"
                        data-min="<?php echo floor($this->crud_model->get_range_lvl('product_id !=', '0', "min")); ?>" 
                           data-max="<?php echo ceil($this->crud_model->get_range_lvl('product_id !=', '0', "max")); ?>" >
                        <?php echo translate('all_listings');?> 
                    </a>
                </h3>      
            <ul style="height:432px;overflow-y:auto;">   
                                                          
                <?php
                $all_category = '' ; 
                if(isset($is_listing) && $is_listing == 'shop_listing'){
                        $all_category =json_decode($this->db->get_where('ui_settings',array('ui_settings_id' => 87))->row()->value,true);
                    
                        foreach($all_category as $k=> $v)
                        {
                            $sing = $this->db->where('category_id',$v)->get('category')->row_array();
                            $all_category[$k] = $sing;
                        }
                        $result=array();
                        
                   }
                else{
                    
                    // $all_category = $this->db->get('category')->result_array();
                    
                    // NIMRA HERE
                      $categories =json_decode($this->db->get_where('ui_settings',array('ui_settings_id' => 35))->row()->value, true);
                       $result=array();
                                            foreach($categories as $row){
                                                if($this->crud_model->if_publishable_category($row)){
                                                    $result[]=$row;
                                                }
                                            }
                    // var_dump($result);
                    // die();
                   $all_category =  $this->db->where_in('category_id',$result)->get('category')->result_array();

                    // NIMRA HERE
                    
                }
                    foreach($all_category as $key => $row)
                    {
						if($row['category_id'] && $row['category_name']){
                ?>
                <li>
                    
                    <a href="<?= base_url('/directory'); ?>/<?= $row['slug'] ?>" for="cat_<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?>   <i  class="fa fa-angle-down angle_rightdown"></i></a>
                    <!--<i  class="fa fa-angle-down angle_rightdown"></i>-->
                    <div class="cat_result" id="cat_r<?php echo $row['category_id']; ?>"></div>
                    
                    
                </li>
                <?php  
						}
                    }
                ?>
            </ul>
        </div>
    </div>
    <!-- /widget shop categories -->
    
    <br>
    <div class="row hidden-sm hidden-xs">
    <?php
		//echo $this->html_÷model->widget('special_products');
	?>
    </div>
    
    <?php
    $id = '0';
      if(isset($_GET['is_listing']) && $_GET['is_listing'] == 'car_listing'){
      $id = '807';
      }
      if(isset($_GET['is_listing']) && $_GET['is_listing'] == 'property_listing'){
          $id = '808';
      }
      if(isset($_GET['is_listing']) && $_GET['is_listing'] == 'event_listing'){
           $id = '917';
      }
      if(isset($_GET['is_listing']) && $_GET['is_listing'] == 'jobs_listing'){
           $id = '78';
      }
    
     $all_amenity = $this->db->where('status', 1)->where('catid',$id)->get('amenity')->result_array();
    ?>
    <?php
    if(!empty($all_amenity)){
    ?>
    <div class="Amenities">
        <h3>Amenities</h3>
        <ul>
            <?php
            foreach($all_amenity as $k=> $v)
            {
                ?>
                <li><input type="checkbox" value="<?= $v['name'] ?>" class="amenities_check" /><label><?= $v['name']; ?></label></li>
                <?php
            }
            ?>
            <!--<li><label><input type="checkbox" /> Maintenance Staff</label></li>-->
            <!--<li><label><input type="checkbox" /> Security Staff</label></li>-->
            <!--<li><label><input type="checkbox" /> Cleaning</label></li>-->
            <!--<li><label><input type="checkbox" /> Internet Connection</label></li>-->
            <!--<li><label><input type="checkbox" /> Amenities</label></li>-->
            <!--<li><label><input type="checkbox" /> Maintenance Staff</label></li>-->
            <!--<li><label><input type="checkbox" /> Security Staff</label></li>-->
            <!--<li><label><input type="checkbox" /> Cleaning</label></li>-->
            <!--<li><label><input type="checkbox" /> Internet Connection</label></li>-->
            
        </ul>
    </div>
    <?php
    }
    ?>

</aside>

<input type="hidden" id="univ_max" value="<?php echo $this->crud_model->get_range_lvl('product_id !=', '', "max"); ?>">
<input type="hidden" id="cur_cat" value="0">
<?php include 'search_script.php'; ?>
