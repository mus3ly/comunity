<style>
   
    .active2{
        background:#f36022 !important;
        padding:10px !important;
        color:white !important;
        
    }
    .tab_buttons{
        background:#ddd;
        padding:10px;
        color:black;
    }
</style>
<?php
    foreach($user_info as $row)
    {
?>
    <div class="information-title">
        <?php echo translate('profile_information');?>
    </div>
    
    <div class="details-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="tabs-wrapper content-tabs">
                    <ul class="nav nav-tabs new_tab">
                        <li class="active">
                <a href="#tab1" id="tab1_btn" class="tab_buttons active2" data-toggle="tab">
                                <?php echo translate('personal_info');?>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2" class="tab_buttons" id="tab2_btn" data-toggle="tab">
                                <?php echo translate('change_password');?>
                            </a>
                        </li>
                        <li>
                            <a href="#tab3" id="tab3_btn" class="tab_buttons" data-toggle="tab">
                                <?php echo translate('Account information');?>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane  in active" id="tab1">
                             <div class="details-wrap">
                                <div class="block-title alt"> 
                                    <i class="fa fa-angle-down"></i> 
                                    <?php echo translate('change_your_profile_information');?>
                                </div>
                                <div class="details-box">
                                    <?php
                                        echo form_open(base_url() . 'home/registration/update_info/', array(
                                            'class' => 'form-login',
                                            'method' => 'post',
                                            'enctype' => 'multipart/form-data'
                                        ));
                                    ?>    
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="username" value="<?php echo $row['username']; ?>" type="text" placeholder="<?php echo translate('first_name');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="surname" value="<?php echo $row['surname']; ?>" type="text" placeholder="<?php echo translate('last_name');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="form-control" name="email" value="<?php echo $row['email']; ?>" type="email" placeholder="<?php echo translate('email');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="form-control" name="address1" value="<?php echo $row['address1']; ?>" type="text" placeholder="<?php echo translate('address 1');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="form-control" name="address2" value="<?php echo $row['address2']; ?>" type="text" placeholder="<?php echo translate('address 2');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="phone" value="<?php echo $row['phone']; ?>" type="tel" placeholder="<?php echo translate('phone');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input class="form-control" name="city" value="<?php echo $row['city']; ?>" type="text" placeholder="<?php echo translate('city');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input class="form-control" name="state" value="<?php echo $row['state']; ?>" type="text" placeholder="<?php echo translate('state');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input class="form-control" name="country" value="<?php echo $row['country']; ?>" type="text" placeholder="<?php echo translate('country');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="zip" value="<?php echo $row['zip']; ?>" type="text" placeholder="<?php echo translate('ZIP');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="skype" value="<?php echo $row['skype']; ?>" type="text" placeholder="<?php echo translate('skype');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="google_plus" value="<?php echo $row['google_plus']; ?>" type="text" placeholder="<?php echo translate('google_plus');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="facebook" value="<?php echo $row['facebook']; ?>" type="text" placeholder="<?php echo translate('facebook');?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <span class="btn btn-theme pull-right signup_btn btn btn-success" data-unsuccessful='<?php echo translate('info_update_unsuccessful!'); ?>' data-success='<?php echo translate('info_updated_successfully!'); ?>' data-ing='<?php echo translate('updating..') ?>' >
                                                    <?php echo translate('update');?>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>   
                        </div>
                        <div class="tab-pane " id="tab2">
                            <div class="details-wrap">
                                <div class="block-title alt"> <i class="fa fa-angle-down"></i> <?php echo translate('change_your_password');?></div>
                                <div class="details-box">
                                    <?php
                                        echo form_open(base_url() . 'home/registration/update_password/', array(
                                            'class' => 'form-delivery',
                                            'method' => 'post',
                                            'enctype' => 'multipart/form-data'
                                        ));
                                    ?> 
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-group"><input required name="password" type="password" placeholder="<?php echo translate('old_password');?>" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group"><input required name="password1" type="password" placeholder="<?php echo translate('new_password');?>" class="form-control"></div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group"><input required name="password2" type="password" placeholder="<?php echo translate('confirm_new_password');?>" class="form-control"></div>
                                            </div>
                                            <div class="col-md-12 col-sm-12">
                                                <span class="btn btn-theme pull-right signup_btn btn btn-success" data-unsuccessful='<?php echo translate('password_change_unsuccessful!'); ?>' data-success='<?php echo translate('password_changed_successfully!'); ?>' data-ing='<?php echo translate('updating..') ?>' >
                                                    <?php echo translate('update');?> 
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="tab-pane " id="tab3">
                            <div class="details-wrap">
                                <div class="block-title alt"> <i class="fa fa-angle-down"></i> <?php echo translate('change_your_payment details');?></div>
                                <div class="details-box">
                                    <?php
                                        echo form_open(base_url() . 'home/registration/update_payment/', array(
                                            'class' => 'form-delivery',
                                            'method' => 'post',
                                            'enctype' => 'multipart/form-data'
                                        ));
                                    ?> 
                                        <div class="row">
                                              <div class="form-group">
                                        <select class="form-select" id="changer" name="payment_methode" aria-label="Default select example">
                                          <option >Choose Payment Methode</option>
                                          <option value="paypal" <?= ($row['payment_methode'] == 'paypal')?'selected':'' ?>>paypal</option>
                                          <option value="Payswitch" <?= ($row['payment_methode'] == 'Payswitch')?'selected':'' ?>>Payswitch</option>
                                         
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <input required name="payment_id"id="add_here" type="text" value="<?= $row['payment_id'] ?>" placeholder="Choose Methode" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <span class="btn btn-theme pull-right signup_btn btn btn-success" data-unsuccessful='<?php echo translate('info_update_unsuccessful!'); ?>' data-success='<?php echo translate('info_updated_successfully!'); ?>' data-ing='<?php echo translate('updating..') ?>' >
                                                    <?php echo translate('update');?>
                                                </span>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>  
<?php
    }
?>
<script>
    $('document').ready(function(){
        $('#tab1_btn').click(function(){
           $('#tab2').hide(); 
           $('#tab1').show(); 
           $( "#tab1_btn" ).addClass( 'active2');
           $( "#tab2_btn" ).removeClass( 'active2');
            $( "#tab3_btn" ).removeClass( 'active2');
           $('#tab3').hide();
        });
        $('#tab2_btn').click(function(){
           $('#tab1').hide(); 
           $('#tab2').show(); 
           $('#tab3').hide();
            $( "#tab2_btn" ).addClass( 'active2');
            $( "#tab1_btn" ).removeClass( 'active2');
            $( "#tab3_btn" ).removeClass( 'active2');
        }); 
        $('#tab3_btn').click(function(){
           $('#tab1').hide(); 
           $('#tab2').hide(); 
         $( "#tab3_btn" ).addClass( 'active2');
         $( "#tab1_btn" ).removeClass( 'active2');
            $( "#tab2_btn" ).removeClass( 'active2');
           $('#tab3').show(); 
        });
    });
</script>
<script>
    $('document').ready(function(){
       $('#changer').on("change",function(){
          var paypalText = 'Add paypal Email';
          var PayswitchText = 'Add Payswitch ID';
          
          var my = $('#changer').val(); 
          if(my == 'paypal'){
              $('#add_here').attr('placeholder',paypalText)
          }else if(my = 'Payswitch'){
                $('#add_here').attr('placeholder',PayswitchText)    
          }
          
       }); 
    });
</script>
                                   