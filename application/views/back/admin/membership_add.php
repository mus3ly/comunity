<div>
    <?php
		echo form_open(base_url() . 'admin/membership/do_add/', array(
			'class' => 'form-horizontal',
			'method' => 'post',
			'id' => 'membership_add',
			'enctype' => 'multipart/form-data'
		));
	?>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('title');?>
                    	</label>
                <div class="col-sm-6">
                    <input type="text" name="title" placeholder="<?php echo translate('title'); ?>" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                	<?php echo translate('membership_category');?>
                    	</label>
                <div class="col-sm-6">
                    <select class="form-control required" name="mcat">
                        <?php
                        foreach($category as $k => $v){
                        ?>
                        <option value="<?= $v['id']; ?>"><?= $v['name']; ?></option>
                        <?php } ?>
                    </select>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('no_of_listings');?>
                        </label>
                <div class="col-sm-6">
                    <input type="number" name="product_limit"  placeholder="<?php echo translate('no_of_listings'); ?>" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('discount');?>
                        </label>
                <div class="col-sm-6">
                    <input type="text" name="discount"  placeholder="<?php echo translate('discount'); ?>" class="form-control required">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('price');?> (<?php echo currency('','def'); ?>)
                        </label>
                <div class="col-sm-2">
                    <input type="number" name="price" placeholder="<?php echo translate('price'); ?>" class="form-control required">
                </div>
                <div class="col-sm-1">
                    /
                </div>
                <div class="col-sm-2">
                    <input type="number" name="timespan" placeholder="<?php echo translate('timespan'); ?>" class="form-control required">
                </div>
                <div class="col-sm-2">
                    <?php echo translate('days'); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('stripe_id');?>
                        </label>
                <div class="col-sm-6">
                    <input type="text" name="stripe_id"  placeholder="<?php echo translate('stripe_id'); ?>" class="form-control required">
                </div>
              
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('stripe_live_id');?>
                        </label>
                <div class="col-sm-6">
                    <input type="text" name="stripe_live_id"  placeholder="<?php echo translate('stripe_live_id'); ?>" class="form-control required">
                </div>
              
            </div>
            
             <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('promo_code');?>
                        </label>
                <div class="col-sm-6">
                    <input type="number" name="promo_code" placeholder="<?php echo translate('promo_code'); ?>" class="form-control">
                </div>
              
            </div>
            
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('promo_limit');?>
                        </label>
                <div class="col-sm-6">
                    <input type="number" name="promo_limit" placeholder="<?php echo translate('promo_limit'); ?>" class="form-control">
                </div>
              
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-1">
                    <?php echo translate('Referal check');?>
                </label>
                <div class="col-sm-6">
                    <input type="checkbox" value="1" name="promo_check" >
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('package_seal');?></label>
                <div class="col-sm-6">
                    <span class="pull-left btn btn-default btn-file">
                        <?php echo translate('select_package_seal');?>
                        <input type="file" name="img" id='imgInp' accept="image">
                    </span>
                    <br><br>
                    <span id='wrap' class="pull-left" >
                        <img src="<?php echo base_url(); ?>uploads/others/photo_default.png" 
                            width="48.5%" id='blah' > 
                    </span>
                </div>
            </div>

        </div>
	</form>
</div>

<script>
	$(document).ready(function() {
		$('.demo-chosen-select').chosen();
		$('.demo-cs-multiselect').chosen({width:'100%'});
	});
	
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
</script>

<script src="<?php echo base_url(); ?>template/back/js/custom/brand_form.js"></script>
