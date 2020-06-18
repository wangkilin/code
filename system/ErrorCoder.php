<?php
use Classes\Factory;
/**
 * $Id$
 * $Revision$
 * $Author$
 * $LastChangedDate$
 *
 * @package
 * @version
 * @example $const = ErrorCoder::getErrorDescByCode(10000);
            echo $const;
 * @author Kilin WANG <wangzx@oradt.com>
 */
/**
 * 错误代码定义类
 * @final
 */
final class ErrorCoder
{
    CONST ERR_DESC_UNKNOWN_ERROR = 'Unknown error';

    ##########################################
    # 100~999  HTTP error
    ##########################################
    CONST ERR_BAD_REQUEST = 100;
    CONST ERR_DESC_100 = 'Bad request!';

    CONST ERR_HTTP_RESPONSE_ERROR = 101;
    CONST ERR_DESC_101 = 'Http responds error.';

    CONST ERR_HTTP_RESPONSE_CODE_ERROR = 102;
    CONST ERR_DESC_102 = 'Http responds error.';
    ##########################################
    # 1000~9999  File operation error
    ##########################################
    CONST ERR_FILE_EXISTING = 1000;
    CONST ERR_DESC_1000 = 'File is existing!';
    CONST ERR_FILE_OPEN_REMOTE_FAILED = 1001;
    CONST ERR_DESC_1001 = 'Open remote file failed!';
    CONST ERR_FILE_CANNOT_MKDIR = 1002;
    CONST ERR_DESC_1002 = 'Can not create dir!';
    CONST ERR_FILE_CANNOT_WRITE = 1003;
    CONST ERR_DESC_1003 = 'Can not write file!';
    CONST ERR_FILE_NOT_PERMISSION = 1004;
    CONST ERR_DESC_1004 = 'user not permission enter the module!';


    ##########################################
    # 10000~99999  前台用户错误信息
    ##########################################
    CONST ERR_LOGIN_USERNAME_WRONG = 10000; // 用户名不存在
    CONST ERR_DESC_10000 = 'User does not exist!';
    CONST ERR_LOGIN_PASSWORD_WRONG = 100010; // 用户密码错误
    CONST ERR_DESC_100010 = 'Password is wrong!';
    CONST ERR_LOGIN_WRONG_MORE_THAN_NUMBER = 10002; // 用户密码错误次数超过指定限制
    CONST ERR_DESC_10002 = 'Password is wrong and number is more than limited!';
    CONST ERR_ENT_USERNAME_EXIST = 200005; //企业登陆用户名已经存在
    CONST ERR_DESC_200005    = 'Username is exists';
    CONST ERR_ENT_NAME_EXIST	= 200006;//企业名称已经存在
    CONST ERR_DESC_200006		= 'company is exists';
    CONST ERR_ENT_EMAIL_EXIST   = 999024;//企业邮箱已经存在（new version username=email）
	CONST ERR_DESC_999024       = 'company emal is exist';
    CONST ERR_BIZ_DELIVERY_ACCOUNT_NOT_EXISTS = 999020; //分配时账号不存在
    CONST ERR_DESC_999020 = 'account not exists';
    CONST ERR_OPERATOR_MOBILE_EXISTS = 999023; //运维人员手机号已经存在
    CONST ERR_DESC_999023 = 'mobile is exists';
    CONST ERR_PERSON_MOBILE_NOT_EXISTS = 999004; //个人模块手机号码不存在
    CONST ERR_DESC_999004 = 'data is not exists';
    //CONST ERR_PERSON_REGSITER_MOBILE_EXISTS = 999022; //个人模块手机号码存在
    //CONST ERR_DESC_999022 = 'mobile is  exists';
    CONST ERR_PERSON_REG_MOBILE_EXISTS = 300002; //个人模块手机号码存在
    CONST ERR_DESC_300002 = 'mobile is  exists';
    CONST ERR_session_expired = 100006; //用户在其他地方已经登录
    CONST ERR_DESC_100006 = 'user logined others';

    ##########################################
    # 100000~999999  6位码为API接口端错误
    ##########################################
    CONST ERR_API_ERROR_UNKNOWN = 100001;
    CONST ERR_DESC_100001 = 'Unknown error';
    CONST ERR_API_INVALID_USER = 100002;
    CONST ERR_DESC_100002 = 'invalid user';
    CONST ERR_API_ERROR_PASSWORD = 100003;
    CONST ERR_DESC_100003 = 'password not correct';
    CONST ERR_API_ERROR_EMAIL = 100004;
    CONST ERR_DESC_100004 = 'email not correct';
    CONST ERR_API_ERROR_MOBILE = 100005;
    CONST ERR_DESC_100005 = 'mobile not correct';
    CONST ERR_API_ERROT_UNTOKEN = 100007;
    CONST ERR_DESC_100007 = 'miss access token';
    CONST ERR_API_ERROE_PHONE = 999002;
    CONST ERR_DESC_999002 = 'parameter not enough';
	CONST ERR_API_ERROE_PARAMETER_NOT_ENOUGH = 999003;
    CONST ERR_DESC_999003 = 'parameter not enough';
    CONST ERR_API_ERROE_DATA_EXISTS = 999005;
    CONST ERR_DESC_999005 = 'data exists';

    /*
     * 错误代码
     * @var int
     */
    private $_errorCode = null;

    /*
     * 错误描述
     * @var string
     */
    private $_errorDesc = '';

    /**
     * 基于错误代码获取本类实例化模型
     * @param int $errorCode 错误代码
     *
     * @return ErrorCoder
     */
    static public function getInstance($errorCode)
    {
        $errorDescModel = new ErrorCoder($errorCode);

        return $errorDescModel;
    }

    /**
     * 构造函数. 基于错误代码，重置错误信息
     * @param int $errorCode 错误代码
     * @param string $errorMsg 错误描述信息，可以不传递,默认为空字符串
     */
    public function __construct($errorCode,$errorMsg='')
    {
        $this->reset();
        $errorMsg = trim($errorMsg);
        if(empty($errorMsg)){//错误描述参数不传递时
        	$this->setErrorCode($errorCode);
        }else{//错误描述信息非空时执行
        	$this->setError($errorCode,$errorMsg);
        }
    }

    /**
     * 重置错误信息和错误代码
     */
    public function reset()
    {
        $this->_errorCode = null;
        $this->_errorDesc = '';
    }

    /**
     * 设置错误编码
     * @param int $code 错误编码
     *
     * @return ErrorCoder
     */
    public function setErrorCode($code)
    {
        if(self::getErrorDescByCode($code)) {
            $this->_errorCode = $code;
            $this->_errorDesc = self::getErrorDescByCode($code);
        }

        return $this;
    }

    /**
     * 设置错误编码和错误信息
     * @param unknown $code 错误编码
     * @param unknown $desc 错误信息
     * @return ErrorCoder
     */
    public function setError($code,$desc='')
    {
    	if(self::getErrorDescByCode($code)) {
    		$this->_errorCode = $code;
    		$errDesc = self::getErrorDescByCode($code);
    		if(self::ERR_DESC_UNKNOWN_ERROR == $errDesc && $desc){
    			$this->_errorDesc = $desc;
    		}else{
    			$this->_errorDesc = $errDesc;
    		}
    	}
    	return $this;
    }

    /**
     * 自定义错误描述信息
     * @param string $errorDesc 自定义错误信息
     * @return ErrorCoder
     */
    public function setErrorDesc($errorDesc)
    {
        $this->_errorDesc = $errorDesc;

        return $this;
    }

    /**
     * 获取错误信息
     * @return array
     */
    public function getError()
    {
        if($this->_errorCode) {
            $error = array('code'=>$this->_errorCode, 'desc'=>$this->_errorDesc);
        } else {
            $error = array();
        }

        return $error;
    }

    /**
     * 获取错误描述
     *
     * @return string
     */
    public function getErrorDesc()
    {
        return $this->_errorDesc;
    }

    /**
     * 获取错误代码
     * @return int
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * 基于错误代码获取描述信息
     * @param int $errorCode 错误代码
     * @return string|null
     */
    static public function getErrorDescByCode($errorCode)
    {
        $errorDesc = null;
        $reflectionClass = new ReflectionClass(__CLASS__);
        $constantList = $reflectionClass->getConstants();


        if(isset($constantList['ERR_DESC_'.$errorCode])) {
            $errorDesc = $constantList['ERR_DESC_'.$errorCode];
        }

        if(!empty($errorDesc)) {
            return $errorDesc;
        }

        return self::ERR_DESC_UNKNOWN_ERROR;
    }

    /**
     * 魔幻函数。 将类实例转换成字符串
     *
     * @return string
     */
    public function __toString()
    {
        $errorInfo = $this->getError();
        if(isset($errorInfo['desc'])) {
            return 'Error: ' . $errorInfo['code'] . ' - ' . $errorInfo['desc'];
        }

        return '';
    }
}
