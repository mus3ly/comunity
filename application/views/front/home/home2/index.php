
<style>
    .scroll::-webkit-scrollbar {
            display: none;
        }
        .left_fields {
        padding: 9px !important;
    }
    .dec_wrappper {
    padding: 9px !important;
}
 .blogs_titlee{
    padding: 8px 7px;
}
.meddle_cont {
    padding: 0 10px 0 8px;
}
.verifed_listings  .desc{
    margin: 0px 4px 19px !important;
    font-size: 15px !important;
    padding: 7px 0px 10px;
    line-height: 20px;
    height: 70px;
    display: -webkit-box;
    max-width: 400px;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #333;
}
.dec_wrappper > p {
        margin: 0px 4px 19px !important;
        font-size: 15px !important;
        padding: 7px 8px 10px;
        line-height: 20px;
        height: 70px;
        display: -webkit-box;
        max-width: 400px;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #333;
    }
</style>

<body id="page-name">

<div class="main_warp">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-4 graphic_img">

<?php
                                            $top_banner     =  $this->db->get_where('ui_settings',array('ui_settings_id' => '62'))->row();
                                            if($top_banner)
                                            {
                                             $img = $this->crud_model->get_img($top_banner->value)->webp_url;
                                         }

                                        ?>
                <img src="<?= $img ?>" width="343px" height="198px" alt="">
            </div>
            <div class="col-sm-8 perfect_place">
                <h5>Community HubLand - A Directory Site - Your Virtual Land</h5>
                <h3>Search Your <b>Communities</b></h3>
                <div class="search_bar border_iner">
                <form action="<?= base_url('/home/text_search'); ?>"  onkeyup="submitForm(event)" id="srch_form" method="post">
                    <img style="width:22px;" src="<?= base_url('/search_icon_bar.png'); ?>" width="22px" height="22px" alt="Search">
                    <input type="text" placeholder="TACOS, CHEAP DINNER, MAX’S" id="left_box"  name="query" alt="" style="border-right: 1px solid #f26122 !important;border-radius:0;" id="texted">
                    <img src="<?= base_url(); ?>template/front/images/Location.webp" width="18px" height="21px" alt="Search">
                     <input type="text" id="loc_box"  onkeyup="search_location()" placeholder="LOCATION"  name="" alt="" >
                     <div id="map_search" style="
    z-index: 9999999999999999;
    position: absolute;
">
                         <img id="loader" style="display:none" src="<?= base_url('/map-loader.gif'); ?>" />
                         <div id="result"></div>
                     </div>
                     <input type="hidden" id="place_id" name="place_id" />
                    <button type="submit">Search</button>

    <div id="map"></div>
                </form>
                </div>


                <div id="small-categories" class="owl-carousel owl-carousel-icons owl-loaded owl-drag">
                  <div class="owl-stage-outer">
                     <div class="owl-stage" style="transform: translate3d(-3002px, 0px, 0px); transition: all 0.25s ease 0s; width: 4804px;">

                        <?php
                        // die('come');
                        $brands = $this->db->get('category')->result_array();
                $categories =json_decode($this->db->get_where('ui_settings',array('ui_settings_id' => 35))->row()->value,true);
                                            $result=array();
                                            foreach($categories as $row){
                                                if($this->crud_model->if_publishable_category($row)){
                                                    $result[]=$row;
                                                }
                                            }

                    foreach ($brands as $key => $value) {
                        $fa_icons = $value['fa_icon'] == 'fa-thin fa-house-building' ? 'fa-building' : $value['fa_icon'];
                        $fa_icons = $fa_icons == 'fa-shirt-long-sleeve' ? 'fa-shirt' : $fa_icons;
                        $fa_icons = ($fa_icons)?$fa_icons:'fa-file-image-o';
                        if(in_array($value['category_id'], $result))
                        {
                            //  echo $value['category_id'];
                        ?>
                            <div class="owl-item " >
                           <div class="item">
                              <div class="slider_box_icons">
                                <ul>
                                    <li ><a href="<?= base_url('directory/'.$value['slug']); ?>"><i class="fas <?= $fa_icons; ?>"></i>  <?= $value['category_name'] ?></a></li>
                                </ul>
                            </div>
                           </div>
                        </div>
                        <?php
                        }
                    }
                ?>



                     </div>
                  </div>
                  <div class="owl-nav">
                     <button type="button" role="presentation" aria-label="left arrow" class="owl-prev"><i class="fa fa-angle-left"></i></button>
                     <button type="button" role="presentation" aria-label="right arrow" class="owl-next"><i class="fa fa-angle-right"></i> </button>
                  </div>
                  <div class="owl-dots disabled"></div>
               </div>



            </div>
        </div>
    </div>
</div>
<div class="right_dotted">
    <img src="<?= base_url(); ?>template/front/images/doted-lines-right.webp" alt="">
</div>


<div class="video_warp">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 business_graphic">
                <video autoplay="" loop="" muted="">
                <source src="https://gold-blu.gamedayspuds.com/wp-content/uploads/2022/12/glam-product.mp4" type="video/mp4">
            </video>
            </div>
            <div class="col-sm-6 communitybox every_business">

                <h3>Sign-up and own a business page in less than 5 minutes</h3>
                    <div class="scroll">
                    <p>Use your business page as your community market page:</p>

                    <ul>
                        <?php
                        $points = array( 'Post Advertisements', 'Own A Commercial Business Page', 'Benefit From Affiliate Marketing' );
                        $html = '';
                        foreach( $points as $point ) {
                            $html .= '<li><img src="https://markethubland.com/template/front/images/Tick-Square.webp" width="24px" height="25px" alt="">' . $point . '</li>';
                        }
                        echo $html;
                        ?>
                    </ul>
                    <p>
                        Become Community HubLand pioneer now at an incredibly low subscription fee!
                        <a href="" class="btn_anim from-top btn_simple">JOIN NOW</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<?php/*?> <div class="list_business">
    <div class="container">
        <div class="plus_dot">
            <div class="right_plus">
                <img src="<?= base_url(); ?>template/front/images/plus-gray.png" alt="">
            </div>
            <h4><?php echo $this->crud_model->get_type_name_by_id('ui_settings','63','value'); ?></h4>
            <p><?php echo $this->crud_model->get_type_name_by_id('ui_settings','64','value'); ?></p>
            <div class="orange_plus">
                <img src="<?= base_url(); ?>template/front/images/orange-plus.png" alt="">
            </div>
        </div>
    </div>
</div> <?php */?>


<div class="icon_box_wrap">
    <div class="container">

      <!--  <div class="row">

        <?php
                $cboxes = unserialize($this->crud_model->get_type_name_by_id('ui_settings','65','value'));
                                    $boxes = 3;
                                    if($cboxes)
                                    {

                                        $boxes = count($cboxes);
                                    }

                                    for ($i=0; $i < $boxes; $i++) {
                                        ?>
            <div class="col-sm-4 sidegapp">
                <div class="info_box_shadow">
                    <div class="shadow_icon">
                    <i class="fa <?= (isset($cboxes[$i]['icon'])?$cboxes[$i]['icon']:''); ?>" aria-hidden="true"></i>

                    </div>
                    <b><?= (isset($cboxes[$i]['heading'])?$cboxes[$i]['heading']:''); ?></b>
                    <ul>
                        <?php
                        $ex = explode(',',$cboxes[$i]['detail']);
                        if(count($ex) > 1)
                        {
                            foreach($ex as $k=> $v)
                            {
                            ?>
                            <li><?= $v; ?></li>
                            <?php
                            }
                        }
                        else
                        {
                            echo $cboxes[$i]['detail'];
                        }
                        ?>
                    </ul>
                    <div class="bottom_path active_path">
                        <img src="<?= base_url(); ?>template/front/images/rectangle.png" alt="">
                    </div>
                </div>
            </div>

            <?php

                                    }
            ?>

        </div>-->


        </div>
    </div>
</div>

<div class="community_wrap">
    <div class="container">
        <div class="clipart">
            <img src="<?= base_url(); ?>template/front/images/business_graphic-clipart.webp" width="79px" height="63px" alt="">
        </div>
        <div class="row">
            <div class="col-sm-7 communitybox every_business">

                <h3><?= $this->crud_model->get_type_name_by_id('ui_settings','69','value'); ?></h3>
                <div class="scroll">
                <p><?= $this->crud_model->get_type_name_by_id('ui_settings','68','value'); ?></p>

                <ul>
                    <?php
                    $bullets =  $this->crud_model->get_type_name_by_id('ui_settings','73','value');
                    $bullets = explode(',',$bullets);
                    foreach($bullets as $k=> $v)
                    {
                        ?>
                        <li><img src="<?= base_url(); ?>template/front/images/Tick-Square.webp" width="24px" height="25px" alt=""><?= $v ?></li>
                        <?php
                    }
                    ?>
                </ul>
                <b>Price?</b>
                <h5>Less than a the cost of a breakfast a month</h5>
            </div>
            </div>
            <div class="col-sm-5 business_graphic">
                <?php
                                    $img = '';
                                            $top_banner     =  $this->db->get_where('ui_settings',array('ui_settings_id' => '67'))->row();
                                            if($top_banner)
                                            {
                                             $img = $this->crud_model->get_img($top_banner->value)->webp_url;
                                         }

                                        ?>
                <img src="<?= $img ?>"  width="512px" height="529px" alt="">
                <div class="circle_clipart">
                    <img src="<?= base_url(); ?>template/front/images/circle-clipart.webp" width="96px" height="223px" alt="">
                </div>
            </div>
        </div>
        <div class="dotted_lines_clipart">
            <img src="<?= base_url(); ?>template/front/images/dotted_lines_clipart.webp" width="120px" height="106px" alt="">
        </div>
    </div>
</div>

<div class="orange_card">
    <div class="container">
        <div class="orange_card_box">
            <div class="full_circle">
                <img src="<?= base_url(); ?>template/front/images/business-card-right.webp" alt="">
            </div>
            <p><?= $this->crud_model->get_type_name_by_id('ui_settings','74','value'); ?></p>
            <h4><?= $this->crud_model->get_type_name_by_id('ui_settings','75','value'); ?></span></h4>
            <p class="hire_para"><?= $this->crud_model->get_type_name_by_id('ui_settings','76','value'); ?></p>
            <div class="row">
                <?php
                    $bullets =  $this->crud_model->get_type_name_by_id('ui_settings','77','value');
                    $bullets = explode(',',$bullets);

                    foreach($bullets as $k=> $v)
                    {
                        $exp = explode(':',$v);
                        ?>
                        <div class="col-sm-6 checkbox_tick">
                    <img src="<?= base_url(); ?>template/front/images/Tick-Square.webp" width="24px" height="25px" alt="">
                    <h4><?= (isset($exp[0])?$exp[0]:''); ?></h4>
                    <p><?= (isset($exp[1])?$exp[1]:''); ?></p>
                </div>
                        <?php
                    }
                    ?>
                <div class="col-sm-12 checkbox_tick mt-4">
                    <img src="<?= base_url(); ?>template/front/images/Tick-Square.webp" width="24px" height="25px" alt="">
                    <h4>CALL CENTRE SERVICES</h4>
                    <p>A myriad of low cost, highly efficient customer services from technical to telemarketing and help desk support</p>
                </div>
            <div class="learn_more_btns">
                <?php
                $btns  = json_decode($this->db->get_where('ui_settings',array('ui_settings_id' => '78'))->row()->value,true);
                // var_dump($btns);
                $i = 0;
                foreach($btns as $k => $v){
                    if($i % 2 == 0)
                    {
                ?>
                <a href="<?= $v['url']; ?>" class="our_projects"><?= $v['txt'];?></a>
                <?php
                }
                else
                {
                    ?>
                <a href="<?= $v['url']; ?>" ><?= $v['txt'];?></a>

                    <?php
                }
                }
                ?>
            </div>
            <div class="bottom_circled">
                <img src="<?= base_url(); ?>template/front/images/bottom-circled.webp" alt="">
            </div>
        </div>
    </div>
</div>


    <div class="advertise_wrap">
        <div class="purple_line">
            <img src="<?= base_url(); ?>template/front/images/base-icon.png" alt="">
        </div>
        <div class="container">
            <div class="row" id="advertise_info">
                <div class="col-sm-4 business_graphic">
                    <?php
                    $img = '';
                    $top_banner = $this->db->get_where('ui_settings', array('ui_settings_id' => '85'))->row();
                    if ($top_banner) {
                        $img = $this->crud_model->get_img($top_banner->value)->secure_url;
                    }

                    ?>
                    <img src="<?= $img ?>" alt="">
                    <div class="purple_dot" style="top: auto;bottom: -61px;">
                        <img src="<?= base_url(); ?>template/front/images/purple.png" alt="">
                    </div>
                </div>
                <div class="col-sm-8 communitybox">
                    <b><?= $this->crud_model->get_type_name_by_id('ui_settings', '79', 'value'); ?></b>
                    <h3><?= $this->crud_model->get_type_name_by_id('ui_settings', '80', 'value'); ?></h3>
                    <div class="scroll" style="display:inline-block">
                        <p><?= $this->crud_model->get_type_name_by_id('ui_settings', '82', 'value'); ?></p>
                        <div class="row ">
                            <?php
                            $bullets = $this->crud_model->get_type_name_by_id('ui_settings', '81', 'value');
                            $bullets = explode(',', $bullets);
                            foreach ($bullets as $k => $v) {
                                $exp = explode(':', $v);
                                ?>
                                <div class="<?php echo ($k == count($bullets) - 1) ? 'col-sm-12' : 'col-sm-6'; ?> checkbox_tick">
                                    <img src="<?= base_url(); ?>template/front/images/Tick-Square.png" alt="">
                                    <h4 style="color:black;"><?= (isset($exp[0]) ? $exp[0] : ''); ?></h4>
                                    <p style="color:black;"><?= (isset($exp[1]) ? $exp[1] : ''); ?></p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="learn_more_btns" align="right">
                        <a href="<?= $this->crud_model->get_type_name_by_id('ui_settings', '84', 'value'); ?>"
                           class="our_projects"><?= $this->crud_model->get_type_name_by_id('ui_settings', '83', 'value'); ?></a>
                        <!--<div class="purple_dot">-->
                        <!--    <img src="<?= base_url(); ?>template/front/images/purple.png" alt="">-->
                        <!--</div>-->
                    </div>

                </div>

            </div>
        </div>
        <div class="upper_line_dot">
            <img src="<?= base_url(); ?>template/front/images/doted-lines-right.png" alt="">
        </div>
    </div>
<?php
include "featured_products.php";
?>
<div class="container">
  <div class="joinbtnbox">
    <a href="<?= base_url(); ?>vendor">Join Community HubLand Affiliate Marketing</a>
  </div>
</div>

<script src="<?= base_url('/'); ?>template/front/js-files/jquery-3.2.1.min.js"></script>
<script src="<?= base_url('/'); ?>template/front/js-files/owl.carousel.js"></script>
<script src="<?= base_url('/'); ?>template/front/js-files/custom.js"></script>
<script src="<?= base_url('/'); ?>template/front/js-files/additional-script.js"></script>
          <script type="text/javascript">
              (function($) {

    /*---Owl-carousel----*/

    // ___Owl-carousel-icons
    var owl = $('.owl-carousel-icons');
    owl.owlCarousel({
        loop: true,
        rewind: false,
        margin: 0,
        animateIn: 'fadeInDowm',
        animateOut: 'fadeOutDown',
        autoplay: false,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        dots: false,
        nav: true,
        autoplay: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 2,
                nav: true
            },
            1250: {
                items: 8,
                nav: true
            }
        }
    })
 // ___Owl-carousel-icons

})(jQuery);
function search_location()
{

    var str = $('#loc_box').val();
    //
    if(str.length >= 2 )
    {
        $('#map_search #loader').show();
        $('#map_search #result').hide();
        $.ajax({
        url: "<?= base_url('home/srch_loc'); ?>?str="+str,
        type: 'GET',
        // dataType: 'json', // added data type
        success: function(res) {
            $('#map_search #loader').hide();
            $('#map_search #result').show();
            $('#map_search #result').html(res);
            // alert(res);
        }
    });

    }
    else
    {
        $('#map_search #result').hide();
    }
}
function select_place(place,txt)
{
    $('#loc_box').val(txt);
    $('#place_id').val(place);
    $('#map_search #result').hide();

}
          </script>


