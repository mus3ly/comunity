<div id="content-container">
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo translate('upgrade_package');?></h1>
    </div>
    <div class="tab-base">
        <div class="tab-base tab-stacked-left">
            <div class="col-sm-12">
            <div class="panel panel-bordered-dark">
                <?php
                    $membership    = $this->db->get_where('vendor', array(
                        'vendor_id' => $this->session->userdata('vendor_id')
                    ))->row()->membership;
                    echo form_open(base_url() . 'vendor/package/upgrade/', array(
                        'class'     => 'form-horizontal',
                        'method'    => 'post',
                        'id'        => 'upgrade_form',
                        'enctype'   => 'multipart/form-data'
                    ));
                ?>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo translate('product');?></label>
                            <div class="col-sm-6">
                                <select name="membership" id="type" class="demo-chosen-select" onchange="get_membership_info(this.value)">
                                    <?php
                                    $this->db->order_by('mcat','asc');
                                    $memberships = $this->db->where('promo_check','1')->get('membership')->result_array();
                                        foreach ($memberships as $row1) {
                                        $pkg = $this->db->where('id', $row1['mcat'])->get('member_cat')->result_array();

                                        ?>
                                    <option value="<?php echo $row1['membership_id']; ?>"
                                        <?php if ($row1['membership_id'] == $e_match) {
                                            echo 'selected="selected"';
                                        } ?> >
                                        <?php echo $pkg[0]['name'].' ('. $row1['title'].' )'; ?>
                                    </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo translate('details');?></label>
                            <div class="col-sm-6" id="mem_info">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?php echo translate('payment_method');?></label>
                            <div class="col-sm-6">
                                <select name="method" class="demo-chosen-select" onchange="get_stripe(this.value)">
                                <?php if($this->db->get_where('business_settings',array('type'=>'paypal_set'))->row()->value == 'ok'){ ?>
                                    <option value="paypal" >PayPal</option>
                                <?php } if($this->db->get_where('business_settings',array('type'=>'stripe_set'))->row()->value == 'ok'){ ?>
                                    <option value="stripe" >Stripe</option>
                                <?php } if($this->db->get_where('business_settings',array('type'=>'c2_set'))->row()->value == 'ok'){ ?>
                                    <option value="c2" >Twocheckout</option>
                                <?php }if($this->db->get_where('business_settings',array('type'=>'vp_set'))->row()->value == 'ok'){ ?>
                                    <option value="vp" >VoguePay</option>
                                <?php } if($this->db->get_where('business_settings',array('type'=>'pum_set'))->row()->value == 'ok'){?>
                                <option value="pum" >PayUmoney</option>
                                <?php } if($this->db->get_where('business_settings',array('type'=>'pum_set'))->row()->value == 'ok'){?>
                                <option value="bitcoin" >Bitcoin</option>
                                <?php } if($this->db->get_where('business_settings',array('type'=>'ssl_set'))->row()->value == 'ok'){?>
                                    <option value="ssl" >SSLcommerz</option>
                                <?php }?>
                                    <option value="cash" >Cash</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"></label>
                            <div class="col-sm-6">
                                <span id='verify'></span>
                            </div>
                        </div>
                        <script src="https://checkout.stripe.com/checkout.js"></script>
                        <script type="text/javascript">
                            var handler = StripeCheckout.configure({
                                key: '<?php echo $this->db->get_where('business_settings' , array('type' => 'stripe_publishable'))->row()->value;  ?>',
                                image: '',
                                token: function(token) {
                                  // Use the token to create the charge with a server-side script.
                                  // You can access the token ID with `token.id`
                                    $('#upgrade_form').append("<input type='hidden' name='stripeToken' value='" + token.id + "' />");
                                    $.activeitNoty({
                                        type: 'success',
                                        icon : 'fa fa-check',
                                        message : '<?php echo translate('your_card_details_verified!'); ?>',
                                        container : 'floating',
                                        timer : 3000
                                    });
                                    $('#verify').html('<?php echo translate('your_card_details_verified!'); ?>');
                                }
                            });

                            function get_stripe(type){
                                var typea = $('#type').val();
                                if(type == 'stripe'){
                                    $.ajax({
                                        url: "<?php echo base_url(); ?>vendor/business_settings/membership_price/"+typea,
                                        success: function(total){
                                            total = total.replace("<?php echo currency(); ?>", '');
                                            //total = parseFloat(total.replace(",", ''));
                                            total = total/parseFloat(<?php echo exchange(); ?>);
                                            total = total*100;
                                            handler.open({
                                                name: '<?php echo $system_title; ?>',
                                                description: '<?php echo translate('pay_with_stripe'); ?>',
                                                amount: total
                                            });
                                        }
                                    });

                                }
                            }
                            // Close Checkout on page navigation
                            $(window).on('popstate', function() {
                                handler.close();
                            });
                        </script>
                    <div class="panel-footer text-right">
                        <button class="btn btn-info enterer" >
                            <?php echo translate('upgrade');?>
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<div style="display:none;" id="business"></div>
<script>
	var base_url = '<?php echo base_url(); ?>';
	var user_type = 'vendor';
	var module = 'business_settings';
	var list_cont_func = '';
	var dlt_cont_func = '';

    function get_membership_info(id){
        $('#mem_info').load('<?php echo base_url(); ?>vendor/business_settings/membership_info/'+id);
    }
    $(document).ready(function(){
		get_membership_info(<?php echo $membership; ?>);
    });

</script>
<script src="<?php echo base_url(); ?>template/back/js/custom/business.js"></script>
