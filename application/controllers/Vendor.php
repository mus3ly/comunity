<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor extends CI_Controller
{
    /*n
     *  Developed by: Active IT zone
     *  Date    : 14 July, 2015
     *  Active Supershop eCommerce CMS
     *  http://codecanyon.net/user/activeitezone
     */

    function __construct()
    {
        
        parent::__construct();
        $this->load->database();
        $this->load->library('spreadsheet');
        $this->load->library('paypal');
        $this->load->library('twoCheckout_Lib');
        $this->load->library('vouguepay');
        $this->load->library('pum');
        
    }
    public function preview_btn($id)
    {
        $user = $this->db->where('product_id', $id)->get('product')->row();
        if($user)
        {
            custom_redirect(base_url($user->slug));
        }
        else
        {
            custom_redirect(base_url());
        }
    }
    public function vendor_page($id)
    {


        $user = $this->db->where('vendor_id', $id)->get('vendor')->row();

        $added_by = array(
            'type' => 'vendor',
            'id' => $id,
        );
        $in = array();
        $in['title'] = $user->company;
        $in['brand'] = $user->cat1;
        $in['cat2'] = $user->cat2;
        $in['category'] = $user->cat3;
        $in['name'] = $user->name;
        $in['address1'] = $user->address1;
        $in['address2'] = $user->address2;
        $in['country'] = $user->country;
        $in['city'] = $user->city;
        $in['state'] = $user->state;
        $in['status'] = 'ok';
        $lat = '';
        $lng = '';
        if(isset($_SESSION['ip_info']))
        {
            $lat = $_SESSION['ip_info']['lat'];
            $lng = $_SESSION['ip_info']['lon'];
        }
        $in['lat'] = $lat;
        $in['lng'] = $lng;
        $in['bussniuss_phone'] = $user->phone;
        $in['whatsapp_number'] = $user->whatsapp;
        $in['bussniuss_email'] = $user->email;
        $in['zip'] = $user->zip;
        $in['added_by'] = json_encode($added_by);
        $in['is_bpage'] = 1;
        $in['slug'] = slugify($user->company);

        $this->db->insert('product', $in);
        $pid = $this->db->insert_id();
        // create_slug($pid);
        $v = $this->db->where('vendor_id', $id)->update('vendor', array('bpage' => $pid));
        //s
        if($user->promo == ''){
        if ($user->pack) {
            $pack = $this->db->where('membership_id', $user->pack)->get('membership')->row();
            $option = array(
                'ads' => $pack->product_limit,
            );
            $this->cart->destroy();
            $data = array(
                'id' => $user->pack,
                'qty' => 1,
                'signup_pkg' => 1,
                'option' => json_encode($option),
                'vendor' => $id,
                'vendor_name' => $user->title,
                'price' => $pack->price,
                'name' => $pack->title,
                'shipping' => 0,
                'tax' => 0
            );
            $this->cart->insert($data);
            $_SESSION['subscription_vendor'] = $id;
            return   "checkout";
        } else {
            $pack = $this->db->where('amount', 0)->get('package')->row();
            $this->process_pack($id, 0);
            return 0;
        }
        }else{
            $promo = $user->promo;
            $chk = $this->db->where('promo_code',$promo)->get('membership')->row();
            $new = $chk->promo_limit -1;
            $chk = $this->db->where('promo_code',$promo)->update('membership',array('promo_limit'=> $new));
            return 'vendor_promo';
        }
        //bpage
    }
    function cats_edit($pid,$type= 'get',$cat = 0){
        if($pid)
        {
            $pro = $this->db->where('product_id',$pid)->get('product')->row();
            if($type == 'add' && $cat)
            {
                if($pro->category)
                $pro->category = $pro->category.','.$cat;
                else
                $pro->category = $cat;
            }
            $cats = array_filter(explode(',',$pro->category));
            if($type == 'remove')
            {
                unset($cats[count($cats)-1]);
            }
            ?>
            <input type="hidden" id="category" value="<?= ($cats)?implode(',',$cats):""; ?>" name="category"/>
            <?php
            $r = $this->db->where('product_id',$pid)->update('product',array('category'=>implode(',',$cats)));
            if(true)
            {
                if(count($cats))
                {
                    $para2 = $cats[count($cats)-1];
                         $sing = $this->db->where('category_id',$para2)->get('category')->row();
                         if($sing)
                          $level = $sing->level;
       
            $breed = array();
            
            $cid = $para2;
            for ($i=1; $i <= $level; $i++) { 
             
                 $srow = $this->db->where('category_id',$cid)->get('category')->row_array();
               if(isset($srow) && !empty($srow)){
                   $breed[] = $cid;
                 $cid = $srow['pcat'];
                //  var_dump($cid);
               }
            }
            if($breed)
            {
                ?>
                <div class="breaddcum">
                    <ul>
                        <?php
                        $cat = array();
                        foreach(array_reverse($breed) as $k=> $v)
                        {
                            $cat[] = $v;
                            $crow = $this->db->where('category_id',$v)->get('category')->row();
                            ?>
                            <li><?= $crow->category_name;?>
                            <?php
                            if($v == $para2)
                            {
                            ?>
                            <i onclick="cats_edit(<?=$pid?>,'remove')" class="cross_icon fa-solid fa-xmark"></i>
                            <?php
                            }
                            ?>
                            </li>
                            <?php
                        }
                        ?>


                    </ul>
                </div>
                <?php
                $all_category =  $this->db->where('pcat',$para2)->get('category')->result_array();
                    foreach($all_category as $k=>$v){
                                if(true)
                                {
                            ?>
                                <div class="col-md-4 col-sm-12 col-xs-12 " onclick="cats_edit(<?=$pid?>,'add','<?= $v['category_id'];?>')" >
                                    <a href="javascript:void(0);"><div class="flip-card ">
                                  <div class="flip-card-inner">
                                    <div class="flip-card-front ">
                                        <!--<i class="fa <?= $v['fa_icon'];?>" aria-hidden="true"></i>-->
                                        <!--<br>-->
                                        <p><?= $v['category_name'];?></p>
                                    </div>
                                    <!--<div class="flip-card-back"><p><?= $v['category_name'];?> </p></div>-->
                                  </div>
                                </div>
                                </a>
                                </div>
                                <?php 
                                }
                }
                ?>
                <?php
            }
                }
                else
                {
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
                    foreach($all_category as $k=>$v){
                                if(true)
                                {
                            ?>
                                <div class="col-md-4 col-sm-12 col-xs-12 " onclick="cats_edit(<?=$pid?>,'add','<?= $v['category_id'];?>')" >
                                    <a href="#"><div class="flip-card ">
                                  <div class="flip-card-inner">
                                    <div class="flip-card-front ">
                                        <!--<i class="fa <?= $v['fa_icon'];?>" aria-hidden="true"></i>-->
                                        <!--<br>-->
                                        <p><?= $v['category_name'];?></p>
                                    </div>
                                    <!--<div class="flip-card-back"><p><?= $v['category_name'];?> </p></div>-->
                                  </div>
                                </div>
                                </a>
                                </div>
                                <?php 
                                }
                }
            }
        }
        }
        exit();
    }
    function admin_fields(){
    {
        if(isset($_GET['cats']))
        {
            $cats = $_GET['cats'];
            $pid = (isset($_GET['pid'])?$_GET['pid']:0);
            $pro = $this->db->where('product_id',$pid)->get('product')->row_array();
              
                          
            $old_values = array();
            if(isset($pro['additional_fields'])){
                        $exp = json_decode($pro['additional_fields']);
                        // var_dump($exp);
                        $ex = json_decode($exp->name);
                        $values = json_decode($exp->value);
                        
                          foreach($ex as $k => $v){
                              $val = $values[$k];
                        $txt = strip_tags($val);
                        if($val)
                        {
                            $old_values[trim($v)] = $val;
                        }
                          }
              }
            $cats = explode(',',$cats);
            if(count($cats) == 1)
            {
                $row = $this->db->where('category_id',$cats[0])->get('category')->row();
                if($row)
                {
                    $cats = explode(',',$row->path);
                }
            }
            $fields = $this->db->where_in('category', $cats)->get('list_fields')->result_array();
            foreach($fields as $k=> $v)
            {
                $v['val'] = '';
                
                if(isset($old_values[trim($v['label'])]))
                $v['val'] = $old_values[trim($v['label'])];
                // var_dump($v['label']);
                // if($v['label'] == 'List your best features')
                // {
                //     $v['val'] = 'Test';
                // }
                if($v['name'] == 'date_posted')
                {
                    if($pro['status'] == 'draft' && !$pro['posted_on'])
                    {
                        $v['val'] = date('M d, Y',strtotime(date('Y-m-d') ));
                        
                    }
                    else
                    {
                        
                        $v['val'] = date('M d, Y',strtotime($pro['posted_on'] ));
                    }
                }
                if($v['name'] == 'date_updated')
                {
                    if($pro['status'] == 'draft')
                    {
                        $v['val'] = ' ';
                        
                    }
                    else
                    {
                        $v['val']- date('M d, Y',strtotime(date('Y-m-d') ));
                    }
                }
                // if($v['name'] != 'date_posted' &&$v['name'] != 'date_updated')
                $this->load->view('admin_f',$v);
            }
        }
    }
    }
    function testing(){
      $x = update_col('31');
      var_dump($this->db->last_query());
    }
    function productslug(){
        $slug = $_REQUEST['value'];
        $this->db->where('slug', $slug);
        $query = $this->db->get('product');
        $msg = '';
        if($query->num_rows() > 0){
          $msg = 'error';
        }
        else{
           $msg = 'success';
        }
        echo $msg;
    }
    function address($para1 = '', $para2 = '')
    {
        if ($para1 == 'do_add') {
            $tit = $_POST['title'];
             unset($_POST['title']);
            $subids = $_POST;
            $subids['validate'] = true;
            $final = http_build_query($subids);

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.goshippo.com/addresses/',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  $final,
              CURLOPT_HTTPHEADER => array(
                'Authorization: ShippoToken shippo_test_52bf877b99c795c0e3a73a8dd2483c417db6f730',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: tracker_sessionid=7ff959a70da74398953017ff97c920a5'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           $resp = json_decode($response);
          if(isset($resp->object_id) && isset($resp->is_complete) && $resp->is_complete == true){
            $type              = 'address';
            $data['uid']       = $this->session->userdata('vendor_id');
            $data['name']       = $this->input->post('name');
            $data['company']     = $this->input->post('company');
            $data['street1']      = $this->input->post('street1');
            $data['city']    = $this->input->post('city');
            $data['state']    = $this->input->post('state');
            $data['zip']    = $this->input->post('zip');
            $data['shippo_id']    = $resp->object_id;
            $data['country']    = $this->input->post('country');
            $data['title']    = $tit;
            $data['create_at']    =date('Y-m-d H:i:s');
            $data['raw']       = $response;
            $data['email']       = $this->input->post('email');
            $this->db->insert('address', $data);
            // echo $this->db->last_query();
            $id = $this->db->insert_id();
          }else{
             echo translate('Invalid Data');
             exit();
          }

            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == "update") {
            $tit = $_POST['title'];
             unset($_POST['title']);
            $subids = $_POST;
            $subids['validate'] = true;
            $final = http_build_query($subids);

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.goshippo.com/addresses/',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>  $final,
              CURLOPT_HTTPHEADER => array(
                'Authorization: ShippoToken shippo_test_52bf877b99c795c0e3a73a8dd2483c417db6f730',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: tracker_sessionid=7ff959a70da74398953017ff97c920a5'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
           $resp = json_decode($response);
          if(isset($resp->object_id) && isset($resp->is_complete) && $resp->is_complete == true){
            $type              = 'address';
            $data['uid']       = $this->session->userdata('vendor_id');
            $data['name']       = $this->input->post('name');
            $data['company']     = $this->input->post('company');
            $data['title']    = $tit;
            $data['street1']      = $this->input->post('street1');
            $data['city']    = $this->input->post('city');
            $data['state']    = $this->input->post('state');
            $data['zip']    = $this->input->post('zip');
            $data['country']    = $this->input->post('country');
            $data['shippo_id']    = $resp->object_id;
            $data['create_at']    =date('Y-m-d H:i:s');
            $data['raw']       = $response;
            $data['email']       = $this->input->post('email');
              $this->db->where('address_id', $para2);
            $this->db->update('address', $data);
            // echo $this->db->last_query();
          }else{
             $this->session->set_flashdata('error',translate('Invalid Data'));
            redirect($_SERVER['HTTP_REFERER']);
          }

            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            if(!demo()){
                $this->db->where('address_id', $para2);
                $this->db->delete('address');
                $this->crud_model->set_category_data(0);
                recache();
            }
        } elseif ($para1 == 'multi_delete') {
            if(!demo()){
                $ids = explode('-', $param2);
                $this->crud_model->multi_delete('address', $ids);
            }
        } else if ($para1 == 'edit') {
            $page_data['brand_data'] = $this->db->get_where('address', array(
                'address_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/address_edit', $page_data);
        } elseif ($para1 == 'list') {
            $vid = $this->session->userdata('vendor_id');
            $this->db->where('uid', $vid);
            $this->db->order_by('address_id', 'asc');
            $page_data['all_brands'] = $this->db->get('address')->result_array();
            $this->load->view('back/vendor/address_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/address_add');
        } else {
            $page_data['page_name']  = "address";
            $page_data['all_brands'] = $this->db->get('address')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function brand($para1 = '', $para2 = '')
    {
        if ($para1 == 'do_add') {
            $type                = 'brand';
            $data['vid']        = $this->session->userdata('vendor_id');
            $data['title']        = $this->input->post('name');
            $data['icon']        = $this->input->post('fa_icon');
            $data['detail']        = $this->input->post('description');
            $data['sort']        = $this->input->post('sort');
            $this->db->insert('textg', $data);
            $id = $this->db->insert_id();
            if(isset($_FILES['img']['name']))
            {
            $path = $_FILES['img']['name'];
            $ext = 'webp';
            $data_banner['img']         = demo() ? '' : 'textg_'.$id.'.'.$ext;
            if(true){

                $r = $this->crud_model->file_up("img", "textg", $id, '', 'no', '.'.$ext);
                if($r)
                {
                    if($r)
                    {
                        $logo_id = $this->crud_model->add_img($r,array());
                        //array_push($dataInfo,$logo_id);
                        $r =$this->db->where('textg_id',$id)->update('textg',array('img'=>$logo_id));


                    }
                }
            }
            }
            // die($id);
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == "update") {
            // var_dump($para2);
            $data['vid']        = $this->session->userdata('vendor_id');
            $data['title']        = $this->input->post('name');
            $data['icon']        = $this->input->post('fa_icon');
            $data['detail']        = $this->input->post('description');
            $data['sort']        = $this->input->post('sort');
            $this->db->where('textg_id', $para2);
            $this->db->update('textg', $data);
            // var_dump($this->db->last_query());
           if(isset($_FILES['img']['name']))
            {
            $path = $_FILES['img']['name'];
            $ext = 'webp';
            $data_banner['img']         = demo() ? '' : 'textg_'.$para2.'.'.$ext;
            if(true){

                $r = $this->crud_model->file_up("img", "textg", $para2, '', 'no', '.'.$ext);
                var_dump($r);
                if($r)
                {
                    if($r)
                    {
                        $logo_id = $this->crud_model->add_img($r,array());
                        //array_push($dataInfo,$logo_id);
                        $r =$this->db->where('textg_id',$para2)->update('textg',array('img'=>$logo_id));


                    }
                }
            }
            }
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'delete') {
            if(!demo()){
                unlink("uploads/brand_image/" .$this->crud_model->get_type_name_by_id('brand',$para2,'logo'));
                $this->db->where('textg_id', $para2);
                $this->db->delete('textg');
                $this->crud_model->set_category_data(0);
                recache();
            }
        } elseif ($para1 == 'multi_delete') {
            if(!demo()){
                $ids = explode('-', $param2);
                $this->crud_model->multi_delete('brand', $ids);
            }
        } else if ($para1 == 'edit') {
            $page_data['brand_data'] = $this->db->get_where('textg', array(
                'textg_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/brand_edit', $page_data);
        } elseif ($para1 == 'list') {
            $vid = $this->session->userdata('vendor_id');
            $this->db->where('vid', $vid);
            $this->db->order_by('sort', 'asc');
            $page_data['all_brands'] = $this->db->get('textg')->result_array();
            $this->load->view('back/vendor/brand_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/brand_add');
        } else {
            $page_data['page_name']  = "brand";
            $page_data['all_brands'] = $this->db->get('textg')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    function get_state($id,$val = 0)
    {
        echo $this->crud_model->select_html('states','state','name','edit','select_state form-control demo-chosen-select required',$val,'country_id',$id,'select_state','single');
        exit();
    }
    function get_city($id,$val = 0)
    {
        echo $this->crud_model->select_html('cities','city','name','edit','select_city form-control demo-chosen-select required',$val,'state_id',$id,'select_city','single');
        exit();
    }

    /* index of the vendor. Default: Dashboard; On No Login Session: Back to login page. */
    public function csv_size($id)
    {
        $row = $this->db->where('product_id',$id)->get('product')->row();
        if($row)
        {
            $colum = 'eu_size';
            $g = $row->gender;
            $all = $this->db->get('standerd_sizes')->result_array();
            $size = array();
            foreach($all as $k=> $v)
            {
                $size[] = $v[$colum];
            }
            $this->db->where('product_id',$id)->update('product',array('color'=>json_encode($size)));

            // if($g == 'men')
            // {
            //     $colum = '';
            // }
            // if($g == 'women')
            // {
            //     $colum = '';
            // }
        }
    }

    public function getAmenties(){
        $cats = (isset($_REQUEST['cats'])?explode(',',$_REQUEST['cats']):array());
        if($_REQUEST['add'] == '1'){
      $amn = $this->db->like('name',$_REQUEST['value'])->where('status',1)->get('amenity')->result_array();
    //   var_dump($this->db->last_query());
    //   die();
         echo '<ul>';
          foreach($amn as $k => $row){
              if(in_array($row['catid'],$cats))
              {
              echo '<div class="col-sm-3>';
              echo '<li class="" onclick="selectamn('.$row['amenity_id'].');">'.$row['name'].'</li>'; 
              echo '</div>';
              }
          }
          echo '</ul>';
    }
        if($_REQUEST['select'] == '1' && isset($_REQUEST['pid']) && isset($_REQUEST['sid'])){
            $pid = $_REQUEST['pid'];
            $exp = $this->db->where('pid', $_REQUEST['pid'])->where('am_id',$_REQUEST['sid'])->get('product_to_amenity')->result_array();
            if(!$exp)
            {
            $in = array(
                    'pid'=> $pid,
                    'am_id' => $_REQUEST['sid']
                    );
            
                  $amn = $this->db->insert('product_to_amenity',$in);
                  if($amn)
                  {
                      echo 1;
                  }
            }

        }
        if($_REQUEST['add_to_table'] == '1'  && isset($_REQUEST['pid'])){
            $catid = isset($cats[0])?$cats[0]:0;
            $pid = $_REQUEST['pid'];
            
              $this->db->insert('amenity', array('name'  => $_REQUEST['value'],'catid'=>$catid));
              $id = $this->db->insert_id();
              var_dump($id);
              if($id)
              {
                  $in = array(
                    'pid'=> $pid,
                    'am_id' => $id
                    );
            
                  $amn = $this->db->insert('product_to_amenity',$in);
                  if($amn)
                  {
                      echo 1;
                  }
              }


        }
        if($_REQUEST['get'] == '1'  && isset($_REQUEST['pid'])){
             $exp = $this->db->where('pid', $_REQUEST['pid'])->get('product_to_amenity')->result_array();
                foreach($exp as $k=> $v)
                                {
                                    $amn = $this->db->where('amenity_id',$v['am_id'])->get('amenity')->row();
                                    ?>
                                <p  id="am_<?= $v['id'] ?>"><span class="sec_sev_close" onclick="delete_ament('<?= $v['id'] ?>')">x</span><?= $amn->name ?></p>
                                <?php
                                }

        }
        if($_REQUEST['del'] == '1'   && isset($_REQUEST['pid'])   && isset($_REQUEST['am_id'])){
             $in = array(
                    'pid'=> $_REQUEST['pid'],
                    'id' => $_REQUEST['am_id']
                    );
            
                  $amn = $this->db->where($in)->delete('product_to_amenity');
                  if($amn)
                  {
                      echo 1;
                  }

        }

    }
    public function gupload()
    {
        $img_id = 0;
        if(isset($_POST['img']))
        {
            $pid = (isset($_POST['pid']))?$_POST['pid']:0;
            $img = $_POST['img'];
            $base64string = $img;
    $uploadpath   = 'uploads/';
    $parts        = explode(";base64,", $base64string);
    $imageparts   = explode("image/", @$parts[0]);
    $imagetype    = $imageparts[1];
    $imagebase64  = base64_decode($parts[1]);
    $file         = $uploadpath . uniqid() . '.png';
    file_put_contents($file, $imagebase64);
            if($file)
                                            {
                                                $logo_id = $this->crud_model->add_img($file,$data);
                                                $data['img_id'] = $logo_id;
                                                $data['path'] = $file;
                                                $data['url'] = base_url($file);
                                                if($logo_id && $pid)
                                                {
                                                   $in = array(
                                                    'pid' =>$pid,
                                                    'img' =>$logo_id,
                                                   );
                                                   $this->db->insert('product_to_images',$in);
                                               }
                                               echo json_encode($data);
                                               exit();

                                            }
        }
        die("0");
    }
    public function main()
    {
        
        if ($this->session->userdata('vendor_login') == 'yes') {
            $page_data['vend'] = $this->db->where('vendor_id',$this->session->userdata('vendor_id'))->get('vendor')->row();
            $page_data['page_name'] = "dashboard";
            echo $this->load->view('back/index', $page_data,true);
        } else {
            $page_data['control'] = "vendor";
            echo $this->load->view('back/login',$page_data,true);
        }
    }
    public function index()
    {
        
        if ($this->session->userdata('vendor_login') == 'yes') {
            $page_data['vend'] = $this->db->where('vendor_id',$this->session->userdata('vendor_id'))->get('vendor')->row();
            $page_data['page_name'] = "dashboard";
            echo $this->load->view('back/index', $page_data,true);
        } else {
            $page_data['control'] = "vendor";
            echo $this->load->view('back/login',$page_data,true);
        }
    }
    /*Product slides add, edit, view, delete */
    function slides($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('slides')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == 'do_add') {
            $type                       = 'slides';
            $data['button_color']       = $this->input->post('color_button');
            $data['text_color']         = $this->input->post('color_text');
            $data['button_text']        = $this->input->post('button_text');
            $data['button_link']        = $this->input->post('button_link');
            $data['uploaded_by']        = 'vendor';
            $data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $this->db->insert('slides', $data);
            $id = $this->db->insert_id();
            if(!demo()){
                $this->crud_model->file_up("img", "slides", $id, '', '', '.jpg');
            }
            recache();
        } elseif ($para1 == "update") {
            $data['button_color']       = $this->input->post('color_button');
            $data['text_color']         = $this->input->post('color_text');
            $data['button_text']        = $this->input->post('button_text');
            $data['button_link']        = $this->input->post('button_link');
            $this->db->where('slides_id', $para2);
            $this->db->update('slides', $data);
            if(!demo()){
                $this->crud_model->file_up("img", "slides", $para2, '', '', '.jpg');
            }
            recache();
        } elseif ($para1 == 'delete') {
            if(!demo()){
                $this->crud_model->file_dlt('slides', $para2, '.jpg');
                $this->db->where('slides_id', $para2);
                $this->db->delete('slides');
                recache();
            }
        } elseif ($para1 == 'multi_delete') {
            if(!demo()){
                $ids = explode('-', $param2);
                $this->crud_model->multi_delete('slides', $ids);
            }
        } else if ($para1 == 'edit') {
            $page_data['slides_data'] = $this->db->get_where('slides', array(
                'slides_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/slides_edit', $page_data);
        } elseif ($para1 == 'list') {
            $this->db->order_by('slides_id', 'desc');
            $this->db->where('added_by', json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/vendor/slides_list', $page_data);
        }elseif ($para1 == 'slide_publish_set') {
            $slides_id = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('slides_id', $slides_id);
            $this->db->update('slides', $data);
            recache();
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/slides_add');
        } else {
            $page_data['page_name']  = "slides";
            $page_data['all_slidess'] = $this->db->get('slides')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    /* Login into vendor panel */
    function login($para1 = '')
    {
        if ($para1 == 'forget_form') {
            $page_data['control'] = 'vendor';
            $this->load->view('back/forget_password',$page_data);
        } else if ($para1 == 'forget') {
            if(demo()){
                echo "Action blocked in demo";exit;
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == FALSE)
            {
                echo validation_errors();
            }
            else
            {
                $query = $this->db->get_where('vendor', array(
                    'email' => $this->input->post('email')
                ));
                if ($query->num_rows() > 0) {
                    $vendor_id         = $query->row()->vendor_id;
                    $password         = substr(hash('sha512', rand()), 0, 12);
                    $data['password'] = sha1($password);
                    $this->db->where('vendor_id', $vendor_id);
                    $this->db->update('vendor', $data);
                    if ($this->email_model->password_reset_email('vendor', $vendor_id, $password)) {
                        echo 'email_sent';
                    } else {
                        echo 'email_not_sent';
                    }
                } else {
                    echo 'email_nay';
                }
            }
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                echo validation_errors();
            }
            else
            {
                $login_data = $this->db->get_where('vendor', array(
                    'email' => $this->input->post('email'),
                    'password' => sha1($this->input->post('password'))
                ));
                if ($login_data->num_rows() > 0) {
                    if($login_data->row()->status == 'approved' && $login_data->row()->email_ver){
                        if(!$login_data->row()->bpage)
                        {
                         $this->vendor_page($login_data->row()->vendor_id);
                        }
                        foreach ($login_data->result_array() as $row) {
                            $this->session->set_userdata('login', 'yes');
                            $this->session->set_userdata('vendor_login', 'yes');
                            $this->session->set_userdata('vendor_id', $row['vendor_id']);
                            $this->session->set_userdata('vendor_name', $row['display_name']);
                            $this->session->set_userdata('title', 'vendor');
                            echo 'lets_login';
                        }
                    } else {
                        if(!$login_data->row()->email_ver)
                        {
                         echo translate('email_unverify');   
                        }
                        else
                        {
                        echo translate('unapproved');
                        }
                    }
                } else {
                    echo translate('login_failed_vendor');
                }
            }
        }
    }


    /* Loging out from vendor panel */
    function logout()
    {
        $this->session->sess_destroy();
        custom_redirect(base_url() . 'vendor');
    }

    /*Product coupon add, edit, view, delete */
    function coupon($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('coupon')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == 'do_add') {
            $data['title'] = $this->input->post('title');
            $data['code'] = $this->input->post('code');
            $data['till'] = $this->input->post('till');
            $data['status'] = 'ok';
            $data['added_by'] = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['spec'] = json_encode(array(
                                'set_type'=>'product',
                                'set'=>json_encode($this->input->post('product')),
                                'discount_type'=>$this->input->post('discount_type'),
                                'discount_value'=>$this->input->post('discount_value'),
                                'shipping_free'=>$this->input->post('shipping_free')
                            ));
            $this->db->insert('coupon', $data);
        } else if ($para1 == 'edit') {
            $page_data['coupon_data'] = $this->db->get_where('coupon', array(
                'coupon_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/coupon_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['title'] = $this->input->post('title');
            $data['code'] = $this->input->post('code');
            $data['till'] = $this->input->post('till');
            $data['spec'] = json_encode(array(
                                'set_type'=>'product',
                                'set'=>json_encode($this->input->post('product')),
                                'discount_type'=>$this->input->post('discount_type'),
                                'discount_value'=>$this->input->post('discount_value'),
                                'shipping_free'=>$this->input->post('shipping_free')
                            ));
            $this->db->where('coupon_id', $para2);
            $this->db->update('coupon', $data);
        } elseif ($para1 == 'delete') {
            if(!demo()){
                $this->db->where('coupon_id', $para2);
                $this->db->delete('coupon');
            }

        } elseif ($para1 == 'list') {
            $this->db->order_by('coupon_id', 'desc');
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/vendor/coupon_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/coupon_add');
        } elseif ($para1 == 'publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('coupon_id', $product);
            $this->db->update('coupon', $data);
        } else {
            $page_data['page_name']      = "coupon";
            $page_data['all_coupons'] = $this->db->get('coupon')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    // ticket
    function ticket($para1 = "", $para2 = "", $para3 = "")
    {
        if (!$this->crud_model->vendor_permission('ticket')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == 'delete') {
            if(!demo()){
                $this->db->where('message_thread_id', $para2);
                $this->db->delete('message_thread');
            }
        } elseif ($para1 == 'list') {
            $id = $this->session->userdata('vendor_id');
            $this->db->order_by('message_thread_id', 'desc');
            $this->db->where('sender', '{"type":"seller","id":"' . $id . '"}');
            $this->db->or_where('reciever', '{"type":"seller","id":"' . $id . '"}');
            $page_data['message_threads'] = $this->db->get_where('message_thread')->result_array();
            $this->load->view('back/vendor/ticket_list', $page_data);
        } elseif ($para1 == 'reply') {
            $data['message'] = $this->input->post('reply');
            $data['time'] = time();
            $data['sender'] = json_encode(array('type'=>'seller','id'=>$this->session->userdata('vendor_id')));
            $data['message_thread_id']= $para2;
            $data['view_status']= json_encode(array('user_show'=>'no','seller_show'=>'ok'));
            $this->db->insert('message',$data);
        } elseif ($para1 == 'view') {
            $page_data['message_data'] = $this->db->get_where('message_thread', array(
                'message_thread_id' => $para2
            ))->result_array();
            $this->crud_model->message_to_vendor_viewed($para2,'seller');
            $page_data['tic']=$para2;
            $this->load->view('back/vendor/ticket_view', $page_data);
        } else if ($para1 == 'view_user') {
            $page_data['user_data'] = $this->db->get_where('user', array(
                'user_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/user_view', $page_data);
        } elseif ($para1 == 'reply_form') {
            $page_data['message_data'] = $this->db->get_where('message_thread', array(
                'message_thread_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/ticket_reply', $page_data);
        } else {
            $page_data['page_name']        = "ticket";
            $id = $this->session->userdata('vendor_id');
            $this->db->order_by('message_thread_id', 'desc');
            $this->db->where('sender', '{"type":"seller","id":"' . $id . '"}');
            $this->db->or_where('reciever', '{"type":"seller","id":"' . $id . '"}');
            $page_data['message_threads'] = $this->db->get_where('message_thread')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /*Product Sale Comparison Reports*/
    function report($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('report')) {
            redirect(base_url() . 'vendor');
        }
        $page_data['page_name'] = "report";
        $physical_system     =  $this->crud_model->get_type_name_by_id('general_settings','68','value');
        $digital_system      =  $this->crud_model->get_type_name_by_id('general_settings','69','value');
        if($physical_system !== 'ok' && $digital_system == 'ok'){
            $this->db->where('download','ok');
        }
        if($physical_system == 'ok' && $digital_system !== 'ok'){
            $this->db->where('download',NULL);
        }
        if($physical_system !== 'ok' && $digital_system !== 'ok'){
            $this->db->where('download','0');
        }
        $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
        $page_data['products']  = $this->db->get('product')->result_array();
        $this->load->view('back/index', $page_data);
    }

    /*Product Stock Comparison Reports*/
    function report_stock($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('report')) {
            redirect(base_url() . 'vendor');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings','68','value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        $page_data['page_name'] = "report_stock";
        if ($this->input->post('product')) {
            $page_data['product_name'] = $this->crud_model->get_type_name_by_id('product', $this->input->post('product'), 'title');
            $page_data['product']      = $this->input->post('product');
        }
        $this->load->view('back/index', $page_data);
    }

    /*Product Wish Comparison Reports*/
    function report_wish($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('report')) {
            redirect(base_url() . 'vendor');
        }
        $page_data['page_name'] = "report_wish";
        $this->load->view('back/index', $page_data);
    }
  function affliate($para1 = '', $para2 = '')
            {
                $page_data = array();
        if ($para1 == 'preview') {

            $page_data['res'] = $sing = $this->db->where('compain_id',$para2)->get('compain')->row();
            $html = '';
            if($sing->compain_type == 'text_compain')
            {
                $html = '<a href="'.$sing->link.'" target="_blank">
                    '.$sing->content.'
                </a>';
            }
            else if($sing->compain_type == 'banner_compain')
            {
                $html = '<a href="'.$sing->link.'" target="_blank">
                    <img src="'.$this->crud_model->get_img($sing->banner_img)->secure_url.'" />
                </a>';
            }
            else if($sing->compain_type == 'video_compain')
            {
                $html = '<iframe width="560" height="315" src="'.$sing->video_link.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><div style="display:table;clear:both;"></div><br><a style="-moz-box-shadow:inset 0 1px 0 0 #fff;-webkit-box-shadow:inset 0 1px 0 0 #fff;box-shadow:inset 0 1px 0 0 #fff;background:-webkit-gradient(linear,left top,left bottom,color-stop(.05,#f9f9f9),color-stop(1,#e9e9e9));background:-moz-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-webkit-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-o-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:-ms-linear-gradient(top,#f9f9f9 5%,#e9e9e9 100%);background:linear-gradient(to bottom,#f9f9f9 5%,#e9e9e9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#f9f9f9", endColorstr="#e9e9e9", GradientType=0);background-color:#f9f9f9;-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;border:1px solid #dcdcdc;display:inline-block;cursor:pointer;color:#666;font-family:Arial;font-size:15px;font-weight:700;padding:6px 24px;text-decoration:none;text-shadow:0 1px 0 #fff" href="'.$sing->link.'">'. $sing->title.'</a>';
            }
    ?>
    <?php
    echo $html;

            // die();
            $this->load->view('back/vendor/compain_edit', $page_data);
        }else if ($para1 == 'compain_do_add') {
          //  $check = $this->input->post('check');
              $this->db->where('product_id', $this->input->post('pro_id'));
            $this->db->update('product',array(
                'is_affiliate' => '1'
                ));
            $data = array(
                'compain_type' => $this->input->post('compain_type'),
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'video_link' => $this->input->post('video_link'),
                'link' =>$this->input->post('link'),
                'percentage' =>$this->input->post('percentage'),
                'added_by' => json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))),
                'pID' => $this->input->post('pro_id')
                );
            $this->db->insert('compain', $data);

            $id = $this->db->insert_id();
            $this->load->library('cloudinarylib');
            if($_FILES["banner_img"]['tmp_name']){
                    if(!demo()){
                        $path = 'uploads/compain'.time().'.jpg';
                        move_uploaded_file($_FILES["banner_img"]['tmp_name'], $path);
                        $data = \Cloudinary\Uploader::upload($path);
                        if(isset($data['public_id']))
                        {
                            $logo_id = $this->crud_model->add_img($path,$data);
                            if($logo_id)
                            {
                             $this->db->where('compain_id', $id);
                            $this->db->update('compain', array(
                                'banner_img' => $logo_id
                            ));
                           }
                        }
    //top_banner
                    }
                }
                recache();

        } elseif ($para1 == "update") {
            // die();
            $check = $this->input->post('check');
           $data = array(
                'compain_type' => $this->input->post('compain_type'),
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'video_link' => $this->input->post('video_link'),
                'link' =>$this->input->post('link'),
                'percentage' =>$this->input->post('percentage')
                );
            $this->db->where('compain_id', $para2);
            $this->db->update('compain', $data);
        } elseif ($para1 == 'delete') {

            if(!demo()){

                // if($this->crud_model->get_type_name_by_id('menu',$para2,'img'))
                // {
                //     unlink("uploads/bpkg_image/" .$this->crud_model->get_type_name_by_id('menu',$para2,'img'));
                // }
                // var_dump($para2);
                $this->db->where('compain_id', $para2);
                $this->db->delete('compain');
                recache();
            }
        } elseif ($para1 == 'multi_delete') {
            if(!demo()){
                $ids = explode('-', $param2);
                $this->crud_model->multi_delete('compain', $ids);
            }
        } else if ($para1 == 'edit') {
            $page_data['res'] =$this->db->get('compain')->result_array();
            $page_data['data'] = $this->db->get_where('compain', array(
                'compain_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/compain_edit', $page_data);
        } elseif ($para1 == 'list') {

            $this->db->order_by('compain_id', 'desc');
           $page_data['all_brands'] = $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))))->get('compain')->result_array();

            // $page_data['all_brands'] = $this->db->get('compain')->result_array();
            $this->load->view('back/vendor/compain_list', $page_data);
        } elseif ($para1 == 'add') {
            $data['res'] =$this->db->get('compain')->result_array();
            $page_data['page_name'] = 'compain_add';
            $this->load->view('back/index', $page_data);
        } elseif ($para1 == 'compain') {
            die("compain");
            $data['res'] =$this->db->get('menu')->result_array();
            $this->load->view('back/vendor/bpkg1_add', $data);
        } else {

            if(!isset($page_data['page_name']))
            {
            $page_data['page_name']  = "compain";
            }
            $page_data['all_brands'] = $this->db->get('compain')->result_array();

            $this->load->view('back/index', $page_data);
        }
    }
    /* Product add, edit, view, delete, stock increase, decrease, discount */
    function blog_cat(){
      $x =  $this->db->where('pcat', $_REQUEST['id'])->get('category')->result_array();

      echo '<select name="blogcat">';
      foreach($x as $k => $v){
          echo '<option '.$v['category_id'].'>'.$v['category_name'].'</option>';
      }
      echo '</select>';
    }
    function filter_table($col, $val)
    {
        if($col == 'model')
        {
            $r =$this->db->where('name',$val)->get('makes')->row();
            if(!$r)
            {
                $in  = array(
                    'name'=> $val,
                    'status'=> 0,
                    );
                $r = $this->db->insert('makes',$in);
            }
        }
        
    }
    public function createSlug($str, $delimiter = '-'){

    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;

}
    function create_slug($id)
    {
        if(isset($_GET['title']))
        {
            $title = $_GET['title'];
            $slug = $this->createSlug($title);
            if($id)
            {
                $this->db->where('product_id !=',$id);
            }
            $this->db->like('slug',$slug);
            $row = $this->db->get('product')->row();
            if($row)
            {
               $slug = $slug.$this->session->userdata('vendor_id'); 
            }
            if($id)
            {
                $this->db->where('product_id !=',$id);
            }
            $this->db->like('slug',$slug);
            $row = $this->db->get('product')->row();
            if($row)
            {
                $slug = '';
                
            }
            echo $slug;
            exit();
    
            
        }
    }
    function bpage_cat($page,$type='',$cat=0)
    {
        if($type == 'remove' && $page && $cat)
        {
            $pro = $this->db->where('product_id',$page)->get('product')->row();
        $breed = explode(',',$pro->category);
        foreach($breed as $k=> $v)
        {
            if($v == $cat)
            {
                unset($breed[$k]);
            }
        }
        $this->db->where('product_id',$page)->update('product',array('category'=>implode(',',$breed)));
        
        ?>
        <div class="cat_breed">
                    <ul>
                        <?php
                        $cat = array();
                        foreach($breed as $k=> $v)
                        {
                            $cat = $this->db->where('category_id',$v)->get('category')->row();
                            ?>
                            <p id="am_99"><span class="sec_sev_close" onclick="bpage_cat('<?= $page ?>','remove','<?= $v ?>');">x</span><?= $cat->category_name ?></p>
                            <?php
                        }
                        ?>


                    </ul>
                </div>
                <?php
        }
        if($type == 'select' && $page && $cat)
        {
            $pro = $this->db->where('product_id',$page)->get('product')->row();
        $breed = array_filter(explode(',',$pro->category));
        if(!in_array($cat,$breed))
        {
        $breed[] = $cat;
        }
        $this->db->where('product_id',$page)->update('product',array('category'=>implode(',',$breed)));
        
        ?>
        <input type="hidden" id="category" value="<?= implode(',',$breed) ?>" />
        <div class="cat_breed">
                    <ul>
                        <?php
                        $cat = array();
                        foreach($breed as $k=> $v)
                        {
                            $cat = $this->db->where('category_id',$v)->get('category')->row();
                            ?>
                            <p id="am_99"><span class="sec_sev_close" onclick="bpage_cat('<?= $page ?>','remove','<?= $v ?>');">x</span><?= $cat->category_name ?></p>
                            <?php
                        }
                        ?>


                    </ul>
                </div>
                
                <?php
        }
        if($type == 'get' && $page)
        {
            $pro = $this->db->where('product_id',$page)->get('product')->row();
        $breed = explode(',',$pro->category);
        
        ?>
        <input type="hidden" id="category" value="<?= implode(',',$breed) ?>" />
                    <div class="cat_breed col-md-12">
                    <ul>
                        <?php
                        $cat = array();
                        foreach($breed as $k=> $v)
                        {
                            $cat = $this->db->where('category_id',$v)->get('category')->row();
                            ?>
                            <p id="am_99"><span class="sec_sev_close" onclick="bpage_cat('<?= $page ?>','remove','<?= $v ?>');">x</span><?= $cat->category_name ?></p>
                            <?php
                        }
                        ?>


                    </ul>
                </div>
               
                <?php
        }
        elseif($type == 'child')
        {
            ?>
            
            <?php
            if(!$cat)
            {
                 $categories =json_decode($this->db->get_where('ui_settings',array('ui_settings_id' => 35))->row()->value, true);
                       $result=array();
                                            foreach($categories as $row){
                                                if($this->crud_model->if_publishable_category($row)){
                                                    $result[]=$row;
                                                }
                                            }
                    // var_dump($result);
                    // die();
                   $brands =  $this->db->where_in('category_id',$result)->get('category')->result_array();
            }
            else
            {
            $brands = $this->db->where('pcat',$cat)->get('category')->result_array();
            }
            foreach($brands as $k=>$v){
                if(true)
                {
            ?>
            
                <div class="col-md-4 col-sm-12 col-xs-12 <?= ($product_data->category == $v['category_id'])?"active":"" ?>" >
                    <a href="javascript:void(0);"><div class="flip-card ">
                  <div class="flip-card-inner">
                    <div class="flip-card-front ">
                        <div>
                            <p><?= $v['category_name'];?></p>
                        </div>
                        <div class="cat_icons" style="margin-top: 40px;">
                            <?php
                            $childs = $this->db->where('pcat',$v['category_id'])->get('category')->result_array();
                            if($childs)
                            {
                            ?>
                            <i  onclick="bpage_cat('<?= $page ?>','child','<?= $v['category_id']; ?>');" class="fa-solid fa-children"></i>
                            <?php
                            }
                            ?>
                            <i  onclick="bpage_cat('<?= $page ?>','select','<?= $v['category_id']; ?>');" class="fa-solid fa-check-to-slot"></i>
                        </div>
                    </div>
                    
                  </div>
                </div>
                </a>
                </div>
                <?php
                }
            }
        }
        
    }
    function column3($type,$id)
    {
        if($type == 'add')
        {
            echo $this->load->view('sections/add_column3.php',array('pid'=>$id),true);
            exit();
        }
        die('OKK');
    }
    function save_product($id)
    {
        if(!$id)
        {
            $data = array();
            $id = $vendor_id->id;
$vendor = $this->db->where('vendor_id', $this->session->userdata('vendor_id'))->get('vendor')->row_array();

// get product
$n = $this->db->where('product_id', $vendor['bpage'])->where('is_bpage', 1)->get('product')->row_array();
            $data['title'] = $this->input->post('title');
            
            $data['added_by']             = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['vendor_id'] =$vid = $this->session->userdata('vendor_id');
            $vendor = $this->db->where('vendor_id',$vid)->get('vendor')->row();
            $country = $this->db->where('countries_id',$vendor->country)->get('countries')->row();
            $data['country'] = $vendor->country;
            $data['country'] = $vendor->country;
            $data['bussniuss_email'] = $vendor->email;
            $data['bussniuss_phone'] = $vendor->phone;
            $data['curr'] = $country->currency_symbol;
            $data['slog'] = $this->input->post('slog');
            $data['slug'] = $this->input->post('slug');
            $data['seo_description'] = $this->input->post('seo_description');
            $data['seo_title'] = $this->input->post('seo_title');
            $data['module'] = $this->input->post('module');
            $data['tag'] = $this->input->post('tag');
            $data['comp_cover'] = $this->input->post('comp_cover');
            $data['summery'] = $this->input->post('summery');
            $data['status'] = 'draft';
            $data['lat'] = $n['lat'];
            $data['lng'] = $n['lng'];
            $data['create_at'] = date('Y-m-d H:i:s');
            $mod = $this->db->where('id',$this->input->post('module'))->get('modules')->row();
            if($mod)
            {
                $data['category'] = ($mod->sub_category)?$mod->category.','.$mod->sub_category:$mod->category;
            }
            
            $this->db->insert('product',$data);
            $id = $this->db->insert_id();
            if($id)
            {
                echo json_encode(array('rurl'=>base_url('vendor/product/edit/'.$id)));
            }
        }
        else
        {
            
            $old = $this->db->where('product_id',$id)->get('product')->row_array();
            
            
            $cols = $this->db->list_fields('product');
            $this->load->helper(array('form', 'url'));

            $this->load->library('form_validation');
            $errm = '';

            foreach($cols as $k=> $v)
            {
                $err = $this->db->where('column',$v)->get('product_validation')->row();
                if($err)
                {
                    $this->form_validation->set_rules($err->column, translate($err->column), $err->type);
                }
            }
            // var_dump($_POST['description']);
            // if(isset($_POST['description']) && strlen(strip_tags($_POST['description'])) < 300)
            // {
            //     $errm = 'Detail must be greater then 300 chracters';
            // }

                if ($this->form_validation->run() == FALSE || $errm)
                {
                        if($errm)
                        {
                            echo $errm;
                        }
                        else
                        {
                        echo validation_errors();
                        }
                }
                else
                {
                    
                    $options = $up = array();
                    if($old['status'] == 'draft')
                    {
                        $up['posted_on'] = date('Y-m-d H:i:s');
                    }
                    else
                    {
                        $up['update_at'] = date('Y-m-d H:i:s');
                    }
                    
                    $up['status'] = 'ok';
                    $data['added_by']             = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['vendor_id'] =$vid = $this->session->userdata('vendor_id');
            $vendor = $this->db->where('vendor_id',$vid)->get('vendor')->row();
            $country = $this->db->where('countries_id',$vendor->country)->get('countries')->row();
            $up['country'] = $vendor->country;
            $up['curr'] = $country->currency_symbol;
                    $up['checkbox_xtra_fields'] = json_encode($this->input->post('checkboxinfo'));
                    $js = array('feature','buttons','amenities','text','etra_content','enable_checks','social_media','txt_gal','about_buttons','about_feature','tabs');
                    $up['info_button']        = $this->input->post('button_txt');
            $up['button_url']         = $this->input->post('button_url');
                    foreach($js as $k=> $v)
                    {
                        if(!isset($_POST[$v]))
                        {
                            $_POST[$v] = array();
                        }
                    }
                    foreach($cols as $k=> $v)
                    {
                        if(isset($_POST[$v]))
                        {
                            if(in_array($v,$js))
                            {
                                
                            $up[$v] = json_encode($this->input->post($v));
                            }
                            else
                            $up[$v] = $this->input->post($v);
                        }
                    }
                            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            if($choice_titles && count($choice_titles ) > 0){
                foreach ($choice_titles as $i => $row) {
                    $choice_options         = $this->input->post('op_set'.$choice_no[$i]);
                    $options[]              =   array(
                                                    'no' => $choice_no[$i],
                                                    'title' => $choice_titles[$i],
                                                    'name' => 'choice_'.$choice_no[$i],
                                                    'type' => $choice_types[$i],
                                                    'option' => $choice_options
                                                );
                }
            }
            $up['options']            = json_encode($options);
                    $additional_value = array();
                    $additional_value['name']   = $this->input->post('ad_field_names_custom');
            $additional_value['value']   = $this->input->post('ad_field_values_custom');
            $up['additional_fields_new'] = json_encode($additional_value);
            $additional_fields = array();
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $up['additional_fields']  = json_encode($additional_fields);
                    $up['number_of_column']        = $num  = $this->input->post('number_of_column');
        $rtra = $this->input->post('etra_content');
        // var_dump($rtra);
        $up['description'] = $_POST['description'];
                    $r = $this->db->where('product_id',$id)->update('product',$up);
                   if($r)
                   {
                       
                $am_arr = array();
                                $exp = $this->db->where('pid', $id)->get('product_to_amenity')->result_array();
                foreach($exp as $k=> $v)
                                {
                                    $amn = $this->db->where('amenity_id',$v['am_id'])->get('amenity')->row();
                                    $am_arr[] = $amn->name;
                                }
                                $this->db->where('product_id',$id)->update('product',array('amenities'=>implode(',',$am_arr)));
                                            $additional_value['name'] = $nm   = $this->input->post('ad_field_names_custom');
            $additional_value['value']  = $vls = $this->input->post('ad_field_values_custom');
                    $this->db->where('pid',$id)->delete('product_to_accordion');
                foreach($additional_value['name'] as $k=> $v)
                {
                    $in = array(
                        'pid'=>$id,
                        'title' => $nm[$k],
                        'detail' => $vls[$k]
                        );
                    $r = $this->db->insert('product_to_accordion',$in);
                }
                update_col($id);
                   }
                   else
                   {
                       echo 'Somthing working wrong';
                   }
                }
        }
    }
    function product($para1 = '', $para2 = '', $para3 = '')
    {



        $list_type = '';
        //is_job

        if($this->session->userdata('admin_login') != 'yes')
        {
        if (!$this->crud_model->vendor_permission('product') ) {
            redirect(base_url() . 'vendor');
        }
        }
        if ($this->crud_model->get_type_name_by_id('general_settings','68','value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        $page_data = array();
            if(isset($_GET['module']))
            {
                $module = $_GET['module'];
                $mod = $this->db->where('id',$module)->get('modules')->row();
                if(isset($mod->sub_category) && $mod->sub_category)
                {
                    $mod->category = $mod->sub_category;
                }
                if(isset($mod->category))
                {
                    $cat = $this->db->where('category_id',$mod->category)->get('category')->row();
                    if($cat)
                    {
                        $page_data['mod'] = $mod;
                        $page_data['mod_cat'] = $cat;
                    }
                }
            }

        if ($para1 == 'do_add') {
            // var_dump($_POST);
            // die();
            $option              = $this->input->post('option');
            $options = array();
            $num_of_imgs = 0;
                $cat = $this->input->post('category');
                $data = array();
               $cat2 = explode(',', $cat);
               $data['module']               = $this->input->post('module');
               $data['specification']               = $this->input->post('specification');
                $data['warranty_info']               = $this->input->post('warranty_info');
                $data['shipping_info']               = $this->input->post('shipping_info');
                $data['seller_profile']               = $this->input->post('seller_profile');
             if (in_array($this->config->item('car_cat'), $cat2))
                  {
                $data['is_car'] = 1;
                
                
                $data['checkbox_h']               = $this->input->post('checkbox_h');
                $data['accor_h']               = $this->input->post('accor_h');
                $data['accor_p']               = $this->input->post('accor_p');
                $data['gal_h']               = $this->input->post('gal_h');
                $data['gal_d']               = $this->input->post('gal_d');
                $data['make']               = $this->input->post('make');
                $data['model']              = $this->input->post('model');
                $data['seats']              = $this->input->post('no_of_seats');
                $data['pid']                = $this->input->post('car_id');
                $data['purchase_price']     = $this->input->post('carprice');
                $data['sale_price']     = $this->input->post('sale_price');
                $data['posted_date']        = $this->input->post('cardate_posted');
                $data['about_desc']        = $this->input->post('about_us');
                $data['mot']        = $this->input->post('about_us');
                $data['car_condition']        = $this->input->post('carcondition');
                $data['distance_covered']        = $this->input->post('distance');
                $data['uom']        = $this->input->post('cardistance');
                $data['xtra_car']        = $this->input->post('xtraacarr');
                $data['purchase_requirements']        = $this->input->post('car_require');
                $data['fuel']        = $this->input->post('fuel');
                $data['transmission']        = $this->input->post('transmission');
                $data['currency']        = $this->input->post('currency');
                  }
                  if (in_array("808", $cat2))
                  {
                $data['is_property'] = 1;
                $data['propert_type']               = $this->input->post('property_type');
                $data['no_of_bedroom']              = $this->input->post('no_of_bedrooms');
                $data['available_from_date']        = $this->input->post('available_from_date');
                  }
                  if (in_array("917", $cat2) || isset($_REQUEST['is_event']))
                  {
                $data['is_event'] = 1;
                $data['event_date']               = $this->input->post('event_date');
                $data['event_type']               = $this->input->post('event_type');
                $data['age_restriction_event']    = $this->input->post('event_age_restriction');
                  }
                  if (in_array("78", $cat2) || isset($_REQUEST['is_job']))
                  {
                $data['is_job'] = 1;
                $data['job_hours']               = $this->input->post('select_job_hours');
                $data['job_type']               = $this->input->post('select_job_type');
                $data['pid']               = $this->input->post('job_id');
                $data['posted_date'] = $this->input->post('date');
                  }

            if(isset($_REQUEST['is_blog'])){
                $data['is_blog'] = 1;
                $data['author_name'] = $this->input->post('author');
                $data['slug']                 = $slug =  $this->input->post('slug');
                $data['create_at'] = $this->input->post('date');
            }

            if(isset($_REQUEST['is_product']))
            {
                // var_dump(slugify($this->input->post('title')));
            $data['is_product']          = 1;
            $data['down_text']      = $this->input->post('down_text');
            $data['uper_text']      = $this->input->post('uper_text');
            $data['current_stock']      = $this->input->post('current_stock');
            $data['sale_price']      = $this->input->post('sale_price');
            $data['product_link']      = $this->input->post('product_link');
            // $data['slug']               = slugify($this->input->post('title'));
            }

            // die();
            $data['discip_heading']          = $this->input->post('discip_heading');
            $data['seo_title']          = $this->input->post('seo_title');
            $data['seo_description']    = $this->input->post('seo_description');
            $data['title']              = $this->input->post('title');
            $data['slogan']               = $this->input->post('slogan');
            $data['slog']                = $this->input->post('slog');
            $data['main_heading']         = $this->input->post('main_heading');
            $data['bussniuss_email']      = $this->input->post('bussniuss_email');
            $data['bussniuss_phone']      = $this->input->post('bussniuss_phone');
            $data['whatsapp_number']      = $this->input->post('whatsapp_number');
            $data['feature']              = ($this->input->post('feature'))?json_encode($this->input->post('feature')):'';
            $data['buttons']              = ($this->input->post('buttons'))?json_encode($this->input->post('buttons')):'';
            $data['category']             = $this->input->post('category');
                    if(isset($_POST['description']))
            $data['description']        = $_POST['description'];
            $data['sub_category']         = $this->input->post('sub_category');
            $data['sale_price']           = (isset($_POST['fields']['sale_price'])?$_POST['fields']['sale_price']:'0');
            $data['whatsapp_number']      = $this->input->post('whatsapp_number');
            $data['lat']                  = $this->input->post('lat');
            $data['lng']                  = $this->input->post('lng');
            $data['warehouse_id']         = $this->input->post('warehouse');
            // $data['listing_amenities']    = json_encode($this->input->post('listingamenities'));
             $additional_value['name']    = json_encode($this->input->post('ad_field_names_custom'));
            $additional_value['value']    = json_encode($this->input->post('ad_field_values_custom'));
            $data['checkbox_xtra_fields'] = json_encode($this->input->post('checkboxinfo'));
            $data['additional_fields_new']= json_encode($additional_value);
            $data['add_timestamp']        = time();
            $data['download']             = NULL;
            $data['featured']             = 'no';
            $data['vendor_featured']      = 'no';
            $data['is_bundle']            = 'no';
            $data['status']               = 'ok';
            $data['rating_user']          = '[]';
            $data['tag']                  = $this->input->post('tag');
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            if($choice_titles && count($choice_titles ) > 0){
                foreach ($choice_titles as $i => $row) {
                    $choice_options         = $this->input->post('op_set'.$choice_no[$i]);
                    $options[]              =   array(
                                                    'no' => $choice_no[$i],
                                                    'title' => $choice_titles[$i],
                                                    'name' => 'choice_'.$choice_no[$i],
                                                    'type' => $choice_types[$i],
                                                    'option' => $choice_options
                                                );
                }
            }
            $data['options']            = json_encode($options);
            $data['num_of_imgs']          = $num_of_imgs;
            $data['current_stock']        = $this->input->post('current_stock');
            $fil_col = $this->db->where('is_filter',1)->get('list_fields')->result_array();
            foreach($fil_col as $k=> $v)
            {
                $col  = $v['tbl_col'];
                if(isset($_POST[$col]) && $_POST[$col])
                {
                    $this->filter_table($col,$_POST[$col]);
                $data[$col] = $_POST[$col];
                }
            }
            $data['front_image']          = 0;
            $additional_fields['name']    = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value']   = json_encode($this->input->post('ad_field_values'));
            /*--extra field---*/
            $data['color']                = json_encode($this->input->post('color'));
            $additional_fields1 = array();
            $additional_fields1['name']    = json_encode($this->input->post('ad_field_names'));
            $additional_fields1['value']   = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']    = json_encode($additional_fields);
            $data['additional_fields_new']    = json_encode($additional_fields1);
            $data['brand']                = $this->input->post('brand');
             $data['city']                  = $this->input->post('city');
            $data['unit']                 = $this->input->post('unit');
            if($this->input->post('slug'))
            {
            $data['slug']                 = $slug =  $this->input->post('slug');
            }
            else
            $data['slug']               = $slug =  slugify($this->input->post('title'));

            $choice_titles                = $this->input->post('op_title');
            $choice_types                 = $this->input->post('op_type');
            $choice_no                    = $this->input->post('op_no');

            $data['added_by']             = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['vendor_id'] =$vid = $this->session->userdata('vendor_id');
            $vendor = $this->db->where('vendor_id',$vid)->get('vendor')->row();
            $country = $this->db->where('countries_id',$vendor->country)->get('countries')->row();
            $data['country'] = $vendor->country;
            $data['country'] = $vendor->country;
            $data['curr'] = $country->currency_symbol;

            if (true) {
                $slug_ver = $this->db->where('slug',$slug)->get('product')->row();
                if($slug_ver)
                {
                    echo "Slug already exist!";
                    exit();
                }
                
               $this->db->insert('product', $data);
               $id = $this->db->insert_id();
                if($id){
                    
                            $additional_value['name'] = $nm   = $this->input->post('ad_field_names_custom');
            $additional_value['value']  = $vls = $this->input->post('ad_field_values_custom');
                    $this->db->where('pid',$id)->delete('product_to_accordion');
                foreach($additional_value['name'] as $k=> $v)
                {
                    $in = array(
                        'pid'=>$id,
                        'title' => $nm[$k],
                        'detail' => preg_replace('/[^A-Za-z0-9\-]/', '', $vls[$k])
                        );
                    $r = $this->db->insert('product_to_accordion',$in);
                }
                     if(is_array($this->input->post('amenities'))){
                $arr = $this->input->post('amenities');
                   $this->add_product_amenities($para1,$id,$arr);
                    }else{
                     $string = $this->input->post('amenities');
                     $arr =  explode(',', $string);
                     $this->add_product_amenities($para1,$id,$arr);
                    }
                    update_col($id);
                            foreach($_POST['fields'] as $k=> $v)
                            {
                                // $r = update_product_meta($id,$k, $v);
                            }

                            // var_dump($id);
                            if(isset($_REQUEST['rand_id']) && !empty($_REQUEST['rand_id']))
                            {
                                $am_arr = array();
                                $exp = $this->db->where('pid', $_REQUEST['rand_id'])->get('product_to_amenity')->result_array();
                foreach($exp as $k=> $v)
                                {
                                    $amn = $this->db->where('amenity_id',$v['am_id'])->get('amenity')->row();
                                    $am_arr[] = $amn->name;
                                }
                                $this->db->where('product_id',$id)->update('product',array('amenities'=>implode(',',$am_arr)));
                                $up = array('pid'=>$id);
                                $ret = $this->db->where('pid',$_REQUEST['rand_id'])->update('product_to_images', $up);
                                $ret = $this->db->where('pid',$_REQUEST['rand_id'])->update('product_to_amenity', $up);
                                
                            }
                            $this->benchmark->mark_time();
                            $this->load->library('cloudinarylib');
                            if($id && isset($_FILES['sneakerimg']['name']) && !empty($_FILES['sneakerimg']['name'])){

                                $sneakerimg = $this->crud_model->file_up("sneakerimg", "product", 'sneakerimg_'.time());
                            // $data = \Cloudinary\Uploader::upload($sneakerimg);
                            if($sneakerimg)
                            {
                        $logo_id = $this->crud_model->add_img($sneakerimg,array());
                        // array_push($dataInfo,$logo_id);
                        $r =$this->db->where('product_id',$id)->update('product',array('comp_logo'=>$logo_id));
                        // var_dump($r);


                        }

                        }
                        if($id && isset($_FILES['sideimg']['name']) && !empty($_FILES['sideimg']['name'])){

                            $sneakerimg = $this->crud_model->file_up("sideimg", "product", 'sideimg_'.time());
                            // $data = \Cloudinary\Uploader::upload($sneakerimg);
                        if($sneakerimg)
                        {
                            $logo_id = $this->crud_model->add_img($sneakerimg,$data);
                            $this->db->where('product_id',$id)->update('product',array('comp_cover'=>$logo_id));


                        }

                        }

                } else {
                    var_dump($this->db->last_query());
                    die();
                }
            }
            elseif ($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'yes') {
                $this->db->insert('product', $data);
                $id = $this->db->insert_id();
                if($id)
                {
                        $additional_value['name'] = $nm   = $this->input->post('ad_field_names_custom');
            $additional_value['value']  = $vls = $this->input->post('ad_field_values_custom');
                    $this->db->where('pid',$id)->delete('product_to_accordion');
                foreach($additional_value['name'] as $k=> $v)
                {
                    $in = array(
                        'pid'=>$id,
                        'title' => $nm[$k],
                        'detail' => $vls[$k]
                        );
                    $r = $this->db->insert('product_to_accordion',$in);
                    var_dump($r);
                }
                }
                // $this->crud_model->_set_variation($id);
                $this->benchmark->mark_time();
                if(!demo()){
                    $this->crud_model->file_up("images", "product", $id, 'multi');
                }
            }
            $this->crud_model->set_category_data(0);
            recache();
        }
        else if ($para1 == "update")
        {
            

            $option              = $this->input->post('option');
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $data = array();
            $pid =$this->db->where('product_id', $para2)->get('product')->row_array();
            $this->crud_model->set_amenities($pid['product_id']);
            $data['vendor_id'] =$vid = $this->session->userdata('vendor_id');
            $vendor = $this->db->where('vendor_id',$vid)->get('vendor')->row();
            $country = $this->db->where('countries_id',$vendor->country)->get('countries')->row();
            $data['curr'] = $country->currency_symbol;
            $data['tag']    = $this->input->post('tag');
            
            $data['meta_title']               = $this->input->post('meta_title');
            $data['meta_title']               = $this->input->post('meta_title');
            $data['specification']               = $this->input->post('specification');
                $data['warranty_info']               = $this->input->post('warranty_info');
                $data['shipping_info']               = $this->input->post('shipping_info');
                $data['seller_profile']               = $this->input->post('seller_profile');

            //   var_dump($this->db->last_query());
            $v_id = $pid['added_by'];
            $vid = json_decode($v_id);
            
        // $vendor = array();
        //  $this->db->where('vendor_id', $vid->id);
        //     $this->db->update('vendor', $vendor);
            $num                        = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $download                   = $this->crud_model->get_type_name_by_id('product', $para2, 'download');
                     $cat = $this->input->post('category');
               $cat2 = explode(',', $cat);
             if (in_array("807", $cat2))
                  {
                $data['is_car'] = 1;
                $data['discip_heading']          = $this->input->post('discip_heading');
                
                $data['make']               = $this->input->post('make');
                $data['specification']               = $this->input->post('specification');
                $data['warranty_info']               = $this->input->post('warranty_info');
                $data['shipping_info']               = $this->input->post('shipping_info');
                $data['seller_profile']               = $this->input->post('seller_profile');
                $data['model']              = $this->input->post('model');
                $data['seats']              = $this->input->post('no_of_seats');
                $data['pid']                = $this->input->post('car_id');
                $data['purchase_price']     = $_POST['carprice'];
                $data['sale_price']     = $_POST['sale_price'];
                
                $data['posted_date']        = $this->input->post('cardate_posted');
                $data['about_desc']        = $this->input->post('about_us');
                $data['mot']        = $this->input->post('about_us');
                $data['car_condition']        = $this->input->post('carcondition');
                $data['distance_covered']        = $this->input->post('distance');
                $data['uom']        = $this->input->post('cardistance');
                $data['xtra_car']        = $this->input->post('xtraacarr');
                $data['purchase_requirements']        = $this->input->post('car_require');
                $data['fuel']        = $this->input->post('fuel');
                $data['transmission']        = $this->input->post('transmission');
                $data['currency']        = $this->input->post('currency');
                  }
                  if (in_array("808", $cat2))
                  {
                $data['is_property'] = 1;
                $data['propert_type']               = $this->input->post('property_type');   $data['available_from_date']        = $this->input->post('available_from_date');
                $data['no_of_bedroom']               = $this->input->post('no_of_bedrooms');
                  }
                  if (in_array("917", $cat2) || isset($_REQUEST['is_event']))
                  {
                $data['is_event'] = 1;
                $data['event_date']               = $this->input->post('event_date');
                $data['event_type']               = $this->input->post('event_type');
                $data['age_restriction_event']    = $this->input->post('event_age_restriction');
                  }
                  if (in_array("78", $cat2) || isset($_REQUEST['is_job']))
                  {
                $data['is_job'] = 1;
                $data['job_hours']               = $this->input->post('select_job_hours');
                $data['job_type']               = $this->input->post('select_job_type');
                $data['pid']               = $this->input->post('job_id');
                $data['posted_date'] = $this->input->post('date');
                  }

          if(isset($_REQUEST['is_blog'])){
                $data['is_blog'] = 1;
                $data['slug']                 = $slug =  $this->input->post('slug');
                $data['author_name'] = $this->input->post('author');
                $data['posted_date'] = date('Y-m-d H:i:s');
            }
            
            

            $data['seo_title']          = $this->input->post('seo_title');
            $data['seo_description']    = $this->input->post('seo_description');
            $data['down_text']      = $this->input->post('down_text');
            $data['uper_text']      = $this->input->post('uper_text');
            $data['checkbox_h']               = $this->input->post('checkbox_h');
                $data['accor_h']               = $this->input->post('accor_h');
                $data['accor_p']               = $this->input->post('accor_p');
                $data['gal_h']               = $this->input->post('gal_h');
                $data['gal_d']               = $this->input->post('gal_d');
        $data['etra_content']          = json_encode($this->input->post('etra_content'));
        $data['number_of_column']        = $num  = $this->input->post('number_of_column');
        $rtra = $this->input->post('etra_content');
        for($i = 0;$i<=$num;$i++)
        {
            
            if(isset($rtra[$i]['type']) && $rtra[$i]['type'] == 'img')
            {
                $im = 'img_col'.$i;
                if(isset($_FILES[$im]['name']) && $_FILES[$im]['name'])
                {
                    $path = 'uploads/product_image/'.time().$i.'.webp';
                    if(file_exists($path)) {
    chmod($path,0755); //Change the file permissions if allowed
    $r = unlink($path); //remove the file
    // var_dump($r);
    // var_dump($path);
}


                         move_uploaded_file($_FILES[$im]['tmp_name'], $path);
                    $rtra[$i]['data'] = $path;
                }
            }
            
        }
        $data['etra_content']          = json_encode($rtra);
        $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            if($choice_titles && count($choice_titles ) > 0){
                foreach ($choice_titles as $i => $row) {
                    $choice_options         = $this->input->post('op_set'.$choice_no[$i]);
                    $options[]              =   array(
                                                    'no' => $choice_no[$i],
                                                    'title' => $choice_titles[$i],
                                                    'name' => 'choice_'.$choice_no[$i],
                                                    'type' => $choice_types[$i],
                                                    'option' => $choice_options
                                                );
                }
            }
            $data['options']            = json_encode($options);
        $fil_col = $this->db->where('is_filter',1)->get('list_fields')->result_array();
            foreach($fil_col as $k=> $v)
            {
                $col  = $v['tbl_col'];
                if(isset($_POST[$col]) && $_POST[$col])
                {
                    $this->filter_table($col,$_POST[$col]);
                $data[$col] = $_POST[$col];
                }
            }
            
        //extra info end
            $data['seo_description']    = $this->input->post('seo_description');
            $data['title']              = $this->input->post('title');
            $data['slogan']               = $this->input->post('slogan');
            $data['main_heading']       = $this->input->post('main_heading');
            $data['bussniuss_email']    = $this->input->post('bussniuss_email');
            $data['bussniuss_phone']    = $this->input->post('bussniuss_phone');
            $data['whatsapp_number']    = $this->input->post('whatsapp_number');
            $data['enable_checks']      = json_encode($this->input->post('checks'));
            $data['feature']            = ($this->input->post('feature'))?json_encode($this->input->post('feature')):'';
            $data['buttons']            = ($this->input->post('buttons'))?json_encode($this->input->post('buttons')):'';
            $data['text']               = ($this->input->post('text'))?json_encode($this->input->post('text')):'';
            $data['lat']                = $this->input->post('lat');
            $data['lng']                = $this->input->post('lng');
            $data['warehouse_id']         = $this->input->post('warehouse');
            $data['category']           = $this->input->post('category');
            if(isset($_POST['description']))
            $data['description']        = $_POST['description'];
            $data['sub_category']       = $this->input->post('sub_category');
            if(!isset($data['sale_price']))
            $data['sale_price']           = (isset($_POST['fields']['sale_price'])?$_POST['fields']['sale_price']:'0');
            if(!isset($data['purchase_price']) || !$data['purchase_price'])
            $data['purchase_price']     = $this->input->post('purchase_price');
            $data['tax']                = $this->input->post('tax');
            $data['slog']                = $this->input->post('slog');
            $data['default_tab']        = $this->input->post('default_tab');
            $data['discount']           = $this->input->post('discount');
            $data['discount_type']      = $this->input->post('discount_type');
            $data['tax_type']           = $this->input->post('tax_type');
            $data['checkbox_xtra_fields'] = json_encode($_POST['checkboxinfo']);
            $data['shipping_cost']      = $this->input->post('shipping_cost');
            $data['about_title']        = $this->input->post('about_title');
            $data['about_desc']         = $this->input->post('about_description');
            $data['extra_section_heading']      = $this->input->post('extra_section_heading');
            $data['about_address']      = $this->input->post('about_address');
            // $data['listing_amenities']    = json_encode($this->input->post('listingamenities'));
            $data['color']              = json_encode($option);
            $data['num_of_imgs']        = $num + $num_of_imgs;
            $data['front_image']        = 0;
            
            $additional_value['name']   = $this->input->post('ad_field_names_custom');
            $additional_value['value']   = $this->input->post('ad_field_values_custom');
            $data['additional_fields_new'] = json_encode($additional_value);
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']  = json_encode($additional_fields);
            $data['brand']              = $this->input->post('brand');
            $data['unit']               = $this->input->post('unit');
            $data['info_head']          = $this->input->post('info_head');
            $data['info_desc']          = $_POST['info_desc'];
            $data['gallery_text']        = $this->input->post('gdesc');
            $data['gallery_lable']      = $this->input->post('gtitle');
            $data['gdesc']             = $this->input->post('gdesc1');
            $data['gtitle']             = $this->input->post('gtitle1');
            
            
                if($this->input->post('slug'))
            $data['slug']                 = $this->input->post('slug');
            else
            $data['slug']               = slugify($this->input->post('title'));
            
            $data['cats']               = $this->input->post('cats');
            $data['info_button']        = $this->input->post('button_txt');
            $data['button_url']         = $this->input->post('button_url');
            if(is_array($this->input->post('amenities'))){
                $arr = $this->input->post('amenities');
                    $data['amenities']  =  implode(",",$arr);
            }else{
            $data['amenities']         = $this->input->post('amenities');
            }
            $data['openig_time']         = $this->input->post('openig_time');
            $data['closing_time']         = $this->input->post('closing_time');
            $data['social_media']       = json_encode($this->input->post('social'));
            $data['name']                  = $this->input->post('name1');
            $data['pemail']                  = $this->input->post('email1');
            $data['whatsapp_number']                  = $this->input->post('whatsapp_number');
            $data['ephonen']                  = $this->input->post('pphone');
            $data['bussniuss_phone']                  = $this->input->post('pphone');
            $data['amen_check']                  = $this->input->post('checkamenities');
            $data['address1']              = $this->input->post('address');
            $data['address2']              = $this->input->post('address2');
            $data['country']               = $this->input->post('country');
            $data['city']                  = $this->input->post('city');
            $data['state']                 = $this->input->post('state');
            $data['zip']                   = $this->input->post('zip_code');
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            $data['current_stock']                = $this->input->post('current_stock');
            /*--extra field---*/
            $data['color']              = json_encode($this->input->post('color'));
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']  = json_encode($additional_fields);
            $data['brand']              = $this->input->post('brand');
            $data['unit']               = $this->input->post('unit');
            $choice_titles              = $this->input->post('op_title');
            $choice_types               = $this->input->post('op_type');
            $choice_no                  = $this->input->post('op_no');
            $data['color']              = json_encode($this->input->post('color'));

            


            $this->db->where('product_id', $para2);
            
            $this->db->update('product', $data);
            
            if($para2)
            {
                $additional_value['name'] = $nm   = $this->input->post('ad_field_names_custom');
            $additional_value['value']  = $vls = $this->input->post('ad_field_values_custom');
                $this->db->where('pid',$para2)->delete('product_to_accordion');
                foreach($additional_value['name'] as $k=> $v)
                {
                    $in = array(
                        'pid'=>$para2,
                        'title' => $nm[$k],
                        'detail' => $vls[$k]
                        );
                    $this->db->insert('product_to_accordion',$in);
                }
                $id = $para2;
                $am_arr = array();
                                $exp = $this->db->where('pid', $id)->get('product_to_amenity')->result_array();
                foreach($exp as $k=> $v)
                                {
                                    $amn = $this->db->where('amenity_id',$v['am_id'])->get('amenity')->row();
                                    $am_arr[] = $amn->name;
                                }
                                $this->db->where('product_id',$id)->update('product',array('amenities'=>implode(',',$am_arr)));
            }
              if(is_array($this->input->post('amenities'))){
                $arr = $this->input->post('amenities');
                   $this->add_product_amenities($para1,$para2,$arr);
            }else{
             $string = $this->input->post('amenities');
             $arr =  explode(',', $string);
             $this->add_product_amenities($para1,$para2,$arr);
            }
            $this->load->library('cloudinarylib');
            $id = $para2;
            update_col($id);
            if(isset($_POST['fields']) && $id)
            {
                foreach($_POST['fields'] as $k=> $v)
                {
                    // $r = update_product_meta($id,$k, $v);
                }
            }
            if($id && isset($_FILES['sneakerimg']['name']) && !empty($_FILES['sneakerimg']['name'])){
                    $path = 'uploads/product_image/product_' . time() . '.webp';

                         move_uploaded_file($_FILES["sneakerimg"]['tmp_name'], $path);
                        // $data = \Cloudinary\Uploader::upload($path);
                        // var_dump($data);
                    if($path)
                    {
                        $logo_id = $this->crud_model->add_img($path,array());
                        $this->db->where('product_id',$id)->update('product',array('comp_logo'=>$logo_id));


                    }
                }//if iumg set thensss
                // var_dump($_FILES);
                // die();
                if($id && isset($_FILES['firstImg']['name']) && !empty($_FILES['firstImg']['name'])){

                    $sneakerimg = $this->crud_model->file_up("firstImg", "product", 'firstImg_'.time());

                // $data =  \Cloudinary\Uploader::upload($sneakerimg);
                if($sneakerimg)
                {
                    $logo_id = $this->crud_model->add_img($sneakerimg,array());
                    // array_push($dataInfo,$logo_id);
                    $this->db->where('product_id',$id)->update('product',array('firstImg'=>$logo_id));


                }
            }

                    if($id && isset($_FILES['sideimg']['name']) && !empty($_FILES['sideimg']['name'])){

                     $path = 'uploads/product_image/product1cover_' . time() . '.webp';
                         move_uploaded_file($_FILES["sideimg"]['tmp_name'], $path);
                        // $data = \Cloudinary\Uploader::upload($path);
                    if($path)
                    {
                        $logo_id = $this->crud_model->add_img($path,array());
                        $this->db->where('product_id',$id)->update('product',array('comp_cover'=>$logo_id));


                    }

                    }

                    if($id && isset($_FILES['boxImg']['name']) && !empty($_FILES['boxImg']['name'])){

                        $sneakerimg = $this->crud_model->file_up("boxImg", "product", 'boxImg_'.time());
                        $this->db->where('product_id',$id)->update('product',array('boxImg'=>$sneakerimg));
                        unset($sneakerimg);

                    }
            $this->crud_model->set_category_data(0);
            recache();
        }
        else if ($para1 == 'edit') {
            
            if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
            $sing = $this->db->where('product_id' , $para2)->get('product')->row();
            $added_by = json_decode($sing->added_by,true);
            if(isset($added_by['id']) && $added_by['id'] != $this->session->userdata('vendor_id'))
            {
                $this->logout();
            }
            $page_data['brandss'] =  $this->db->where('pcat', '369')->get('category')->result_array();
            $attrs = $this->db->where('product_id' , $para2)->get('attribute_to_values')->result_array();
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->row();

            $page_data['product_data1'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            
            $page_data['row'] = $sing;
            if(isset($sing->module) && $sing->module)
            {
                $module = $sing->module;
                $this->db->order_by("sorting", "asc");

                $mod = $this->db->where('id',$module)->get('modules')->row();
                if(isset($mod->category))
                {
                    $cat = $this->db->where('category_id',$mod->category)->get('category')->row();
                    if($cat)
                    {
                        $page_data['mod'] = $mod;
                        $page_data['mod_cat'] = $cat;
                    }
                }
            }
             $vid = $this->session->userdata('vendor_id');
                $page_data['warehouse'] =  $this->db->where('uid',$vid)->get('address')->result_array();
            $page_data['brands'] =  $this->db->get('category')->result_array();
             $page_data['social_media']= $this->db->get('bpkg')->result_array();
            $page_data['page_name']   = ($sing->is_bpage)?"product_add2":"product_add2";
            if($sing->is_blog)
            {
                $page_data['page_name']   = "blog_edit";
            }
            $this->load->view('back/index', $page_data);
            }
            else
            {
             $page_data['page_name'] = 'product_limit';
                $this->load->view('back/index', $page_data);   
            }
            
            

        }
        else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->where(array(
                'product_id' => $para2
            ))->get('product')->row_array();
            $this->load->view('back/vendor/product_view', $page_data);
        }
        elseif ($para1 == 'delete') {
            if(!demo()){
                $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
                $this->db->where('product_id', $para2);
                $this->db->delete('product');
                $this->crud_model->set_category_data(0);
            }

            recache();
        }
         elseif ($para1 == 'delimg') {
            $this->db->where('id',$para2)->delete('product_to_images');
            $pid = 0;
            if(isset($_GET['pid']))
            {

                $pid = $_GET['pid'];
            }
            ?>
            <ul>
                                        <?php
                                        $imgs = $this->db->where('pid',$pid)->get('product_to_images')->result_array();
                                        foreach ($imgs as $key => $value) {
                                            $img = $this->crud_model->size_img($value['img'],100,100);
                                            ?>
                                            <li id="gimg_<?= $value['id']; ?>">
                                                <div onclick="delimg('<?= $value['id']; ?>')" class="del_icon"><i class="fa-solid fa-xmark" aria-hidden="true"></i>
</div>

                                                <img src="<?= $img ?>"/></li>

                                            <?php
                                        }
                                        ?>
                                        </ul>
            <?php
        }
         elseif ($para1 == 'rimg') {
            $pid =  $para2;
            ?>
            <ul>
                                        <?php
                                        $this->db->order_by("id", "asc");

                                        $imgs = $this->db->where('pid',$pid)->get('product_to_images')->result_array();
                                        foreach ($imgs as $key => $value) {
                                            $img = $this->crud_model->size_img($value['img'],100,100);
                                            ?>
                                            <li id="gimg_<?= $value['id']; ?>">
                                                <div onclick="delimg('<?= $value['id']; ?>')" class="del_icon"><i class="fa-solid fa-xmark" aria-hidden="true"></i>
</div>

                                                <img src="<?= $img ?>"/></li>

                                            <?php
                                        }
                                        ?>
                                        </ul>
            <?php
        }
        elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $this->db->where('download=',NULL);
            $this->db->where('is_bpage=',0);
            $page_data['type']='';
            $page_data['all_product'] = $this->db->get('product')->result_array();
             $this->load->view('back/vendor/product_list', $page_data);
        }
        elseif ($para1 == 'list_data') {
            if(isset($mod))
            {
                $this->db->where('module',$mod->id);
            }
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if($search){
                $this->db->like('title', $search, 'both');
            }

            $this->db->where('download=',NULL);
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            if(isset($_GET['is_job']))
            {
                $this->db->where('is_job',1);
            }
            if(isset($_GET['is_event']))
            {
                $this->db->where('is_event',1);
            }
            if(isset($_GET['is_product']))
            {
                $this->db->where('is_product',1);
            }
             if(isset($_GET['is_blog']))
            {
            $page_data['type']='is_blog';
            $page_data['is_blog']=1;
            }
            $total      = $this->db->get('product')->num_rows();
            $this->db->limit($limit);
            if($sort == ''){
                $sort = 'product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort,$order);
            if($search){
                $this->db->like('title', $search, 'both');
            }
            $added_by = '';
            if(isset($mod))
            {
                $this->db->where('module',$mod->id);
            }
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $this->db->where('download=',NULL);
            $this->db->where('is_bpage=',0);
            if(isset($_GET['is_job']))
            {
                $this->db->where('is_job',1);
            }
            if(isset($_GET['is_event']))
            {
                $this->db->where('is_event',1);
            }
            if(isset($_GET['is_product']))
            {
                $this->db->where('is_product',1);
            }
            if(isset($_GET['is_blog']))
            {
                $this->db->where('is_blog',1);
            }
            $products   = $this->db->get('product', $limit, $offset)->result_array();
            // var_dump($this->db->last_query());
            // die();


            $data       = array();
            foreach ($products as $row) {


               $category = $row['category'];
                $cat = $this->db->where('category_id',$category)->get('category')->row();
                if($row['comp_cover'])
                {
                    $img = $this->crud_model->size_img($row['comp_cover'],30,30);

                }
                else
                {
                   $img = $this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','one');
                }
                $res    = array(
                             'item'        => '',
                             'getMainPrice'        => '',
                             'sizes'        => '',
                             'added_by'     => '',
                             'current_stock'=> '',
                             'deal'         => '',
                             'publish'      => '',
                             'featured'     => '',
                             'options'      => ''
                          );
                          //get min
                          $child = array();
                          $child[] = $row['product_id'];
                          $min = '';
                          $max = '';
                          if($child)
                          {
                          $all_rates = $this->db->where('rate >',0)->where_in('product', $child)->get('stock')->result_array();
                          $gmin = 0;
                          $gmax = 0;
                          }
                          $cat_name = "";

                          if(isset($cat->category_name))
                          {
                          $cat_name = $cat->category_name;
                          }

                $res['item']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;float: left;" src="'.$img.'"  /><div class="next_div" ><small>'.$row['title'].'</small></div>';
                $res['list_type']  = $cat_name;
                if($row['status'] == 'ok'){
                    $res['publish']  = '<input id="pub_'.$row['product_id'].'" class="sw1" type="checkbox" data-id="'.$row['product_id'].'" checked />';
                } else {
                    $res['publish']  = '<input id="pub_'.$row['product_id'].'" class="sw1" type="checkbox" data-id="'.$row['product_id'].'" />';
                }
                if($row['vendor_featured'] == 'ok'){
                    $res['featured']  = '<input id="v_fet_'.$row['product_id'].'" class="sw4" type="checkbox" data-id="'.$row['product_id'].'" checked />';
                } else {
                    $res['featured']  = '<input id="v_fet_'.$row['product_id'].'" class="sw4" type="checkbox" data-id="'.$row['product_id'].'" />';
                }
                if($row['current_stock'] > 0){
                    $res['current_stock']  = $row['current_stock'].$row['unit'].'(s)';
                } else {
                    $res['current_stock']  = '<span class="label label-danger">'.translate('out_of_stock').'</span>';
                }

                //add html for action
                // $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\"
                //                 onclick=\"ajax_set_full('view','".translate('view_product')."','".translate('successfully_viewed!')."','product_view','".$row['product_id']."');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                //                     ".translate('view')."
                //             </a>
                //             <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                //                 onclick=\"ajax_modal('add_discount','".translate('view_discount')."','".translate('viewing_discount!')."','add_discount','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                //                     ".translate('discount')."
                //             </a>
                //             <a class=\"btn btn-mint btn-xs btn-labeled fa fa-plus-square\" data-toggle=\"tooltip\"
                //                 onclick=\"ajax_modal('add_stock','".translate('add_product_quantity')."','".translate('quantity_added!')."','stock_add','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                //                     ".translate('stock')."
                //             </a>
                //             <a class=\"btn btn-dark btn-xs btn-labeled fa fa-minus-square\" data-toggle=\"tooltip\"
                //                 onclick=\"ajax_modal('destroy_stock','".translate('reduce_product_quantity')."','".translate('quantity_reduced!')."','destroy_stock','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                //                     ".translate('destroy')."
                //             </a>

                //             <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\"
                //                 onclick=\"ajax_set_full('edit','".translate('edit_product')."','".translate('successfully_edited!')."','product_edit','".$row['product_id']."');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                //                     ".translate('edit')."
                //             </a>

                //             <a onclick=\"delete_confirm('".$row['product_id']."','".translate('really_want_to_delete_this?')."')\"
                //                 class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                //                     ".translate('delete')."
                //             </a>";
                $res['options'] = "
                            <a href='".base_url('vendor/affliate/add').'?pid='.$row['product_id']."'\"
                                class=\"btn btn-success btn-xs btn-labeled fa fa-eye\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\"> 
                                    ".translate('affliate')." 
                            </a>
                            <a target='_blank' href='".base_url($row['slug'])."'\"
                                class=\"btn btn-info btn-xs btn-labeled fa fa-eye\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\"> 
                                    ".translate('view')."
                            </a>
                            <a href='".base_url('vendor/product/edit/').$row['product_id']."' class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\"
                                data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('edit')."
                            </a>

                            <a onclick=\"delete_confirm('".$row['product_id']."','".translate('really_want_to_delete_this?')."')\"
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    ".translate('delete')."
                            </a>";
                $data[] = $res;
            }
            $result = array(
                             'total' => $total,
                             'rows' => $data
                           );

            echo json_encode($result);

        } else if ($para1 == 'dlt_img') {
            if(!demo()){
                $a = explode('_', $para2);
                $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
                recache();
            }
        } elseif ($para1 == 'sub_by_cat') {
            $para2 = str_replace("-", ",", $para2);

            $brands = $this->db->where('pcat',$para2)->get('category')->result_array();
            $sing = $this->db->where('category_id',$para2)->get('category')->row();
            // var_dump($sing);

            $level = $sing->level;
            // var_dump($level);
            $breed = array();
            $cid = $para2;
            for ($i=1; $i <= $level; $i++) {
                // var_dump($i);
                $breed[] = $cid;
                $row = $this->db->where('category_id',$cid)->get('category')->row();
                $cid = $row->pcat;

            }
            // var_dump($breed);
            if($breed)
            {
                ?>
                <div class="breaddcum">
                    <ul>
                        <li onclick="selecttype('',0,1)"><i class="fa fa-home" aria-hidden="true"></i></li>
                        <?php
                        $cat = array();
                        foreach(array_reverse($breed) as $k=> $v)
                        {
                            $cat[] = $v;
                            $row = $this->db->where('category_id',$v)->get('category')->row();
                            if($para3 == 'add')
                            {
                            ?>
                            <li onclick="selecttype('<?= implode(',',$cat);?>',<?= $v ?>,1)"><?= $row->category_name;?></li>
                            <?php
                            }
                            else
                            {
                            ?>
                            <li onclick="selecttype('<?= implode(',',$cat);?>',0,1)"><?= $row->category_name;?></li>
                            <?php
                            }
                        }
                        ?>


                    </ul>
                </div>
                <?php
            }
            if(!$brands)
            {
               echo $next = '';
                exit();
            }

            foreach($brands as $k=>$v){
                if(true)
                {
            ?>
                <div class="col-md-4 col-sm-12 col-xs-12 <?= ($product_data->category == $v['category_id'])?"active":"" ?>" onclick="selecttype('<?= $v['category_id'];?>')" >
                    <a href="javascript:void(0);"><div class="flip-card ">
                  <div class="flip-card-inner">
                    <div class="flip-card-front <?= ($product_data->category == $v['category_id'])?"active":"" ?>">
                        <p><?= $v['category_name'];?></p>
                    </div>
                    
                  </div>
                </div>
                </a>
                </div>
                <?php
                }
            }
            // echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, 'get_brnd');
        } elseif ($para1 == 'sub_by_cat1') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select', '', 'category', $para2, 'get_brnd');
        } elseif ($para1 == 'brand_by_sub') {
            $brands=json_decode($this->crud_model->get_type_name_by_id('sub_category',$para2,'brand'),true);
            echo $this->crud_model->select_html('brand', 'brand', 'name', 'add', 'demo-chosen-select required', '', 'brand_id', $brands, '', 'multi');
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        } elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        } elseif ($para1 == 'add') {

            if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                $page_data['modules'] =  $this->db->order_by("sorting", "asc")->get('modules')->result();
                $vid = $this->session->userdata('vendor_id');
                $page_data['warehouse'] =  $this->db->where('uid',$vid)->get('address')->result_array();
                $page_data['brandss'] =  $this->db->where('pcat', '369')->get('category')->result_array();

                if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                    $categories =json_decode($this->db->get_where('ui_settings',array('ui_settings_id' => 35))->row()->value, true);
                       $result=array();
                                            foreach($categories as $row){
                                                if($this->crud_model->if_publishable_category($row)){
                                                    $result[]=$row;
                                                }
                                            }
                    if($result)
                    {
                    $page_data['brands'] =  $this->db->where_in('category_id',$result)->get('category')->result_array();
                    } 
                    // $this->load->view('back/vendor/product_add', $page_data);
                    $page_data['page_name']   = "product_add2";
                    if(isset($_GET['is_product']))
                    {
                        $page_data['page_name']   = "product_add1";
                    }
                    if(isset($_GET['is_blog']))
                    {
                        $page_data['page_name']   = "blog_add";
                    }

            $this->load->view('back/index', $page_data);
                } else {
                    $page_data['page_name']   = "product_limit";
            $this->load->view('back/index', $page_data);
                    // $this->load->view('back/vendor/product_limit');
                }
            }
            else{
                $page_data['page_name'] = 'product_limit';
                $this->load->view('back/index', $page_data);
            }

        } elseif ($para1 == 'srch') {
            if(isset($_GET['srh']))
            $para2 = $_GET['srh'];
            $page = 0;
            if(isset($_GET['page']))
            $page = $_GET['page'];
            if($para2)
            {
               $this->db->like('title', $para2);
               $this->db->where('parent_id', 0);
               $per_page = 16;
               $obj= $this->db;
               $res = $this->db->get('product')->result_array();


               $tot_pro = count($res);

            $tot_page = $tot_pro/$per_page;
            $tpage = $tot_page;
            $cpage = $page;
            $start = $page * $per_page;
            $obj->limit($per_page, $start);
            $obj->like('title', $para2);
$res = $obj->get('product')->result_array();
foreach($res as $k=> $v)
{
    $category = $v['category'];
    $cat = $this->db->where('category_id',$category)->get('category')->row();
                   $vendors = $this->db->where('parent_id', $v['product_id']);
$vendors = $this->db->get('product')->result_array();
$price = $this->crud_model->getMainPrice($v['product_id']);

    ?>
    <li onclick="select_product('<?= $v['product_id'] ?>')">
                                                <div class="img_div" >
                                                <img src="<?= $this->crud_model->file_view('product',$v['product_id'],'','','thumb','src','multi','one'); ?>"/>
                                                </div>
                                                <div class="det_div" >
                                                    <h1><?= $v['title']; ?></h1>
                                                    <p><?php
                                                    if($cat || $price)
                                                    {
                                                        if(!empty($cat->category_name))
                                                        {
                                                            echo 'Product category : '.$cat->category_name;
                                                        }
                                                        if(!empty($price))
                                                        {
                                                            if(!empty($cat->category_name))
                                                            {
                                                            echo '/Lowest Price from vendors : '.$price.'€';
                                                            }
                                                            else
                                                            {
                                                                echo 'Lowest Price from vendors : '.$price.'€';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                     </p>
                                                    </div>
                                            </li>
    <?php
}
/*if($cpage <= $tpage)
{
    ?>
    <li class="load_more" onclick="load_more('<?= $para2; ?>',<?= $cpage++; ?>')">Load More</li>
    <?php
}*/
            }
            else
            {
                if ($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'no') {
                    if(true){
                        $this->load->view('back/vendor/product_step1');
                    } else {
                        $this->load->view('back/vendor/product_limit');
                    }
                }
                elseif($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'yes'){
                    $this->load->view('back/vendor/product_step1');
                }
            }
        } elseif ($para1 == 'add1') {

            if($para2)
            {
                $data = array();
                $data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
                $data['parent_id']           = $para2;
                $already = $this->db->where($data)->get('product')->row();
                if($already)
                {
                    echo "Already added";
                }

            $this->load->view('back/vendor/product_add', $page_data);
            }
            else
            {
                if ($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'no') {
                    if(true){
                        $this->load->view('back/vendor/product_step1');
                    } else {
                        $this->load->view('back/vendor/product_limit');
                    }
                }
                elseif($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'yes'){
                    $this->load->view('back/vendor/product_step1');
                }
            }
        } elseif ($para1 == 'add_stock') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_stock_add', $data);
        } elseif ($para1 == 'destroy_stock') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_stock_destroy', $data);
        } elseif ($para1 == 'stock_report') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_stock_report', $data);
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_add_discount', $data);
        } elseif ($para1 == 'product_featured_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['featured'] = 'ok';
            } else {
                $data['featured'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_v_featured_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['vendor_featured'] = 'ok';
            } else {
                $data['vendor_featured'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_deal_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['deal'] = 'ok';
            } else {
                $data['deal'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'add_discount_set') {
            $product               = $this->input->post('product');
            $data['discount']      = $this->input->post('discount');
            $data['discount_type'] = $this->input->post('discount_type');
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            $this->crud_model->set_category_data(0);
            recache();
        } else {
            
            $page_data['page_name']   = "product";
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }
    public function add_product_amenities($para1,$id, $data = array()){
        if($para1 == 'update'){
            $this->db->where('p_id', $id);
            $this->db->delete('product_amenity');
              foreach($data as $key => $value){
            $values = array(
                'p_id' => $id,
                'name' => $value
                );
            
          $this->db->insert('product_amenity', $values);
        }
        }else{
               foreach($data as $key => $value){
            $values = array(
                'p_id' => $id,
                'name' => $value
                );
            
          $this->db->insert('product_amenity', $values);
        }
        }
      
    }
    public function product_bulk_upload()
    {
        if (!$this->crud_model->vendor_permission('product')) {
            redirect(base_url() . 'vendor');
        }

        $physical_categories =  $this->db->where('digital',null)->or_where('digital','')->get('category')->result_array();
        $physical_sub_categories =  $this->db->where('digital',null)->or_where('digital','')->get('sub_category')->result_array();
        $digital_categories =  $this->db->where('digital','ok')->get('category')->result_array();
        $digital_sub_categories =  $this->db->where('digital','ok')->get('sub_category')->result_array();
        $brands =  $this->db->get('brand')->result_array();

        $page_data['page_name'] = "product_bulk_upload";
        $page_data['physical_categories'] = $physical_categories;
        $page_data['physical_sub_categories'] = $physical_sub_categories;
        $page_data['digital_categories'] = $digital_categories;
        $page_data['digital_sub_categories'] = $digital_sub_categories;
        $page_data['brands'] = $brands;

        $this->load->view('back/index', $page_data);

    }

    public function product_bulk_upload_save()
    {
        if(demo()){
            $this->session->set_flashdata('error',translate('This operation is invalid for demo'));
            redirect('vendor/product_bulk_upload');
        }

        if(!file_exists($_FILES['bulk_file']['tmp_name']) || !is_uploaded_file($_FILES['bulk_file']['tmp_name'])){
            $this->session->set_flashdata('error',translate('File is not selected'));
            redirect('vendor/product_bulk_upload');
        }


        $inputFileName = $_FILES['bulk_file']['tmp_name'];


        $inputFileType = $this->spreadsheet->identify($inputFileName);
        $reader = $this->spreadsheet->createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $products = array();
        if(!empty($sheetData)){

            if(!isset($sheetData[1])){
                $this->session->set_flashdata('error',translate('Column names are missing'));
                redirect('vendor/product_bulk_upload');
            }

            foreach ($sheetData[1] as $colk => $colv){
                $col_map[$colk] = $colv;
            }


            if(!isset($sheetData[2])){
                $this->session->set_flashdata('error',translate('Data missing'));
                redirect('vendor/product_bulk_upload');
            }

            for($i = 2;$i <= count($sheetData);$i++){
                $product = array();
                foreach ($sheetData[$i] as $colk =>$colv) {
                    $product[$col_map[$colk]] = $colv;
                }
                $products[] = $product;
            }
        }

        if(!empty($products)){
            foreach ($products as $product){
                $this->product_bulk_upload_save_single($product);
            }
        }

        $this->session->set_flashdata('success',translate('Products uploaded'));
        redirect('vendor/product_bulk_upload');

    }

    public function product_bulk_upload_save_single($product)
    {
        $image_urls = array();
        $product_stock_data = array();
        $product_data['num_of_imgs'] = 0;
        if (!empty($product['images'])) {
            $image_urls = explode(',', $product['images']);
            $product_data['num_of_imgs'] = count($image_urls);
        }

        $product_data['title'] = $product['title'];
        $product_data['description'] = $product['description'];
        $product_data['category'] = is_numeric($product['category']) ? $product['category'] : 0;
        $product_data['sub_category'] = is_numeric($product['sub_category']) ? $product['sub_category'] : 0;
        $product_data['brand'] = is_numeric($product['brand']) ? $product['brand'] : 0;

        $product_data['purchase_price'] = is_numeric($product['purchase_price']) ? $product['purchase_price'] : 0;
        $product_data['sale_price'] = is_numeric($product['sale_price']) ? $product['sale_price']: 0;

        $product_data['add_timestamp'] = time();
        $product_data['download'] = NULL;
        $product_data['featured'] = 'no';
        $product_data['vendor_featured'] = 'no';
        $product_data['status'] = $product['published'] == 'yes' ? 'ok' : 0;
        $product_data['rating_user'] = '[]';

        if (strpos($product['tax'], '%') !== false) {
            $tax = str_replace("%", "", $product['tax']);
            $product_data['tax'] = is_numeric($tax) ? $tax : 0;
            $product_data['tax_type'] = 'percent';
        } else {
            $tax = $product['tax'];
            $product_data['tax'] = is_numeric($tax) ? $tax : 0;
            $product_data['tax_type'] = 'amount';
        }

        if (strpos($product['discount'], '%') !== false) {
            $discount = str_replace("%", "", $product['discount']);
            $product_data['discount'] = is_numeric($discount) ? $discount : 0;
            $product_data['discount_type'] = 'percent';
        } else {
            $discount = $product['discount'];
            $product_data['discount'] = is_numeric($discount) ? $discount : 0;
            $product_data['discount_type'] = 'amount';
        }

        $product_data['shipping_cost'] = is_numeric($product['shipping_cost']) ? $product['shipping_cost'] : 0;
        $product_data['is_bundle'] = 'no';
        $product_data['color'] = null;
        $product_data['current_stock'] = is_numeric($product['add_stock']) ? $product['add_stock'] : 0;



        $product_data['front_image'] = 0;

        $product_data['additional_fields'] = null;
        $product_data['unit'] = is_numeric($product['unit']) ? $product['unit'] : "";
        $product_data['added_by'] = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
        $product_data['options'] = json_encode($options = array());

        $this->db->insert('product', $product_data);
        $product_id = $this->db->insert_id();
        $this->crud_model->set_category_data(0);
        recache();

        if($product_data['current_stock'] > 0){
            $product_stock_data['type']         = 'add';
            $product_stock_data['product']      = $product_id;
            $product_stock_data['category']     = $product_data['category'];
            $product_stock_data['sub_category'] = $product_data['sub_category'];
            $product_stock_data['product']      = $product_data['product'];
            $product_stock_data['quantity']     = $product_data['current_stock'];
            $product_stock_data['rate']         = $product_data['purchase_price'];
            $product_stock_data['total']        = $product_data['purchase_price'] * $product_data['current_stock'] ;
            $product_stock_data['reason_note']  = 'bulk';
            $product_stock_data['added_by']     = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $product_stock_data['datetime']     = time();
            $product_stock_data['current_stock']= $product_data['current_stock'];
            $this->db->insert('stock', $product_stock_data);
        }

        if(!empty($image_urls)){
            if(!demo()){
                $this->crud_model->file_up_from_urls($image_urls, "product", $product_id);
            }
        }

    }

    /* Digital add, edit, view, delete, stock increase, decrease, discount */
    function digital($para1 = '', $para2 = '', $para3 = '')
    {
        if (!$this->crud_model->vendor_permission('product')) {
            redirect(base_url() . 'vendor');
        }
        if ($this->crud_model->get_type_name_by_id('general_settings','69','value') !== 'ok') {
            redirect(base_url() . 'admin');
        }
        if ($para1 == 'do_add') {
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            if ($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'no') {
                if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))) {

                    $data['seo_title']          = $this->input->post('seo_title');
                    $data['seo_description']    = $this->input->post('seo_description');
                    $data['title']              = $this->input->post('title');
                    $data['category']           = $this->input->post('category');
                    $data['description']        = $this->input->post('description');
                    $data['sub_category']       = $this->input->post('sub_category');
                    $data['sale_price']         = $this->input->post('sale_price');
                    $data['purchase_price']     = $this->input->post('purchase_price')+$this->input->post('shippo_price');
                    $data['add_timestamp']      = time();
                    $data['featured']           = 'no';
                    $data['status']             = 'ok';
                    $data['rating_user']        = '[]';
                    $data['tax']                = $this->input->post('tax');
                    $data['discount']           = $this->input->post('discount');
                    $data['discount_type']      = $this->input->post('discount_type');
                    $data['tax_type']           = $this->input->post('tax_type');
                    $data['shippo']             = $this->input->post('shippo_price');
                    $data['shipping_cost']      = 0;
                    $data['tag']                = $this->input->post('tag');
                    $data['num_of_imgs']        = $num_of_imgs;
                    $data['front_image']        = $this->input->post('front_image');
                    $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
                    $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
                    $data['additional_fields']  = json_encode($additional_fields);
                    $data['requirements']       =   '[]';
                    $data['video']              =   '[]';

                    $data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));

                    $this->db->insert('product', $data);
                    $id = $this->db->insert_id();
                    $this->benchmark->mark_time();

                    if(!demo()){
                        $this->crud_model->file_up("images", "product", $id, 'multi');
                    }

                    $path = $_FILES['logo']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $data_logo['logo']      = demo() ? "" : 'digital_logo_'.$id.'.'.$ext;
                    $this->db->where('product_id' , $id);
                    $this->db->update('product' , $data_logo);
                    if(!demo()){
                        $this->crud_model->file_up("logo", "digital_logo", $id, '','no','.'.$ext);
                    }

                    //Requirements add
                    $requirements               =   array();
                    $req_title                  =   $this->input->post('req_title');
                    $req_desc                   =   $this->input->post('req_desc');
                    if(!empty($req_title)){
                        foreach($req_title as $i => $row){
                            $requirements[]         =   array('index'=>$i,'field'=>$row,'desc'=>$req_desc[$i]);
                        }
                    }

                    $data_req['requirements']           =   json_encode($requirements);
                    $this->db->where('product_id' , $id);
                    $this->db->update('product' , $data_req);

                    //File upload
                    $rand           = substr(hash('sha512', rand()), 0, 20);
                    $name           =  demo() ? "" : $id.'_'.$rand.'_'.$_FILES['product_file']['name'];
                    $da['download_name'] = $name;
                    $da['download'] = 'ok';
                    $folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
                    if(!demo()){
                        move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder .'/' . $name);
                    }
                    $this->db->where('product_id', $id);
                    $this->db->update('product', $da);

                    //vdo upload
                    $video_details              =   array();
                    if($this->input->post('upload_method') == 'upload'){
                        $video              =   $_FILES['videoFile']['name'];
                        $ext                =   pathinfo($video,PATHINFO_EXTENSION);
                        if(!demo()){
                            move_uploaded_file($_FILES['videoFile']['tmp_name'],'uploads/video_digital_product/digital_'.$id.'.'.$ext);
                        }
                        $video_src          =   'uploads/video_digital_product/digital_'.$id.'.'.$ext;
                        $video_details[]    =   demo() ? array() : array('type'=>'upload','from'=>'local','video_link'=>'','video_src'=>$video_src);
                        $data_vdo['video']  =   json_encode($video_details);
                        $this->db->where('product_id',$id);
                        $this->db->update('product',$data_vdo);
                    }
                    elseif ($this->input->post('upload_method') == 'share'){
                        $from               = $this->input->post('site');
                        $video_link         = $this->input->post('video_link');
                        $code               = $this->input->post('video_code');
                        if($from=='youtube'){
                            $video_src      = 'https://www.youtube.com/embed/'.$code;
                        }else if($from=='dailymotion'){
                            $video_src      = '//www.dailymotion.com/embed/video/'.$code;
                        }else if($from=='vimeo'){
                            $video_src      = 'https://player.vimeo.com/video/'.$code;
                        }
                        $video_details[]    = array('type'=>'share','from'=>$from,'video_link'=>$video_link,'video_src'=>$video_src);
                        $data_vdo['video']  =   json_encode($video_details);
                        $this->db->where('product_id',$id);
                        $this->db->update('product',$data_vdo);
                    }
                } else {
                    echo $this->db->last_query();
                    die();
                    echo 'something working wrong!';
                }
            }
            elseif($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'yes'){

                $data['seo_title']          = $this->input->post('seo_title');
                $data['seo_description']    = $this->input->post('seo_description');
                $data['title']              = $this->input->post('title');
                $data['category']           = $this->input->post('category');
                $data['description']        = $this->input->post('description');
                $data['sub_category']       = $this->input->post('sub_category');
                $data['sale_price']         = $this->input->post('sale_price');
                $data['purchase_price']     = $this->input->post('purchase_price');
                $data['add_timestamp']      = time();
                $data['featured']           = 'no';
                $data['status']             = 'ok';
                $data['rating_user']        = '[]';
                $data['tax']                = $this->input->post('tax');
                $data['discount']           = $this->input->post('discount');
                $data['discount_type']      = $this->input->post('discount_type');
                $data['tax_type']           = $this->input->post('tax_type');
                $data['shipping_cost']      = 0;
                $data['tag']                = $this->input->post('tag');
                $data['num_of_imgs']        = $num_of_imgs;
                $data['front_image']        = $this->input->post('front_image');
                $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
                $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
                $data['additional_fields']  = json_encode($additional_fields);
                $data['requirements']       =   '[]';
                $data['video']              =   '[]';

                $data['added_by']           = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));

                $this->db->insert('product', $data);
                $id = $this->db->insert_id();
                $this->benchmark->mark_time();

                if(!demo()){
                    $this->crud_model->file_up("images", "product", $id, 'multi');
                }

                $path = $_FILES['logo']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_logo['logo']       = demo() ? "" : 'digital_logo_'.$id.'.'.$ext;
                $this->db->where('product_id' , $id);
                $this->db->update('product' , $data_logo);
                if(!demo()){
                    $this->crud_model->file_up("logo", "digital_logo", $id, '','no','.'.$ext);

                }
                //Requirements add
                $requirements               =   array();
                $req_title                  =   $this->input->post('req_title');
                $req_desc                   =   $this->input->post('req_desc');
                if(!empty($req_title)){
                    foreach($req_title as $i => $row){
                        $requirements[]         =   array('index'=>$i,'field'=>$row,'desc'=>$req_desc[$i]);
                    }
                }

                $data_req['requirements']           =   json_encode($requirements);
                $this->db->where('product_id' , $id);
                $this->db->update('product' , $data_req);

                //File upload
                $rand           = substr(hash('sha512', rand()), 0, 20);
                $name           = $id.'_'.$rand.'_'.$_FILES['product_file']['name'];
                $da['download_name'] = $name;
                $da['download'] = 'ok';
                $folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
                if(!demo()){
                    move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder .'/' . $name);
                }
                $this->db->where('product_id', $id);
                $this->db->update('product', $da);

                //vdo upload
                $video_details              =   array();
                if($this->input->post('upload_method') == 'upload'){
                    $video              =   $_FILES['videoFile']['name'];
                    $ext                =   pathinfo($video,PATHINFO_EXTENSION);
                    if(!demo()){
                        move_uploaded_file($_FILES['videoFile']['tmp_name'],'uploads/video_digital_product/digital_'.$id.'.'.$ext);
                    }

                    $video_src          =   'uploads/video_digital_product/digital_'.$id.'.'.$ext;
                    $video_details[]    =   demo() ? array() : array('type'=>'upload','from'=>'local','video_link'=>'','video_src'=>$video_src);
                    $data_vdo['video']  =   json_encode($video_details);
                    $this->db->where('product_id',$id);
                    $this->db->update('product',$data_vdo);
                }
                elseif ($this->input->post('upload_method') == 'share'){
                    $from               = $this->input->post('site');
                    $video_link         = $this->input->post('video_link');
                    $code               = $this->input->post('video_code');
                    if($from=='youtube'){
                        $video_src      = 'https://www.youtube.com/embed/'.$code;
                    }else if($from=='dailymotion'){
                        $video_src      = '//www.dailymotion.com/embed/video/'.$code;
                    }else if($from=='vimeo'){
                        $video_src      = 'https://player.vimeo.com/video/'.$code;
                    }
                    $video_details[]    =   array('type'=>'share','from'=>$from,'video_link'=>$video_link,'video_src'=>$video_src);
                    $data_vdo['video']  =   json_encode($video_details);
                    $this->db->where('product_id',$id);
                    $this->db->update('product',$data_vdo);
                }
            }
            $this->crud_model->set_category_data(0);
            recache();
        } else if ($para1 == "update") {
            $options = array();
            if ($_FILES["images"]['name'][0] == '') {
                $num_of_imgs = 0;
            } else {
                $num_of_imgs = count($_FILES["images"]['name']);
            }
            $num                        = $this->crud_model->get_type_name_by_id('product', $para2, 'num_of_imgs');
            $download                   = $this->crud_model->get_type_name_by_id('product', $para2, 'download');

            $data['seo_title']          = $this->input->post('seo_title');
            $data['seo_description']    = $this->input->post('seo_description');
            $data['title']              = $this->input->post('title');
            $data['category']           = $this->input->post('category');
            $data['description']        = $this->input->post('description');
            $data['sub_category']       = $this->input->post('sub_category');
            $data['sale_price']         = $this->input->post('sale_price');
            $data['purchase_price']     = $this->input->post('purchase_price');
            $data['tax']                = $this->input->post('tax');
            $data['discount']           = $this->input->post('discount');
            $data['discount_type']      = $this->input->post('discount_type');
            $data['tax_type']           = $this->input->post('tax_type');
            $data['tag']                = $this->input->post('tag');
            $data['update_time']        = time();
            $data['num_of_imgs']        = $num + $num_of_imgs;
            $data['front_image']        = $this->input->post('front_image');
            $additional_fields['name']  = json_encode($this->input->post('ad_field_names'));
            $additional_fields['value'] = json_encode($this->input->post('ad_field_values'));
            $data['additional_fields']  = json_encode($additional_fields);

            //File upload
            if(!demo()){
                $this->crud_model->file_up("images", "product", $para2, 'multi');
            }
            if($_FILES['product_file']['name'] !== ''){
                $rand           = substr(hash('sha512', rand()), 0, 20);
                $name           = $para2.'_'.$rand.'_'.$_FILES['product_file']['name'];
                $data['download_name'] = $name;
                $folder = $this->db->get_where('general_settings', array('type' => 'file_folder'))->row()->value;
                if(!demo()){
                    move_uploaded_file($_FILES['product_file']['tmp_name'], 'uploads/file_products/' . $folder .'/' . $name);
                }
            }

            $this->db->where('product_id', $para2);
            $this->db->update('product', $data);

            if($_FILES['logo']['name'] !== ''){
                $path = $_FILES['logo']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $data_logo['logo']       = demo() ? "" : 'digital_logo_'.$para2.'.'.$ext;
                $this->db->where('product_id' , $para2);
                $this->db->update('product' , $data_logo);
                if(!demo()){
                    $this->crud_model->file_up("logo", "digital_logo", $para2, '','no','.'.$ext);
                }
            }

            //Requirements add
            $requirements               =   array();
            $req_title                  =   $this->input->post('req_title');
            $req_desc                   =   $this->input->post('req_desc');
            if(!empty($req_title)){
                foreach($req_title as $i => $row){
                    $requirements[]         =   array('index'=>$i,'field'=>$row,'desc'=>$req_desc[$i]);
                }
            }
            $data_req['requirements']           =   json_encode($requirements);
            $this->db->where('product_id' , $para2);
            $this->db->update('product' , $data_req);

            //vdo upload
            $video_details              =   array();
            if($this->input->post('upload_method') == 'upload'){
                $video              =   $_FILES['videoFile']['name'];
                $ext                =   pathinfo($video,PATHINFO_EXTENSION);

                if(!demo()){
                    move_uploaded_file($_FILES['videoFile']['tmp_name'],'uploads/video_digital_product/digital_'.$para2.'.'.$ext);
                }
                $video_src          =   'uploads/video_digital_product/digital_'.$para2.'.'.$ext;
                $video_details[]    =   array('type'=>'upload','from'=>'local','video_link'=>'','video_src'=>$video_src);
                $data_vdo['video']  =   demo() ? array() :json_encode($video_details);
                $this->db->where('product_id',$para2);
                $this->db->update('product',$data_vdo);
            }
            elseif ($this->input->post('upload_method') == 'share'){
                $video= json_decode($this->crud_model->get_type_name_by_id('product',$para2,'video'),true);
                if($video[0]['type'] == 'upload'){
                    if(file_exists($video[0]['video_src'])){
                        unlink($video[0]['video_src']);
                    }
                }
                $from               = $this->input->post('site');
                $video_link         = $this->input->post('video_link');
                $code               = $this->input->post('video_code');
                if($from=='youtube'){
                    $video_src      = 'https://www.youtube.com/embed/'.$code;
                }else if($from=='dailymotion'){
                    $video_src      = '//www.dailymotion.com/embed/video/'.$code;
                }else if($from=='vimeo'){
                    $video_src      = 'https://player.vimeo.com/video/'.$code;
                }
                $video_details[]    =   array('type'=>'share','from'=>$from,'video_link'=>$video_link,'video_src'=>$video_src);
                $data_vdo['video']  =   json_encode($video_details);
                $this->db->where('product_id',$para2);
                $this->db->update('product',$data_vdo);
            }
            elseif ($this->input->post('upload_method') == 'delete'){
                if(!demo()){
                    $data_vdo['video']  =   '[]';
                    $this->db->where('product_id',$para2);
                    $this->db->update('product',$data_vdo);

                    $video= json_decode($this->crud_model->get_type_name_by_id('product',$para2,'video'),true);
                    if($video[0]['type'] == 'upload'){
                        if(file_exists($video[0]['video_src'])){
                            unlink($video[0]['video_src']);
                        }
                    }
                }

            }
            $this->crud_model->set_category_data(0);

            recache();
        } else if ($para1 == 'edit') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/digital_edit', $page_data);
        } else if ($para1 == 'view') {
            $page_data['product_data'] = $this->db->get_where('product', array(
                'product_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/digital_view', $page_data);
        } else if ($para1 == 'download_file') {
            $this->crud_model->download_product($para2);
        } else if ($para1 == 'can_download') {
            if($this->crud_model->can_download($para2)){
                echo "yes";
            } else{
                echo "no";
            }
        } elseif ($para1 == 'delete') {
            if(!demo()){
                $this->crud_model->file_dlt('product', $para2, '.jpg', 'multi');
                unlink("uploads/digital_logo_image/" .$this->crud_model->get_type_name_by_id('product',$para2,'logo'));
                $video=$this->crud_model->get_type_name_by_id('product',$para2,'video');
                if($video!=='[]'){
                    $video_details= json_decode($video,true);
                    if($video_details[0]['type'] == 'upload'){
                        if(file_exists($video_details[0]['video_src'])){
                            unlink($video_details[0]['video_src']);
                        }
                    }
                }
                $this->db->where('product_id', $para2);
                $this->db->delete('product');
                $this->crud_model->set_category_data(0);
                recache();
            }
        } elseif ($para1 == 'list') {
            $this->db->order_by('product_id', 'desc');
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $this->db->where('download=','ok');
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/vendor/digital_list', $page_data);
        } elseif ($para1 == 'list_data') {
            $limit      = $this->input->get('limit');
            $search     = $this->input->get('search');
            $order      = $this->input->get('order');
            $offset     = $this->input->get('offset');
            $sort       = $this->input->get('sort');
            if($search){
                $this->db->like('title', $search, 'both');
            }
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $this->db->where('download=','ok');
            $total= $this->db->get('product')->num_rows();
            $this->db->limit($limit);
            if($sort == ''){
                $sort = 'product_id';
                $order = 'DESC';
            }
            $this->db->order_by($sort,$order);
            if($search){
                $this->db->like('title', $search, 'both');
            }
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $this->db->where('download=','ok');
            $products   = $this->db->get('product', $limit, $offset)->result_array();
            $data       = array();
            foreach ($products as $row) {

                $res    = array(
                             'image' => '',
                             'title' => '',
                             'publish' => '',
                             'options' => ''
                          );

                $res['image']  = '<img class="img-sm" style="height:auto !important; border:1px solid #ddd;padding:2px; border-radius:2px !important;" src="'.$this->crud_model->file_view('product',$row['product_id'],'','','thumb','src','multi','one').'"  />';
                $res['title']  = $row['title'];
                if($row['status'] == 'ok'){
                    $res['publish']  = '<input id="pub_'.$row['product_id'].'" class="sw1" type="checkbox" data-id="'.$row['product_id'].'" checked />';
                } else {
                    $res['publish']  = '<input id="pub_'.$row['product_id'].'" class="sw1" type="checkbox" data-id="'.$row['product_id'].'" />';
                }

                //add html for action
                $res['options'] = "  <a class=\"btn btn-info btn-xs btn-labeled fa fa-location-arrow\" data-toggle=\"tooltip\"
                                onclick=\"ajax_set_full('view','".translate('view_product')."','".translate('successfully_viewed!')."','digital_view','".$row['product_id']."');proceed('to_list');\" data-original-title=\"View\" data-container=\"body\">
                                    ".translate('view')."
                            </a>
                            <a class=\"btn btn-purple btn-xs btn-labeled fa fa-tag\" data-toggle=\"tooltip\"
                                onclick=\"ajax_modal('add_discount','".translate('view_discount')."','".translate('viewing_discount!')."','add_discount','".$row['product_id']."')\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('discount')."
                            </a>
                            <a class=\"btn btn-mint btn-xs btn-labeled fa fa-download\" data-toggle=\"tooltip\"
                                onclick=\"digital_download(".$row['product_id'].")\" data-original-title=\"Download\" data-container=\"body\">
                                    ".translate('download')."
                            </a>

                            <a class=\"btn btn-success btn-xs btn-labeled fa fa-wrench\" data-toggle=\"tooltip\"
                                onclick=\"ajax_set_full('edit','".translate('edit_product_(_digital_product_)')."','".translate('successfully_edited!')."','digital_edit','".$row['product_id']."');proceed('to_list');\" data-original-title=\"Edit\" data-container=\"body\">
                                    ".translate('edit')."
                            </a>

                            <a onclick=\"delete_confirm('".$row['product_id']."','".translate('really_want_to_delete_this?')."')\"
                                class=\"btn btn-danger btn-xs btn-labeled fa fa-trash\" data-toggle=\"tooltip\" data-original-title=\"Delete\" data-container=\"body\">
                                    ".translate('delete')."
                            </a>";
                $data[] = $res;
            }
            $result = array(
                             'total' => $total,
                             'rows' => $data
                           );

            echo json_encode($result);

        } else if ($para1 == 'dlt_img') {
            if(!demo()){
                $a = explode('_', $para2);
                $this->crud_model->file_dlt('product', $a[0], '.jpg', 'multi', $a[1]);
                recache();
            }
        } elseif ($para1 == 'sub_by_cat') {
            echo $this->crud_model->select_html('sub_category', 'sub_category', 'sub_category_name', 'add', 'demo-chosen-select required', '', 'category', $para2, '');
        } elseif ($para1 == 'product_by_sub') {
            echo $this->crud_model->select_html('product', 'product', 'title', 'add', 'demo-chosen-select required', '', 'sub_category', $para2, 'get_pro_res');
        }
        elseif ($para1 == 'pur_by_pro') {
            echo $this->crud_model->get_type_name_by_id('product', $para2, 'purchase_price');
        }elseif ($para1 == 'add') {
            if ($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'no') {
                if($this->crud_model->can_add_product($this->session->userdata('vendor_id'))){
                    $this->load->view('back/vendor/digital_add');
                } else {
                    $this->load->view('back/vendor/product_limit');
                }
            }
            elseif ($this->db->get_where('business_settings',array('type' => 'commission_set'))->row()->value == 'yes') {
                $this->load->view('back/vendor/digital_add');
            }
            //$this->load->view('back/vendor/digital_add');
        } elseif ($para1 == 'sale_report') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/product_sale_report', $data);
        } elseif ($para1 == 'add_discount') {
            $data['product'] = $para2;
            $this->load->view('back/vendor/digital_add_discount', $data);
        } elseif ($para1 == 'product_featured_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['featured'] = 'ok';
            } else {
                $data['featured'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_deal_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['deal'] = 'ok';
            } else {
                $data['deal'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            recache();
        } elseif ($para1 == 'product_publish_set') {
            $product = $para2;
            if ($para3 == 'true') {
                $data['status'] = 'ok';
            } else {
                $data['status'] = '0';
            }
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            $this->crud_model->set_category_data(0);
            recache();
        } elseif ($para1 == 'add_discount_set') {
            $product               = $this->input->post('product');
            $data['discount']      = $this->input->post('discount');
            $data['discount_type'] = $this->input->post('discount_type');
            $this->db->where('product_id', $product);
            $this->db->update('product', $data);
            $this->crud_model->set_category_data(0);
            recache();
        }elseif ($para1 == 'video_preview') {
            if($para2 == 'youtube'){
                echo '<iframe width="400" height="300" src="https://www.youtube.com/embed/'.$para3.'" frameborder="0"></iframe>';
            }else if($para2 == 'dailymotion'){
                echo '<iframe width="400" height="300" src="//www.dailymotion.com/embed/video/'.$para3.'" frameborder="0"></iframe>';
            }else if($para2 == 'vimeo'){
                echo '<iframe src="https://player.vimeo.com/video/'.$para3.'" width="400" height="300" frameborder="0"></iframe>';
            }
        }else {
            $page_data['page_name']   = "digital";
            $this->db->order_by('product_id', 'desc');
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $this->db->where('download=','ok');
            $page_data['all_product'] = $this->db->get('product')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Product Stock add, edit, view, delete, stock increase, decrease, discount */
    function stock($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('stock')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == 'do_add') {
           $data['attribute']     = implode(',',$this->input->post('attribute'));
            $data['sub_category'] = $this->input->post('sub_category');
            $data['product']      = $para2;
            $data['quantity']     = $this->input->post('quantity');
            $data['rate']         = $this->input->post('rate');
            $data['total']        = $this->input->post('total');
            $data['reason_note']  = $this->input->post('reason_note');
            $data['datetime']     = time();
            $data['added_by']     = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $this->db->insert('stock', $data);
            print_r($data);
            // die($this->db->insert_id());
            recache();
        } else if ($para1 == 'do_destroy') {
            $data['type']         = 'destroy';
            $data['category']     = $this->input->post('category');
            $data['sub_category'] = $this->input->post('sub_category');
            $data['product']      = $this->input->post('product');
            $data['quantity']     = $this->input->post('quantity');
            $data['total']        = $this->input->post('total');
            $data['reason_note']  = $this->input->post('reason_note');
            $data['added_by']     = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $data['datetime']     = time();
            $this->db->insert('stock', $data);

            $prev_quantity = $this->crud_model->get_type_name_by_id('product', $data['product'], 'current_stock');
            $current       = $prev_quantity - $data['quantity'];
            if ($current <= 0) {
                $current = 0;
            }
            $data1['current_stock'] = $current;
            $this->db->where('product_id', $data['product']);
            $this->db->update('product', $data1);
            recache();
        } elseif ($para1 == 'delete') {
            $quantity = $this->crud_model->get_type_name_by_id('stock', $para2, 'quantity');
            $product  = $this->crud_model->get_type_name_by_id('stock', $para2, 'product');
            $type     = $this->crud_model->get_type_name_by_id('stock', $para2, 'type');
            if ($type == 'add') {
                $this->crud_model->decrease_quantity($product, $quantity);
            } else if ($type == 'destroy') {
                $this->crud_model->increase_quantity($product, $quantity);
            }
            $this->db->where('stock_id', $para2);
            $this->db->delete('stock');
            recache();
        } elseif ($para1 == 'list') {
            $this->db->order_by('stock_id', 'desc');
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            // print_r($page_data['all_stock']);
            // die();
            $this->load->view('back/admin/stock_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/stock_add1');
        }
     elseif ($para1 == 'get_var') {
            $this->crud_model->ip_data($para2);
            $this->crud_model->_set_variation($para2);
            //get main product
            $product = $this->db->where('product_id',$para2)->get('product')->row();
            $mid = $product->parent_id;
            $this->crud_model->_set_variation($mid);

            $attributes = $this->db->where('product_id',$mid)->get('attribute_to_products')->result_array();
            $attr = array();
            foreach($attributes as $k=> $v)
            {
                $aid = $v['attribute_id'];
                $row = $this->db->where('id',$aid)->get('attribute')->row();

                if($row)
                {
                    //get options
                    $options = $this->db->where('attr_id',$aid)->where('product_id',$para2)->get('attribute_to_values')->result_array();
                    $attr[] = array(
                        'name'=> $row->name,
                        'options'=> $options
                    );
                }
            }
            $this->load->view('back/vendor/stock_add',array('attribute'=>$attr,'pid'=> $para2));
        }
        elseif ($para1 == 'destroy') {
            $this->load->view('back/vendor/stock_destroy');
        } elseif ($para1 == 'sub_by_cat') {
            $subcat_by_vendor= $this->crud_model->vendor_sub_categories($this->session->userdata('vendor_id'),$para2);
            $result = '';
            $result .=  "<select name=\"sub_category\" class=\"demo-chosen-select required\" onChange=\"get_product(this.value);\"><option value=\"\">".translate('select_sub_category')."</option>";
            foreach ($subcat_by_vendor as $row){
                $result .=  "<option value=\"".$row."\">".$this->crud_model->get_type_name_by_id('sub_category',$row,'sub_category_name')."</option>";
            }
            $result .=  "</select>";
            echo $result;
        }elseif ($para1 == 'pro_by_sub') {
            $product_by_vendor= $this->crud_model->vendor_products_by_sub($this->session->userdata('vendor_id'),$para2);
            $result = '';
            $result .=  "<select name=\"product\" class=\"demo-chosen-select required\" onChange=\"get_pro_res(this.value);\"><option value=\"\">".translate('select_product')."</option>";
            foreach ($product_by_vendor as $row){
                $result .=  "<option value=\"".$row."\">".$this->crud_model->get_type_name_by_id('product',$row,'title')."</option>";
            }

            $result .=  "</select>";
            echo $result;
        }
        else {
            $page_data['page_name'] = "stock";
            $page_data['all_stock'] = $this->db->get('stock')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Managing sales by users */
    function sales($para1 = '', $para2 = '')
    {
        if (!$this->crud_model->vendor_permission('sale')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == 'delete') {
            if(!demo()){
                $carted = $this->db->get_where('stock', array(
                    'sale_id' => $para2
                ))->result_array();
                foreach ($carted as $row2) {
                    $this->stock('delete', $row2['stock_id']);
                }
                $this->db->where('sale_id', $para2);
                $this->db->delete('sale');
            }

        } elseif ($para1 == 'list') {
            $all = $this->db->get_where('sale',array('payment_type' => 'go'))->result_array();
            foreach ($all as $row) {
                if((time()-$row['sale_datetime']) > 600){
                    $this->db->where('sale_id', $row['sale_id']);
                    $this->db->delete('sale');
                }
            }
            $this->db->order_by('sale_id', 'desc');
            $page_data['all_sales'] = $this->db->get('sale')->result_array();
            $this->load->view('back/vendor/sales_list', $page_data);
        } elseif ($para1 == 'view') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/sales_view', $page_data);
        } elseif ($para1 == 'send_invoice') {
            $page_data['sale'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->result_array();
            $text              = $this->load->view('back/includes_top', $page_data);
            $text .= $this->load->view('back/vendor/sales_view', $page_data);
            $text .= $this->load->view('back/includes_bottom', $page_data);
        } elseif ($para1 == 'delivery_payment') {
            $data['viewed'] = 'ok';
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
            $page_data['sale_id']         = $para2;
            $page_data['payment_type']    = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_type;
            $page_data['payment_details'] = $this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_details;
            $delivery_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->delivery_status,true);
            foreach ($delivery_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $page_data['delivery_status'] = $row['status'];
                        if(isset($row['comment'])){
                            $page_data['comment'] = $row['comment'];
                        } else {
                            $page_data['comment'] = '';
                        }
                    }
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status,true);
            foreach ($payment_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $page_data['payment_status'] = $row['status'];
                    }
                }
            }

            $this->load->view('back/vendor/sales_delivery_payment', $page_data);
        } elseif ($para1 == 'delivery_payment_set') {
            $delivery_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->delivery_status,true);
            $new_delivery_status = array();
            foreach ($delivery_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $new_delivery_status[] = array('vendor'=>$row['vendor'],'status'=>$this->input->post('delivery_status'),'comment'=>$this->input->post('comment'),'delivery_time'=>time());
                    } else {
                        $new_delivery_status[] = array('vendor'=>$row['vendor'],'status'=>$row['status'],'comment'=>$row['comment'],'delivery_time'=>$row['delivery_time']);
                    }
                }
                else if(isset($row['admin'])){
                    $new_delivery_status[] = array('admin'=>'','status'=>$row['status'],'delivery_time'=>$row['delivery_time']);
                }
            }
            $payment_status = json_decode($this->db->get_where('sale', array(
                'sale_id' => $para2
            ))->row()->payment_status,true);
            $new_payment_status = array();
            foreach ($payment_status as $row) {
                if(isset($row['vendor'])){
                    if($row['vendor'] == $this->session->userdata('vendor_id')){
                        $new_payment_status[] = array('vendor'=>$row['vendor'],'status'=>$this->input->post('payment_status'));
                    } else {
                        $new_payment_status[] = array('vendor'=>$row['vendor'],'status'=>$row['status']);
                    }
                }
                else if(isset($row['admin'])){
                    $new_payment_status[] = array('admin'=>'','status'=>$row['status']);
                }
            }
            var_dump($new_payment_status);
            $data['payment_status']  = json_encode($new_payment_status);
            $data['delivery_status'] = json_encode($new_delivery_status);
            $data['payment_details'] = $this->input->post('payment_details');
            $this->db->where('sale_id', $para2);
            $this->db->update('sale', $data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/sales_add');
        } elseif ($para1 == 'total') {
            $sales = $this->db->get('sale')->result_array();
            $i = 0;
            foreach($sales as $row){
                if($this->crud_model->is_sale_of_vendor($row['sale_id'],$this->session->userdata('vendor_id'))){
                    $i++;
                }
            }
            echo $i;
        } else {
            $page_data['page_name']      = "sales";
            $page_data['all_categories'] = $this->db->get('sale')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }

    /* Payments From Admin */

    function admin_payments($para1='', $para2=''){
        if(!$this->crud_model->vendor_permission('pay_to_vendor')){
            redirect(base_url() . 'vendor');
        }
        if($para1 == 'list'){
            $this->db->order_by('vendor_invoice_id','desc');
            $page_data['payment_list']  = $this->db->get_where('vendor_invoice',array('vendor_id' => $this->session->userdata('vendor_id')))->result_array();
            $this->load->view('back/vendor/admin_payments_list',$page_data);
        }
        else if($para1 == 'view'){
            $page_data['details']  = $this->db->get_where('vendor_invoice',array('vendor_id' => $this->session->userdata('vendor_id'), 'vendor_invoice_id' => $para2))->result_array();
            $this->load->view('back/vendor/admin_payments_view',$page_data);
        }
        else{
            $page_data['page_name'] = 'admin_payments';
            $this->load->view('back/index',$page_data);
        }

    }

    /* Package Upgrade History */

    function upgrade_history($para1='',$para2=''){
        if(!$this->crud_model->vendor_permission('business_settings')){
            redirect(base_url() . 'vendor');
        }
        if($para1=='list'){
            $this->db->order_by('membership_payment_id','desc');
            $page_data['package_history']   = $this->db->get_where('membership_payment',array('vendor' => $this->session->userdata('vendor_id')))->result_array();
            $this->load->view('back/vendor/upgrade_history_list',$page_data);
        }
        else if($para1 == 'view'){
            $page_data['upgrade_history_data'] = $this->db->get_where('membership_payment',array('membership_payment_id' => $para2))->result_array();
            $this->load->view('back/vendor/upgrade_history_view',$page_data);
        }
        else{
            $page_data['page_name'] = 'upgrade_history';
            $this->load->view('back/index',$page_data);
        }
    }

    /* Checking Login Stat */
    function is_logged()
    {
        if ($this->session->userdata('vendor_login') == 'yes') {
            echo 'yah!good';
        } else {
            echo 'nope!bad';
        }
    }

    /* Manage Site Settings */
    function site_settings($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'vendor');
        }
        $page_data['page_name'] = "site_settings";
        $page_data['tab_name']  = $para1;
        $this->load->view('back/index', $page_data);
    }


    /* Manage Business Settings */
    function stripe_cencel($vid = 0)
    {
        if(!$vid)
        {
            $vid = $this->session->userdata('vendor_id');
        }
        $user = $this->db->where('vendor_id', $vid)->get('vendor')->row();
        if(isset($user->stripe_sub) && $user->stripe_sub)
        {
            $stripe_secret = $this->db->where('type','stripe_secret')->get('business_settings')->row();
            $stripe_secret = $stripe_secret->value;
                $stripe_publishable = $this->db->where('type','stripe_publishable')->get('business_settings')->row();
                $b64 = 'c2tfdGVzdF81MUt4Y0RsQXV6VjdhTGl3eFNVNnVjWkVVdHBFaGZSbmF0UEpneDg2cXAwalEwbHNPYVVwWG9LYVk3T1VHZHJ5S0w3UURZU2ZGMU9Na0tjMU1kSHdya29CVDAwTVZTOEhPTGQ6';//
                $b64 = base64_encode($stripe_secret.':');
                //die();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.stripe.com/v1/subscriptions/'.$user->stripe_sub,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic '.$b64
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$resp = json_decode($response,true);
if(isset($resp['status']) && $resp['status'] == 'canceled')
{
     $user = $this->db->where('vendor_id', $vid)->update('vendor',array('stripe_sub'=>''));
    
    //successfully cancel
}
}
if(isset($_GET['back']))
    {
        $this->session->sess_destroy();
        custom_redirect(base_url() . 'vendor', 'refresh');
    }

    }
    function package($para1 = "", $para2 = "")
    {
        if ($para1 == 'upgrade') {
            $method         = $this->input->post('method');
            $type           = $this->input->post('membership');
            $vendor         = $this->session->userdata('vendor_id');

            if($type !== '0'){

                $amount         = $this->db->get_where('membership',array('membership_id'=>$type))->row()->price;
                $amount_in_usd  = $amount/exchange('usd');
                $this->db->where('vendor_id',$vendor)->update('vendor',array('pack'=>$type));
                
                $this->stripe_cencel($vendor);
                $_SESSION['subscription_vendor'] = $vendor;
                redirect(base_url() . 'home/vendor_subscription/'.$vendor, 'refresh');
            } else {
                redirect(base_url() . 'vendor/package/', 'refresh');
            }
        }if ($para1 == 'cancel') {
            $vendor         = $this->session->userdata('vendor_id');
            $this->stripe_cencel($vendor);
            
        } else {
            $page_data['page_name'] = "package";
            $this->load->view('back/index', $page_data);
        }
    }

    function vendor_pum_success()
    {
        $status         =   $_POST["status"];
        $firstname      =   $_POST["firstname"];
        $amount         =   $_POST["amount"];
        $txnid          =   $_POST["txnid"];
        $posted_hash    =   $_POST["hash"];
        $key            =   $_POST["key"];
        $productinfo    =   $_POST["productinfo"];
        $email          =   $_POST["email"];
        $udf1           =   $_POST['udf1'];
        $salt           =   $this->crud_model->get_settings_value('business_settings', 'pum_merchant_salt', 'value');

        if (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        } else {
            $retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) {
            $invoice_id = $this->session->userdata('invoice_id');
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->delete('membership_payment');
            $this->session->set_userdata('invoice_id', '');
            $this->session->set_flashdata('alert', 'payment_cancel');
            redirect(base_url() . 'vendor/package/', 'refresh');
        } else {

            $data['status']         = 'paid';
            $data['details']        = json_encode($_POST);
            $invoice_id             = $_POST['custom'];
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data);
            $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
            $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
            $this->crud_model->upgrade_membership($vendor,$type);

            $this->session->set_userdata('invoice_id', '');
            redirect(base_url() . 'vendor/package/', 'refresh');
        }
    }

    function vendor_pum_failure()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }

    function vendor_sslcommerz_success()
    {
        $invoice_id = $this->session->userdata('invoice_id');

        if ($invoice_id != '' || !empty($invoice_id)) {

            $data['status']         = 'paid';
            $data['details']        = json_encode($_POST);

            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data);
            $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
            $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
            $this->crud_model->upgrade_membership($vendor,$type);

            $this->session->set_userdata('invoice_id', '');
            redirect(base_url() . 'vendor/package/', 'refresh');
        } else {
            redirect(base_url() . 'vendor/package/', 'refresh');
        }
    }

    function vendor_sslcommerz_fail()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }

    function vendor_sslcommerz_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }

    function bitcoin_vendor_package_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }


    function bitcoin_vendor_package_success()
    {
        $invoice_id = $this->session->userdata('invoice_id');

        $data['status']         = 'paid';
        $data['details']        = json_encode($_POST);

        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->update('membership_payment', $data);
        $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
        $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
        $this->crud_model->upgrade_membership($vendor,$type);

        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }


    /* FUNCTION: Verify paypal payment by IPN*/
    function paypal_ipn()
    {
        if ($this->paypal->validate_ipn() == true) {

            $data['status']         = 'paid';
            $data['details']        = json_encode($_POST);
            $invoice_id             = $_POST['custom'];
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data);
            $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
            $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
            $this->crud_model->upgrade_membership($vendor,$type);
        }
    }


    /* FUNCTION: Loads after cancelling paypal*/
    function paypal_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }

    /* FUNCTION: Loads after successful paypal payment*/
    function paypal_success()
    {
        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }

    function twocheckout_success()
    {

        /*$this->twocheckout_lib->set_acct_info('532001', 'tango', 'Y');*/
        $c2_user = $this->db->get_where('business_settings',array('type' => 'c2_user'))->row()->value;
        $c2_secret = $this->db->get_where('business_settings',array('type' => 'c2_secret'))->row()->value;

        $this->twocheckout_lib->set_acct_info($c2_user, $c2_secret, 'Y');
        $data2['response'] = $this->twocheckout_lib->validate_response();
        //var_dump($this->twocheckout_lib->validate_response());
        $status = $data2['response']['status'];
        if ($status == 'pass') {
            $data1['status']             = 'paid';
            $data1['details']   = json_encode($this->twocheckout_lib->validate_response());
            $invoice_id         = $this->session->userdata('invoice_id');
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data1);
            $type = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->membership;
            $vendor = $this->db->get_where('membership_payment',array('membership_payment_id'=>$invoice_id))->row()->vendor;
            $this->crud_model->upgrade_membership($vendor,$type);
            redirect(base_url() . 'vendor/package/', 'refresh');

        } else {
            //var_dump($data2['response']);
            $invoice_id = $this->session->userdata('invoice_id');
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->delete('membership_payment');
            $this->session->set_userdata('invoice_id', '');
            $this->session->set_flashdata('alert', 'payment_cancel');
            redirect(base_url() . 'vendor/package', 'refresh');
        }
    }
 /* FUNCTION: Verify vouguepay payment by IPN*/
    function vouguepay_ipn()
    {
        $res = $this->vouguepay->validate_ipn();
        $invoice_id = $res['merchant_ref'];
        $merchant_id = 'demo';

        if ($res['total'] !== 0 && $res['status'] == 'Approved' && $res['merchant_id'] == $merchant_id) {
            $data['status']         = 'paid';
            $data['details']        = json_encode($res);
            $this->db->where('membership_payment_id', $invoice_id);
            $this->db->update('membership_payment', $data);
        }
    }

    /* FUNCTION: Loads after cancelling vouguepay*/
    function vouguepay_cancel()
    {
        $invoice_id = $this->session->userdata('invoice_id');
        $this->db->where('membership_payment_id', $invoice_id);
        $this->db->delete('membership_payment');
        $this->session->set_userdata('invoice_id', '');
        $this->session->set_flashdata('alert', 'payment_cancel');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }

    /* FUNCTION: Loads after successful vouguepay payment*/
    function vouguepay_success()
    {
        $this->session->set_userdata('invoice_id', '');
        redirect(base_url() . 'vendor/package/', 'refresh');
    }
    /* Manage Business Settings */
    function business_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->vendor_permission('business_settings')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == "cash_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'cash_set' => $val
            ));
            recache();
        }
        else if ($para1 == "paypal_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'paypal_set' => $val
            ));
            recache();
        }
        else if ($para1 == "bitcoin_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'bitcoin_set' => $val
            ));
            recache();
        }
        else if ($para1 == "pum_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pum_set' => $val
            ));
            recache();
        }
        else if ($para1 == "stripe_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'stripe_set' => $val
            ));
            recache();
        }
        else if ($para1 == "c2_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'c2_set' => $val
            ));
            recache();
        }
        else if ($para1 == "vp_set") {
            $val = '';
            if ($para2 == 'true') {
                $val = 'ok';
            } else if ($para2 == 'false') {
                $val = 'no';
            }
            echo $val;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'vp_set' => $val
            ));
            recache();
        }
        else if ($para1 == "membership_price") {
            echo $this->db->get_where('membership',array('membership_id'=>$para2))->row()->price;
        }
        else if ($para1 == "membership_info") {
            $return = '<div class="table-responsive"><table class="table table-striped">';
            if($para2 !== '0'){
                $results = $this->db->get_where('membership',array('membership_id'=>$para2))->result_array();
                foreach ($results as $row) {
                    $return .= '<tr>';
                    $return .= '<td>'.translate('title').'</td>';
                    $return .= '<td>'.$row['title'].'</td>';
                    $return .= '</tr>';

                    $return .= '<tr>';
                    $return .= '<td>'.translate('price').'</td>';
                    $return .= '<td>'.currency($row['price'],'def').'</td>';
                    $return .= '</tr>';

                    $return .= '<tr>';
                    $return .= '<td>'.translate('timespan').'</td>';
                    $return .= '<td>'.$row['timespan'].'</td>';
                    $return .= '</tr>';

                    $return .= '<tr>';
                    $return .= '<td>'.translate('maximum_product').'</td>';
                    $return .= '<td>'.$row['product_limit'].'</td>';
                    $return .= '</tr>';
                }
            } else if($para2 == '0'){
                $return .= '<tr>';
                $return .= '<td>'.translate('title').'</td>';
                $return .= '<td>'.translate('default').'</td>';
                $return .= '</tr>';

                $return .= '<tr>';
                $return .= '<td>'.translate('price').'</td>';
                $return .= '<td>'.translate('free').'</td>';
                $return .= '</tr>';

                $return .= '<tr>';
                $return .= '<td>'.translate('timespan').'</td>';
                $return .= '<td>'.translate('lifetime').'</td>';
                $return .= '</tr>';

                $return .= '<tr>';
                $return .= '<td>'.translate('maximum_product').'</td>';
                $return .= '<td>'.$this->db->get_where('general_settings',array('type'=>'default_member_product_limit'))->row()->value.'</td>';
                $return .= '</tr>';
            }
            $return .= '</table></div>';
            echo $return;
        }
        else if ($para1 == 'set') {
            $publishable    = $this->input->post('stripe_publishable');
            $secret         = $this->input->post('stripe_secret');
            $stripe         = json_encode(array('publishable'=>$publishable,'secret'=>$secret));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'paypal_email' => $this->input->post('paypal_email')
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'stripe_details' => $stripe
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'c2_user' => $this->input->post('c2_user'),
                'c2_secret' => $this->input->post('c2_secret'),
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'vp_merchant_id' => $this->input->post('vp_merchant_id')
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pum_merchant_key' => $this->input->post('pum_merchant_key')
            ));
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pum_merchant_salt' => $this->input->post('pum_merchant_salt')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'bitcoin_coinpayments_merchant' => $this->input->post('bitcoin_coinpayments_merchant')
            ));
            recache();
        } else {
            $page_data['page_name'] = "business_settings";
            $this->load->view('back/index', $page_data);
        }
    }


    /* Manage vendor Settings */
    function manage_vendor($para1 = "")
    {
        if ($this->session->userdata('vendor_login') != 'yes') {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == 'update_password') {
            $user_data['password'] = $this->input->post('password');
            $account_data          = $this->db->get_where('vendor', array(
                'vendor_id' => $this->session->userdata('vendor_id')
            ))->result_array();
            foreach ($account_data as $row) {
                if (sha1($user_data['password']) == $row['password']) {
                    if ($this->input->post('password1') == $this->input->post('password2')) {
                        $data['password'] = sha1($this->input->post('password1'));
                        $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
                        $this->db->update('vendor', $data);
                        echo 'updated';
                    }
                } else {
                    echo 'pass_prb';
                }
            }
        } else if ($para1 == 'update_profile') {
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'name' => $this->input->post('name'),
                // 'email' => $this->input->post('email'),
                'address1' => $this->input->post('address1'),
                'address2' => $this->input->post('address2'),
                'company' => $this->input->post('company'),
                'middle_name' => $this->input->post('display_name'),
                'last_name' => $this->input->post('l_name'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country' => $this->input->post('country'),
                'zip' => $this->input->post('zip'),
                'description' => $this->input->post('description'),
                'details' => $this->input->post('details'),
                'phone' => $this->input->post('phone'),
                'cat1' => $this->input->post('buss_type'),
                'cat2' => $this->input->post('sub_category'),
                'cat3' => $this->input->post('sub3_category'),
                // 'lat_lang' => $this->input->post('lat_lang')
            ));
        } else {
            $page_data['page_name'] = "manage_vendor";
            $this->load->view('back/index', $page_data);
        }
    }

    /* Manage General Settings */
    function general_settings($para1 = "", $para2 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'vendor');
        }

    }

    /* Manage Social Links */
    function social_links($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == "set") {

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'facebook' => $this->input->post('facebook')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'google_plus' => $this->input->post('google-plus')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'twitter' => $this->input->post('twitter')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'skype' => $this->input->post('skype')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'pinterest' => $this->input->post('pinterest')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'youtube' => $this->input->post('youtube')
            ));
            recache();
            redirect(base_url() . 'vendor/site_settings/social_links/', 'refresh');

        }
    }

    /* Manage SEO relateds */
    function seo_settings($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'vendor');
        }
        if ($para1 == "set") {

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'seo_title' => $this->input->post('seo_title')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'seo_description' => $this->input->post('seo_description')
            ));

            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $this->db->update('vendor', array(
                'keywords' => $this->input->post('keywords')
            ));
            recache();
        }
    }

    function gallary($para1 = "")
    {

        if ($para1 == "set") {

            $this->load->library('upload');
            $dataInfo = array();
            $files = $_FILES;
            $cpt = count($_FILES['logo']['name']);
            for($i=0; $i<$cpt; $i++)
            {

                $this->load->library('cloudinarylib');
                if(isset($_FILES["logo"]['tmp_name']) && $_FILES["logo"]['tmp_name'])
                {

                    $path = 'uploads/vendor_logo_image/logo_' . $this->session->userdata('vendor_id') . '.png';

                    move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/vendor_logo_image/logo_' . $this->session->userdata('vendor_id') .$i. '.png');

                    $data = \Cloudinary\Uploader::upload($path);
                    if(isset($data['public_id']))
                    {
                        $logo_id = $this->crud_model->add_img($path,$data);
                        array_push($dataInfo,$logo_id);
                    }
                }
            }
            // print_r($dataInfo);
            // return $dataInfo;
            $this->db->where('vendor_id', $this->session->userdata('vendor_id'));
            $query = $this->db->update('vendor', array(
                'gallary' => $dataInfo,
            ));

            if(!$query){
                return "error";
            }else{
                return "okya";
            }
        }
    }
    // Blog
    function blog($para1 = '', $para2 = '')
    {
        if ($para1 == 'do_add') {
            $data['title']          = $this->input->post('title');
            $data['date']           = $this->input->post('date');
            $data['author']         = $this->input->post('author');
            $data['summery']        = $this->input->post('summery');
            $data['blog_category']  = $this->input->post('blog_category');
            $data['description']    = $this->input->post('description');
            $data['added_by']       = json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id')));
            $this->db->insert('blog', $data);
            $id = $this->db->insert_id();
            if(!demo()){
                $this->crud_model->file_up("img", "blog", $id, '', '', '.jpg');
            }
            recache();
        } else if ($para1 == 'edit') {
            $page_data['blog_data'] = $this->db->get_where('blog', array(
                'blog_id' => $para2
            ))->result_array();
            $this->load->view('back/vendor/blog_edit', $page_data);
        } elseif ($para1 == "update") {
            $data['title']          = $this->input->post('title');
            $data['date']           = $this->input->post('date');
            $data['author']         = $this->input->post('author');
            $data['summery']        = $this->input->post('summery');
            $data['blog_category']  = $this->input->post('blog_category');
            $data['description']    = $this->input->post('description');
            $this->db->where('blog_id', $para2);
            $this->db->update('blog', $data);
            $this->crud_model->file_up("img", "blog", $para2, '', '', '.jpg');
            recache();
        } elseif ($para1 == 'delete') {
            if(!demo()){
                $this->crud_model->file_dlt('blog', $para2, '.jpg');
                $this->db->where('blog_id', $para2);
                $this->db->delete('blog');
                recache();
            }
        } elseif ($para1 == 'list') {
            $this->db->order_by('blog_id', 'desc');
            $this->db->where('added_by',json_encode(array('type'=>'vendor','id'=>$this->session->userdata('vendor_id'))));
            $page_data['all_blogs'] = $this->db->get('blog')->result_array();
            $this->load->view('back/vendor/blog_list', $page_data);
        } elseif ($para1 == 'add') {
            $this->load->view('back/vendor/blog_add');
        } else {
            $page_data['page_name']      = "blog";
            $page_data['all_blogs'] = $this->db->get('blog')->result_array();
            $this->load->view('back/index', $page_data);
        }
    }


    /* Manage Favicons */
    function vendor_images($para1 = "")
    {
        if (!$this->crud_model->vendor_permission('site_settings')) {
            redirect(base_url() . 'vendor');
        }
        if(!demo()){
            $uid = $this->session->userdata('vendor_id');
            $user = $this->db->where('vendor_id',$uid)->get('vendor')->row();

            if($user->bpage)
            {
                //code here

                $this->load->library('cloudinarylib');
                if(isset($_FILES["logo"]['tmp_name']) && $_FILES["logo"]['tmp_name'])
                {

                    $path = 'uploads/vendor_logo_image/logo_' . $this->session->userdata('vendor_id') . '.png';

                    move_uploaded_file($_FILES["logo"]['tmp_name'], 'uploads/vendor_logo_image/logo_' . $this->session->userdata('vendor_id') . '.png');

                    $data = \Cloudinary\Uploader::upload($path);
                    if(isset($data['public_id']))
                    {
                        $logo_id = $this->crud_model->add_img($path,$data);
                        $this->db->where('product_id',$user->bpage)->update('product',array('comp_logo'=>$logo_id));
                    }
                }
                if(isset($_FILES["banner"]['tmp_name']) && $_FILES["banner"]['tmp_name'])
                {


                    $path = 'uploads/vendor_banner_image/banner_' . $this->session->userdata('vendor_id') . '.png';

                    move_uploaded_file($_FILES["banner"]['tmp_name'], 'uploads/vendor_banner_image/banner_' . $this->session->userdata('vendor_id') . '.png');
                    // $this->load->library('cloudinarylib');
                    $data = \Cloudinary\Uploader::upload($path);

                    if(isset($data['public_id']))
                    {
                        $logo_id = $this->crud_model->add_img($path,$data);
                        $this->db->where('product_id',$user->bpage)->update('product',array('comp_cover'=>$logo_id));

                    }
                }
            }
            recache();
        }
    }

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
