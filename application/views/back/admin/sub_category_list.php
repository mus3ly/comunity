<div class="panel-body" id="demo_s">
    <table id="demo-table" class="table table-striped"  data-pagination="true" data-show-refresh="true" data-ignorecol="0,3" data-show-toggle="true" data-show-columns="false" data-search="true" >
        <thead>
            <tr>
                <th><?php echo translate('no');?></th>
                <th><?php echo translate('name');?></th>
                <th><?php echo translate('category');?></th>
                <th><?php echo translate('inner_categries');?></th>
                <th class="text-right"><?php echo translate('options');?></th>
            </tr>
        </thead>				
        <tbody >
        <?php
            $i=0;
            foreach($all_sub_category as $row){
                $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['sub_category_name']; ?></td>
            <td><?php echo $this->crud_model->get_type_name_by_id('category',$row['category'],'category_name'); ?></td>
            <?php
            	$brands=$this->db->where('category',$row['sub_category_id'])->get('sub3_category')->result_array();
			?>
            <td>
    				
          	</td>
            <td class="text-right">
                <a class="btn btn-primary btn-labeled fa fa-plus-circle " 
                            onclick="ajax_modal('add3/<?php echo $row['sub_category_id']; ?>','<?php echo translate('add_sub-category'); ?>','<?php echo translate('successfully_added!'); ?>','sub_category_add','')">
                                <?php echo translate('create_level3_category');?>
                                    </a>
                <a class="btn btn-success btn-xs btn-labeled fa fa-wrench" data-toggle="tooltip" 
                    onclick="ajax_modal('edit','<?php echo translate('edit_sub-category_(_physical_product_)'); ?>','<?php echo translate('successfully_edited!'); ?>','sub_category_edit','<?php echo $row['sub_category_id']; ?>')" data-original-title="Edit" data-container="body">
                        <?php echo translate('edit');?>
                </a>
                <a onclick="delete_confirm('<?php echo $row['sub_category_id']; ?>','<?php echo translate('really_want_to_delete_this?'); ?>')" 
                        class="btn btn-danger btn-xs btn-labeled fa fa-trash" data-toggle="tooltip"
                            data-original-title="Delete" data-container="body">
                                <?php echo translate('delete');?>
                </a>
            </td>
        </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
</div>
           
<div id='export-div'>
    <h1 style="display:none;"><?php echo translate('sub_category');?></h1>
    <table id="export-table" data-name='sub_category' data-orientation='p' style="display:none;">
            <thead>
                <tr>
                    <th><?php echo translate('no');?></th>
                    <th><?php echo translate('name');?></th>
                    <th><?php echo translate('category');?></th>
                </tr>
            </thead>
                
            <tbody >
                <?php
                    $i = 0;
                    foreach($all_sub_category as $row){
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row['sub_category_name']; ?></td>
                    <td><?php echo $this->crud_model->get_type_name_by_id('category',$row['category'],'category_name'); ?></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
    </table>
</div>
           