<?php

/**
 * @file:  BaseController.class.php
 * @brief:  控制器基类,
 * @author:  Mark.Pan
 * @version:  0.1
 * @date:  2015-08-22
 */
abstract class BaseController extends Controller
{
	
	public function init(){
						
		if(!IS_POST) return true; //默认过滤方式: POST
		foreach (static::filter() as $key => $actList) {
			if (in_array(doit::$params[0], $actList)) {
				switch ($key) {
					case 'token': //检查令牌
						//UserController::checkToken($this->post('token'));//默认 post
						break;
					case 'login': //检查登录
						!UserController::isLogin() && EC::fail(EC_NOT_LOGIN);
						break;
				}
			}
		}
		return true;
	}
	
    /**
     * @brief:  获取$_SERVER['REQUEST_URI'] 里?号及其左边的部分
     * @return:
     */
    protected function getUriRoot()
    {
        $pos = strpos($_SERVER['REQUEST_URI'], '?');
        if ($pos !== false) {
            $uri_root = substr($_SERVER['REQUEST_URI'], 0, $pos);
        } else {
            $uri_root = $_SERVER['REQUEST_URI'];
        }
        return $uri_root . '?';
    }

    /**
     * @brief:  从当前的URI中过滤掉指定的GET参数，然后&连接后的URL参数字符串
     * @param:  $filterParam
     * @example:
     * @return:
     */
    protected function getQueryString($filterParam = [])
    {
        if (!is_array($filterParam)) {
            $filterParam = [$filterParam];
        }
        $temp = [];
        foreach ($_GET as $k => $v) {
            if (!in_array($k, $filterParam) && $v !== '') {
                $temp[] = "{$k}={$v}";
            }
        }
        return join('&', $temp) . '&';
        /*
        $queryString = $_SERVER['QUERY_STRING'] ? '&' . $_SERVER['QUERY_STRING'] : '';
        $queryString = preg_replace("/&?{$filterParam}=[0-9]/", '', $queryString);
        $queryString = trim($queryString, '&');
        ($queryString) && ($queryString = $queryString . '&');
        return  $queryString;
         */
    }

    /**
     * @brief:  输出错误提示并返回
     * @param:  $messge
     * @return:
     */
    protected function fail($messge, $url = '')
    {
        if (!$url) {
            die("<script>alert('{$messge}');history.go(-1);</script>");
        }
        die("<script>alert('{$messge}'); window.location='{$url}';</script>");
    }

    /**
     * @brief:  成功
     * @param:  $messge
     * @return:
     */
    protected function success($messge, $url)
    {
        die("<script>alert('{$messge}'); window.location='{$url}';</script>");
    }


    /**
     * @brief:  获取登录的卖家ID  -- 先用着，其实有下面那个总的数据，这个有点多余
     * @return:
     */
    protected function getLoginSellerId()
    {
        $user = $this->getLoginSeller();
        return $user['id'];       
    }

    protected function getLoginSellerAccount()
    {
        $user = $this->getLoginSeller();
        return $user['tel'];        
    }

    /**
     * @brief:  获取登录的卖家信息
     * @return:
     */
    protected function getLoginSeller()
    {
        $res = Controller::model('user')->isLogin();
        $user = $res['data'];
        unset($user['password']);
        return $user;
    }

    /**
     * @brief:  把原框架的分页方式，在包了一些，解决翻页跟参数的问题。
     * @param:  $page
     * @param:  $total
     * @param:  $numPerPage
     * @return:
     */
    protected function getPageHtml($page, $total, $numPerPage = 10)
    {
        if (!$page || !$total) {
            return '';
        }
        $pager = $this->instance('pager');

        //$new_uri = $this->filterUri( 'page' );

        $uri_root = $this->getUriRoot();
        $queryString = $this->getQueryString('page');

        //return $pager->total( $total )->num( $numPerPage )->page( $page )->url( "{$new_uri}page=" )->output();
        return $pager->total($total)->num($numPerPage)->page($page)->url("{$uri_root}{$queryString}page=")->output();
    }


    /**
     * @brief:  输出规定格式的JSON错误响应
     * @param:  $msg
     * @param:  $data
     * @return:
     */
    protected function jsonFail($msg = '', $data = [])
    {
        $this->outJson(-1, $msg, $data);
    }

    /**
     * @brief:  输出规定格式的JSON 成功响应
     * @param:  $msg
     * @param:  $data
     * @return:
     */
    protected function jsonSuccess($msg = '', $data = [])
    {
        $this->outJson(0, $msg, $data);
    }

    /**
     * @brief:  结算进程前，输出接JSON结果
     * @param:  $code
     * @param:  $msg
     * @param:  $data
     * @return:
     */
    protected function outJson($code = -1, $msg = '', $data = [])
    {
        die(json_encode(['code' => $code, 'msg' => $msg, 'data' => $data]));
    }

    public function getPostDataJson()
    {
        //获取POST数据
        $post_data_1 = file_get_contents("php://input");
        $post_data_2 = $GLOBALS['HTTP_RAW_POST_DATA'];
        $post_data = $post_data_1 == '' ? $post_data_2 : $post_data_1;
        Log::notice("getPostDataJson====================>>>PostDataJson=##" . json_encode($post_data) . "##"); // toStirng
        return self::objectToArray(json_decode($post_data)); // toJson
    }

    public function objectToArray($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::objectToArray($value);
            }
        }
        return $array;
    }
    
	private static $_uniqueLoginUser = NULL;
    public static function getUniqueLoginUser(){
    	$loginUser = NULL;
    	
    	if(NULL !== self::$_uniqueLoginUser){
    		return self::$_uniqueLoginUser;
    	}
    	if(UserController::isLogin()){
    		$loginUser = UserController::getLoginUser();
    	}elseif(AdminController::isAdmin()){
    		$loginUser = AdminController::getLoginUser();
    	}    	
    	if(!empty($loginUser) && is_array($loginUser)){
    		self::$_uniqueLoginUser = $loginUser;
    	}
    	return self::$_uniqueLoginUser;
    }

    private static $_currentUserId = NULL;
    public static function getCurrentUserId()
    {
    	if(NULL !== self::$_currentUserId){
    		return self::$_currentUserId;
    	}
    	$loginUser = array();
    	$loginUser = self::getUniqueLoginUser();
    	if(!empty($loginUser) && is_array($loginUser) && isset($loginUser['usercode'])){
    		self::$_currentUserId = $loginUser['usercode'];
    	}    	
                
        if (empty(self::$_currentUserId)) {
            Log::error("getLoginUser_data=========>>>loginUser_data=##" . json_encode($loginUser) . "##"); // toStirng
        }
        return self::$_currentUserId;
    }
    
    private static $_uniqueLoginkeyValue = NULL;
    public static function getUniqueLoginkey(){
    	if(NULL !== self::$_uniqueLoginkeyValue){
    		return self::$_uniqueLoginkeyValue;
    	}
    	$loginUser = array();
    	$loginUser = self::getUniqueLoginUser();    	
    	
    	if(!empty($loginUser) && is_array($loginUser) && isset($loginUser[UserController::$_loginKeyName])){
    		return self::$_uniqueLoginkeyValue = $loginUser[UserController::$_loginKeyName];
    	}    	
    	return NULL;
    }


    /**
     * @return array['code','filePath','fileName']
     */
    public static function uploadFile()
    {
        $type = ['image/png', 'image/jpg', 'image/jpeg'];
        $res = ['code' => EC_OK, 'filePath' => '', 'fileName' => ''];
        try {
            if ($_FILES['file']['error']) {
                $res['code'] = EC_OTH;
                return $res;
            } else if (!$_FILES['file']['name']) {
                $res['code'] = EC_UPL_FILE_NON;
                return $res;
            } else if (!in_array($_FILES['file']['type'], $type)) {
                $res['code'] = EC_UPL_FILE_TYPE_ERR;
                return $res;
            }

            $uploadFileDir = self::getConfig('conf')['attachment_file_path'];
            $res['fileName'] = $_FILES['file']['name'];
            $res['filePath'] = $uploadFileDir . 'F_' . date('YmdHis', time()) . '_' . rand(1, 9999);
            move_uploaded_file($_FILES['file']['tmp_name'], $res['filePath']);
        } catch (Exception $e) {
            Log::error('uploadFile error msg (' . $e->getMessage() . ')');
            EC::fail(EC_OTH);
        }
        return $res;
    }

    /**
     * 默认不对任何action过滤
     * @return array
     */
    public static function filter()
    {
        return [];
    }

    //获取商铺编号
    public function getMCH_NO()
    {
        if(!$MCH_NO = self::getConfig('conf')['MCH_NO']){
            Log::error('bcsRegister create MCH_NO miss');
            EC::fail(EC_CNF_NON);
        }
        
        return $MCH_NO;
    }
    
    //解密网页数据加密、base64
    public function decrypt($data)
    {
        $string      = '';
        $data        = base64_decode($data);
        $conf        = Controller::getConfig('conf');
        $private_key = openssl_pkey_get_private($conf['private_key']);   
        
        openssl_private_decrypt($data, $string, $private_key);        
        return $string;
    }
}
