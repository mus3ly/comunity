
<footer id="footer">
    <div class="show-fixed pull-right">
        <ul class="footer-list list-inline">
            <li>
                <p class="text-sm"><?php echo translate('SEO_proggres');?></p>
                <div class="progress progress-sm progress-light-base">
                    <div style="width: 80%" class="progress-bar progress-bar-danger"></div>
                </div>
            </li>
    
            <li>
                <p class="text-sm"><?php echo translate('online_tutorial');?></p>
                <div class="progress progress-sm progress-light-base">
                    <div style="width: 80%" class="progress-bar progress-bar-primary"></div>
                </div>
            </li>
            <li>
                <button class="btn btn-sm btn-dark btn-active-success"><?php echo translate('checkout');?></button>
            </li>
        </ul>
    </div>
    <?php if($this->session->userdata('title') == 'admin'){ ?>
        <div class="hide-fixed pull-right pad-rgt">
            Currently <?= demo()?'demo':''?> v<?php echo $this->db->get_where('general_settings',array('type'=>'version'))->row()->value; ?>
        </div>
    <?php } ?>
	<p class="pad-lft">&#0169; 2018 <?php echo $system_title;?></p>
</footer>
<?php
            if ($this->session->userdata('title') == 'vendor') {
              $vend = $this->db->where('vendor_id',$this->session->userdata('vendor_id'))->get('vendor')->row();
              $date = $vend->exp_date;
            ?>
<?php
}
?>