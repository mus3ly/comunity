
<div class="row">
     <?php
     
    //  foreach($product_data as $row){
    //     var_dump($row);
        ?>
    <div class="col-md-12">
     <?php
            echo form_open(base_url() . 'vendor/product/update/'.$row['product_id'], array(
                'class' => 'form-horizontal',
                'method' => 'post',
                'id' => 'product_add',
                'enctype' => 'multipart/form-data'
            ));
        ?>
 
            <!--Panel heading-->
            <div class="panel-body">
                <div class="tab-base">
                    <!--Tabs Content-->                    
                    <div class="tab-content">
                        <div id="blog_details" class="tab-pane fade active in">

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1">
                                    <?php echo translate('blog_title');?>
                                        </label>
                                <div class="col-sm-6">
                                    <input type="text" name="title" id="blog_title" value="<?php echo $row['title']; ?>" placeholder="<?php echo translate('blog_title');?>" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1">
                                    <?php echo translate('catchphrase_or_slogan');?>
                                        </label>
                                <div class="col-sm-6">
                                    <input type="text" name="slog" id="demo-hor-1" value="<?php echo $row['slog']; ?>" placeholder="<?php echo translate('catchphrase_or_slogan');?>" class="form-control required">
                                </div>
                            </div>
                            
                            <!--<div class="form-group btm_border">-->
                            <!--    <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category');?></label>-->
                            <!--    <div class="col-sm-6">-->
                            <!--        <?php echo $this->crud_model->select_html('blog_category','blog_category','name','edit','demo-chosen-select required',$row['category'],'','','get_cat'); ?>-->
                            <!--    </div>-->
                            <!--</div>-->
                     
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-2"><?php echo translate('category');?></label>
                                <div class="col-sm-6">
                                    <select name="category" class="form-control demo-chosen-select" id="blog_cat">
                                        <?php
                                       foreach($brandss as $k => $v){
                                        ?>
                                        <option value="<?= $v['category_id'];?>"   <?= (isset($row['category']) && $row['category'] == $v['category_id'] )? 'selected' : '' ?>><?= $v['category_name'];?></option>
                                        <?php
                                       }
                                        ?>
                                        </select>
                                    </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-12"><?php echo translate('images');?></label>
                                <div class="col-sm-6">
                                    <span class="pull-left btn btn-default btn-file"> <?php echo translate('choose_file');?>
                                            <input type="file" value="<?= ($row['sideimg'])?$row['sideimg']:""; ?>" name="sideimg" onchange="preview1(this);" id="demo-hor-inputpass" class="form-control">
                                        </span>
                                    <br><br>
                                    <span id="previewImg1" >
                                            
                                            <?php
                                                if($row['comp_cover'])
                                                {
                                                    $img = $this->crud_model->size_img($row['comp_cover'],100,100);
                                                    ?>
                                                    <img class="img-responsive" width="100" src="<?= $img;?>" data-id="_paris/uploads/product" alt="Feature Image"><?php
                                                }
                                            ?>
                                        </span>
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-14">
                                    <?php echo translate('summary');?>
                                        </label>
                                <div class="col-sm-6">
                                    <textarea rows="9" class="summernotes" data-height="200" data-name="main_heading"><?php echo $row['main_heading']; ?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-14">
                                    <?php echo translate('description');?>
                                        </label>
                                <div class="col-sm-6">
                                    <textarea rows="9" class="summernotes" id="blog_disc" data-height="300" data-name="description"><?php echo $row['description']; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('author');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="author" value="<?php echo $row['author_name']; ?>" id="demo-hor-1" placeholder="<?php echo translate('author');?>" class="form-control required">
                                </div>
                            </div>

                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('date');?></label>
                               <div class="col-sm-6">
                                    <?php 
                                    $date = date('Y-m-d');
                                    ?>
                                    <input type="text" name="date" id="demo-hor-1" class="form-control" value="<?= $date; ?>" readonly>
                                    <input type="hidden" name="is_blog" id="demo-hor-1" class="form-control" value="1" readonly>
                                </div>
                            </div>
                            <!-- <div class="form-group btm_border">-->
                            <!--    <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('time');?></label>-->
                            <!--    <div class="col-sm-6">-->
                            <!--        <input type="time" name="openig_time" value="<?php echo time('H:i:s', strtotime($row['openig_time'])); ?>" id="demo-hor-1" class="form-control">-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="form-group btm_border">
                                <label class="col-sm-4 control-label" for="demo-hor-1"><?php echo translate('slug');?></label>
                                <div class="col-sm-6">
                                    <input type="text" name="slug" id="slug" value="<?php echo $row['slug']; ?>" class="form-control required">
                                    <small id="slug_error_msg" style="display:none;color:red">Slug already exists, choose the new and unique one..</small>
                                </div>
                            </div>
                            
                        </div>


                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-11">
                        <span class="btn btn-purple btn-labeled fa fa-refresh pro_list_btn pull-right" 
                            onclick="ajax_set_full('add','<?php echo translate('add_product'); ?>','<?php echo translate('successfully_added!'); ?>','product_add',''); "><?php echo translate('reset');?>
                        </span>
                    </div>
                    
                    <div class="col-md-1">
                        <span class="btn btn-success btn-md btn-labeled fa fa-upload pull-right enterer" id="registerbtn" onclick="validate_listing();" ><?php echo translate('upload');?></span>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>
<?php
    //}
?>
<!--Bootstrap Tags Input [ OPTIONAL ]-->
<script src="<?php echo base_url(); ?>template/back/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
  <script>
        $('#slug').on('keyup',function(){
              $('#registerbtn').attr("disabled", false);
            $('#slug').removeClass('error');
            $('#slug_error_msg').css({'display':'none'});
            var val = $(this).val();
            var url='<?= base_url('vendor/productslug') ?>';
             if(val){
                $.ajax({
                url: url,
                type: "Post",
                async: true,
                data: { value:val},
                success: function (data) {
                     if(data == 'error'){
                       $('#slug').addClass('error');
                       $('#slug_error_msg').css({'display':'block'});
                       $('#registerbtn').attr("disabled", true);
                       
                   }
                 },
                });
            }
        });
        
    </script>
<script type="text/javascript">
    window.preview = function (input) {
        if (input.files && input.files[0]) {
            $("#previewImg").html('');
            $(input.files).each(function () {
                var reader = new FileReader();
                reader.readAsDataURL(this);
                reader.onload = function (e) {
                    $("#previewImg").append("<div style='float:left;border:4px solid #303641;padding:5px;margin:5px;'><img height='80' src='" + e.target.result + "'></div>");
                }
            });
        }
    }

     $('.delete-div-wrap .close').on('click', function() { 
	 	var pid = $(this).closest('.delete-div-wrap').find('img').data('id'); 
		var here = $(this); 
		msg = 'Really want to delete this Image?'; 
		bootbox.confirm(msg, function(result) {
			if (result) { 
				 $.ajax({ 
					url: base_url+''+user_type+'/'+module+'/dlt_img/'+pid, 
					cache: false, 
					success: function(data) { 
						$.activeitNoty({ 
							type: 'success', 
							icon : 'fa fa-check', 
							message : 'Deleted Successfully', 
							container : 'floating', 
							timer : 3000 
						}); 
						here.closest('.delete-div-wrap').remove(); 
					} 
				}); 
			}else{ 
				$.activeitNoty({ 
					type: 'danger', 
					icon : 'fa fa-minus', 
					message : 'Cancelled', 
					container : 'floating', 
					timer : 3000 
				}); 
			}; 
		  }); 
		});

    function other_forms(){}
	
	function set_summer(){
        $('.summernotes').each(function() {
            var now = $(this);
            var h = now.data('height');
            var n = now.data('name');
            now.closest('div').append('<input type="hidden" class="val" name="'+n+'">');
            now.summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['codeview', 'help']],
                ],
                height: h,
                onChange: function() {
                    now.closest('div').find('.val').val(now.code());
                }
            });
			now.closest('div').find('.val').val(now.code());
        });
	}

    function option_count(type){
        var count = $('#option_count').val();
        if(type == 'add'){
            count++;
        }
        if(type == 'reduce'){
            count--;
        }
        $('#option_count').val(count);
    }

    function set_select(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
    }
    
    $(document).ready(function() {
        // alert();
        set_select();
        set_summer();
        createColorpickers();
    });

    function other(){
        $('.demo-chosen-select').chosen();
        $('.demo-cs-multiselect').chosen({width:'100%'});
        $('#sub').show('slow');
        $('#brn').show('slow');
    }
    function get_cat(id){
        $('#brand').html('');
        $('#sub').hide('slow');
        $('#brn').hide('slow');
        ajax_load(base_url+'admin/blog/sub_by_cat/'+id,'sub_cat','other');
        ajax_load(base_url+'admin/blog/brand_by_cat/'+id,'brand','other');
    }

    function get_sub_res(id){}

    $(".unit").on('keyup',function(){
        $(".unit_set").html($(".unit").val());
    });
	
	function createColorpickers() {
	
		$('.demo2').colorpicker({
			format: 'rgba'
		});
		
	}
	
    
    $("#more_btn").click(function(){
        $("#more_additional_fields").append(''
            +'<div class="form-group">'
            +'    <div class="col-sm-4">'
            +'        <input type="text" name="ad_field_names[]" class="form-control"  placeholder="<?php echo translate('field_name'); ?>">'
            +'    </div>'
            +'    <div class="col-sm-5">'
            +'        <textarea rows="9"  class="summernotes" data-height="100" data-name="ad_field_values[]"></textarea>'
            +'    </div>'
            +'    <div class="col-sm-2">'
            +'        <span class="remove_it_v rms btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            +'    </div>'
            +'</div>'
        );
        set_summer();
    });
    
    
    $("#more_option_btn").click(function(){
        option_count('add');
        var co = $('#option_count').val();
        $("#more_additional_options").append(''
            +'<div class="form-group" data-no="'+co+'">'
            +'    <div class="col-sm-4">'
            +'        <input type="text" name="op_title[]" class="form-control required"  placeholder="<?php echo translate('customer_input_title'); ?>">'
            +'    </div>'
            +'    <div class="col-sm-5">'
            +'        <select class="demo-chosen-select op_type required" name="op_type[]" >'
            +'            <option value="">(none)</option>'
            +'            <option value="text">Text Input</option>'
            +'            <option value="single_select">Dropdown Single Select</option>'
            +'            <option value="multi_select">Dropdown Multi Select</option>'
            +'            <option value="radio">Radio</option>'
            +'        </select>'
            +'        <div class="col-sm-12 options">'
            +'          <input type="hidden" name="op_set'+co+'[]" value="none" >'
            +'        </div>'
            +'    </div>'
            +'    <input type="hidden" name="op_no[]" value="'+co+'" >'
            +'    <div class="col-sm-2">'
            +'        <span class="remove_it_o rmo btn btn-danger btn-icon btn-circle icon-lg fa fa-times" onclick="delete_row(this)"></span>'
            +'    </div>'
            +'</div>'
        );
        set_select();
    });
    
    $("#more_additional_options").on('change','.op_type',function(){
        var co = $(this).closest('.form-group').data('no');
        if($(this).val() !== 'text' && $(this).val() !== ''){
            $(this).closest('div').find(".options").html(''
                +'    <div class="col-sm-12">'
                +'        <div class="col-sm-12 options margin-bottom-10"></div><br>'
                +'        <div class="btn btn-mint btn-labeled fa fa-plus pull-right add_op">'
                +'        <?php echo translate('add_options_for_choice');?></div>'
                +'    </div>'
            );
        } else if ($(this).val() == 'text' || $(this).val() == ''){
            $(this).closest('div').find(".options").html(''
                +'    <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });
    
    $("#more_additional_options").on('click','.add_op',function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).closest('.col-sm-12').find(".options").append(''
            +'    <div>'
            +'        <div class="col-sm-10">'
            +'          <input type="text" name="op_set'+co+'[]" class="form-control required"  placeholder="<?php echo translate('option_name'); ?>">'
            +'        </div>'
            +'        <div class="col-sm-2">'
            +'          <span class="remove_it_n rmon btn btn-danger btn-icon btn-circle icon-sm fa fa-times" onclick="delete_row(this)"></span>'
            +'        </div>'
            +'    </div>'
        );
    });
    
    $('body').on('click', '.rmo', function(){
        $(this).parent().parent().remove();
    });

    function next_tab(){
        $('.nav-tabs li.active').next().find('a').click();                    
    }
    function previous_tab(){
        $('.nav-tabs li.active').prev().find('a').click();                     
    }
    
    $('body').on('click', '.rmon', function(){
        var co = $(this).closest('.form-group').data('no');
        $(this).parent().parent().remove();
        if($(this).parent().parent().parent().html() == ''){
            $(this).parent().parent().parent().html(''
                +'   <input type="hidden" name="op_set'+co+'[]" value="none" >'
            );
        }
    });

    
    $('body').on('click', '.rms', function(){
        $(this).parent().parent().remove();
    });


    $("#more_color_btn").click(function(){
        $("#more_colors").append(''
            +'      <div class="col-md-12" style="margin-bottom:8px;">'
            +'          <div class="col-md-8">'
            +'              <div class="input-group demo2">'
            +'                 <input type="text" value="#ccc" name="color[]" class="form-control" />'
            +'                 <span class="input-group-addon"><i></i></span>'
            +'              </div>'
            +'          </div>'
            +'          <span class="col-md-4">'
            +'              <span class="remove_it_v rmc btn btn-danger btn-icon btn-circle icon-lg fa fa-times" ></span>'
            +'          </span>'
            +'      </div>'
        );
        createColorpickers();
    });                

    $('body').on('click', '.rmc', function(){
        $(this).parent().parent().remove();
    });

	
    function delete_row(e)
    {
        e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    }    
	
	
	$(document).ready(function() {
		$("form").submit(function(e){
			event.preventDefault();
		});
	});
	       var user_type = 'vendor';
    var module = 'product';
    var list_cont_func = 'list';
    var dlt_cont_func = 'delete';
    var feature = 0;
</script>
  <script>
        $("#blog_title").keyup(function() {
          var Text = $(this).val();
          Text = Text.toLowerCase();
          Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
          $("#slug").val(Text);    
          $('#slug').keyup();
        });
            function validate_listing(){
        // var textareaValue = $('#desc_summernote').summernote('code', 'value');
        var plainText = $($("#blog_disc").code()).text();
          if(plainText.length < 300)
            {
                alert('Please add minimum 300 character in description');
                return 0;
            }
        form_submit('product_add','<?php echo translate('product_has_been_uploaded!'); ?>');proceed('to_add');
    }
    </script>
<style>
	.btm_border{
		border-bottom: 1px solid #ebebeb;
		padding-bottom: 15px;	
	}
</style>

