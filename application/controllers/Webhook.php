<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Webhook extends CI_Controller
{

    
    function __construct()
    {
        
        parent::__construct();
        $this->load->database();
        
    }
    public function payment_webhook()
    {
        $payload = @file_get_contents('php://input');
        $data = json_decode($payload,true);
        
        if(isset($data['data']['object']['subscription_details']['metadata']['track']) && $data['type'] == 'invoice.payment_succeeded')
        {
            
            $meta_data = $data['data']['object']['subscription_details']['metadata'];
            $r = $this->db->insert('stripe_webhook',array('data'=>$payload,'track'=>$meta_data['track'],
            'uid'=>$meta_data['customer_id'],'type'=>$data['type']));
            $meta_data = $data['data']['object']['subscription_details']['metadata'];
            $uid = $meta_data['customer_id'];
            $track = $meta_data['track'];
            $plan_id = $meta_data['plan_id'];
            
            $stripe_sub = $data['data']['object']['id'];
            $pack = $this->db->where('membership_id',$plan_id)->get('membership')->row();
            $exp = '';
            if($pack->timespan)
                            {
                                $date = date('Y-m-d H:i:s');
                                $exp = strtotime(date('Y-m-d H:i:s', strtotime($date. ' + '.$pack->timespan.' days'))); 
                            }
            $v = $this->db->where('vendor_id', $uid)->update('vendor',array('membership'=> $plan_id,'stripe_sub'=> $stripe_sub,'member_expire_timestamp'=>$exp));
            $vendor = $this->db->where('vendor_id',$uid)->get('vendor')->row();
            $this->email_model->payment_success($vendor->email,"&#xA3;".$pack->price,14) ;
        }
        elseif(isset($data['data']['object']['subscription_details']['metadata']['track']) && $data['type'] == 'invoice.payment_succeeded')
        {
            $r = $this->db->insert('stripe_webhook',array('data'=>$payload,'track'=>$data['data']['object']['subscription_details']['metadata']['track'],'uid'=>$data['data']['object']['subscription_details']['metadata']['customer_id'],'type'=>$data['type']));
            
            $track = $data['data']['object']['subscription_details']['metadata']['track'];
            $plan_id = $data['data']['object']['metadata']['plan_id'];
            
            $stripe_sub = $data['data']['object']['id'];
            $pack = $this->db->where('membership_id',$plan_id)->get('membership')->row();
            $exp = '';
            if($pack->timespan)
                            {
                                $date = date('Y-m-d H:i:s');
                                $exp = strtotime(date('Y-m-d H:i:s', strtotime($date. ' + '.$pack->timespan.' days'))); 
                            }
                            
            $v = $this->db->where('vendor_id', $pack->vendor)->update('vendor',array('membership'=> $plan_id,'stripe_sub'=> $stripe_sub,'member_expire_timestamp'=>$exp));
            $vendor = $this->db->where('vendor_id',$pack->vendor)->get('vendor')->row();
            $this->email_model->payment_success($vendor->email,"&#xA3;".$pack->amount,14) ;
        }
        else if(isset($data['data']['object']['metadata']['track']) && $data['type'] == 'customer.subscription.created')
        {
            $r = $this->db->insert('stripe_webhook',array('data'=>$payload,'track'=>$data['data']['object']['metadata']['track'],'uid'=>$data['data']['object']['metadata']['customer_id'],'type'=>$data['type']));
            $track = $data['data']['object']['metadata']['track'];
            $vid = $data['data']['object']['metadata']['customer_id'];
            $plan_id = $data['data']['object']['metadata']['plan_id'];
            
            $stripe_sub = $data['data']['object']['id'];
            $pack = $this->db->where('membership_id',$plan_id)->get('membership')->row();
            $exp = '';
            if($pack->timespan)
                            {
                                $date = date('Y-m-d H:i:s');
                                $exp = strtotime(date('Y-m-d H:i:s', strtotime($date. ' + '.$pack->timespan.' days'))); 
                            }
            $v = $this->db->where('vendor_id', $vid)->update('vendor',array('membership'=> $plan_id,'stripe_sub'=> $stripe_sub,'member_expire_timestamp'=>$exp));
            $r = $this->email_model->verifiction_email($vid,'vendor');
            var_dump($r);
            var_dump('vendor id is'.$vid);
            $vendor = $this->db->where('vendor_id',$vid)->get('vendor')->row();
            $this->email_model->account_opening('vendor', $vendor->email, $password);
            $this->email_model->vendor_reg_email_to_admin($vendor->email, $password);
        }
        else if(isset($data['data']['object']['metadata']['track']) && $data['type'] == 'customer.subscription.deleted')
        {
            $r = $this->db->insert('stripe_webhook',array('data'=>$payload,'track'=>$data['data']['object']['subscription_details']['metadata']['track'],'uid'=>$data['data']['object']['subscription_details']['metadata']['customer_id'],'type'=>$data['type']));
            $stripe_sub = $data['data']['object']['id'];
            $vid = $data['data']['object']['metadata']['customer_id'];
            $date = date('Y-m-d');
            $vendor = $this->db->where('vendor_id',$vid)->get('vendor')->row();
                                $exp = strtotime($date); 
            $v = $this->db->where('vendor_id', $vid)->update('vendor',array('stripe_sub'=>NULL,'membership'=>0,'member_expire_timestamp'=>0));
            $p = $this->db->where('added_by','{"type":"vendor","id":'.$vid.'}')->update('product',array('status'=>'draft'));
            $this->email_model->subscription_cancellation($vendor->email) ;
            var_dump($vendor->email);
            var_dump($p);
            var_dump($stripe_sub);
            var_dump($v);
            var_dump($vid.'vendor id');
            var_dump($data['data']['object']['metadata']);
        }
        echo "Success";
        exit();
    }
    

}

