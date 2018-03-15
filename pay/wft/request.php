<?php
/**
 * 支付接口调测例子
 * ================================================================
 * index 进入口，方法中转
 * submitOrderInfo 提交订单信息
 * queryOrder 查询订单
 * 
 * ================================================================
 */
require('Utils.class.php');
require('../../configs/config.php');
require('../config.inc.php');
require('class/RequestHandler.class.php');
require('class/ClientResponseHandler.class.php');
require('class/PayHttpClient.class.php');

Class Request{
    //$url = 'http://192.168.1.185:9000/pay/gateway';

    private $resHandler = null;
    private $reqHandler = null;
    private $pay = null;
    private $cfg = null;
    
    public function __construct(){
        $this->Request();
    }

    public function Request(){
        $this->resHandler = new ClientResponseHandler();
        $this->reqHandler = new RequestHandler();
        $this->pay = new PayHttpClient();
        $this->cfg = new Config();

        $this->xml   = file_get_contents('php://input');
        if(!empty($this->xml)){
            $xs = xmlToArray($this->xml);
            $this->ptype        = $xs["ptype"]?$xs["ptype"]:"";
            $this->notify_url   = $xs["notify_url"]?$xs["notify_url"]:"";
            $this->attach       = base_code($xs["attach"]);
        }else{
            $this->ptype      = $_POST["ptype"]?$_POST["ptype"]:"";
            $this->wap        = $_POST["wap"]?$_POST["wap"]:1;
            $this->service    = ($_POST["wap"]==2)?"pay.weixin.wappay":"unified.trade.pay";
            $this->notify_url = $_POST["notify_url"]?$_POST["notify_url"]:"";
        }
        $this->reqHandler->setGateUrl($this->cfg->P($this->ptype,"url"));
        $this->reqHandler->setKey($this->cfg->P($this->ptype,"key"));
    }
    
    public function index(){
        $method = isset($_REQUEST['method'])?$_REQUEST['method']:'submitOrderInfo';
        switch($method){
            case 'submitOrderInfo'://提交订单
                $this->submitOrderInfo();
            break;
            case 'queryOrder'://查询订单
                $this->queryOrder();
            break;
            case 'submitRefund'://提交退款
                $this->submitRefund();
            break;
            case 'queryRefund'://查询退款
                $this->queryRefund();
            break;
            case 'callback':
                $this->callback();
            break;
        }
    }
    
    /**
     * 提交订单信息
     */
    public function submitOrderInfo(){
        $this->reqHandler->setReqParams($_POST,array('method'));//接收页面传过来的参数，如订单号，金额那些 
        $this->reqHandler->setParameter('service',$this->service);//接口类型

        $this->reqHandler->setParameter('mch_id',$this->cfg->P($this->ptype,"mchId"));//必填项，商户号，由平台分配
        $this->reqHandler->setParameter('version','2.0');
        $this->reqHandler->setParameter('limit_credit_pay','1');   //是否支持信用卡，1为不支持，0为支持
		$this->reqHandler->setParameter('device_info','AND_SDK');//应用类型
		$this->reqHandler->setParameter('mch_app_name','Spad');//应用名
		$this->reqHandler->setParameter('mch_app_id','com.pass.pay');//应用标识
        //通知地址，必填项，接收平台通知的URL
        $this->reqHandler->setParameter('notify_url',$this->notify_url);
		// $this->reqHandler->setParameter('notify_url',' ');//此处默认是空格，商户需传自己的通知地址，保证外网能正常访问到
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名
        $data = Utils::toXml($this->reqHandler->getAllParameters());

        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                //当返回状态与业务结果都为0时才返回，其它结果请查看接口文档
                if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
                    if($this->wap == 1){
                        echo json_encode(array('status'=>100,'token_id'=>$this->resHandler->getParameter('token_id'),
						                   'services'=>$this->resHandler->getParameter('services'),'pay_info'=>"",'params'=>""));
                    }else{
                        echo json_encode(array('status'=>100,'token_id'=>"",'services'=>"",'params'=>"",'pay_info'=>$this->resHandler->getParameter('pay_info')));
                    }
                                           
                    exit();
                }else{
                    echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('err_code').' Error Message:'.$this->resHandler->getParameter('err_msg')));
                    exit();
                }
            }
            echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message')));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }


    /**
     * 查询订单
     */
    public function queryOrder(){
        $this->reqHandler->setReqParams($_POST,array('method'));
        $reqParam = $this->reqHandler->getAllParameters();
        if(empty($reqParam['transaction_id']) && empty($reqParam['out_trade_no'])){
            echo json_encode(array('status'=>500,
                                   'msg'=>'请输入商户订单号,平台订单号!'));
            exit();
        }

        $this->reqHandler->setParameter('version',$this->cfg->P($this->ptype,"version"));
        $this->reqHandler->setParameter('service','unified.trade.query');//接口类型
        $this->reqHandler->setParameter('mch_id',$this->cfg->P($this->ptype,"mchId"));//必填项，商户号，由平台分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
        $this->reqHandler->createSign();//创建签名
        $data = Utils::toXml($this->reqHandler->getAllParameters());

        $this->pay->setReqContent($this->reqHandler->getGateURL(),$data);
        if($this->pay->call()){
            $this->resHandler->setContent($this->pay->getResContent());
            $this->resHandler->setKey($this->reqHandler->getKey());
            if($this->resHandler->isTenpaySign()){
                $res = $this->resHandler->getAllParameters();
                // Utils::dataRecodes('查询订单',$res);
                //支付成功会输出更多参数，详情请查看文档中的7.1.4返回结果
                echo json_encode(array('status'=>200,'data'=>$res));
                exit();
            }
            echo json_encode(array('status'=>500,'msg'=>'Error Code:'.$this->resHandler->getParameter('status').' Error Message:'.$this->resHandler->getParameter('message')));
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }
    

    /**
     * 界面显示
     */
    public function queryRefund(){
       
        
    }
    
    /**
     * 异步通知方法，demo中将参数显示在result.txt文件中
     */
    public function callback(){
        $this->resHandler->setContent($this->xml);
        $this->resHandler->setKey($this->cfg->P($this->ptype,"key"));
        if($this->resHandler->isTenpaySign()){
            if($this->resHandler->getParameter('status') == 0 && $this->resHandler->getParameter('result_code') == 0){
				//echo $this->resHandler->getParameter('status');
				// 11;
				//更改订单状态
				
				
                Utils::dataRecodes('接口回调收到通知参数',$this->resHandler->getAllParameters());
                echo 'success';
                exit();
            }else{
                echo 'failure1';
                exit();
            }
        }else{
            echo 'failure2';
        }
    }
}

$req = new Request();
$req->index();
?>
