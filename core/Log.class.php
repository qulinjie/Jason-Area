<?php
/**
 * Log.class.php
 *
 * DoitPHP日志管理
 * @author tommy <streen003@gmail.com>
 * @copyright  Copyright (c) 2010 Tommy Software Studio
 * @link http://www.doitphp.com
 * @license New BSD License.{@link http://www.opensource.org/licenses/bsd-license.php}
 * @version $Id: Log.class.php 1.3 2010-11-13 20:28:00Z tommy $
 * @package core
 * @since 1.0
 */

if (!defined('IN_DOIT')) {
    exit();
}

class Log extends Base {

	public static function error($message){
		Log::write($message);
	}
	public static function notice($message){
		Log::write($message, 'Notice');
	}
	public static function warning($message){
		Log::write($message, 'Warning');
	}

    //bank log 定义三种级别
    public static function bcsError($message){
        Log::write($message);
        Log::write($message,'Error','bcs-'.date('Y-m-d'));
    }
    public static function bcsNotice($message){
        Log::write($message, 'Notice');
        Log::write($message, 'Notice','bcs-'.date('Y-m-d'));
    }
    public static function bcsWarning($message){
        Log::write($message, 'Warning');
        Log::write($message, 'Warning','bcs-'.date('Y-m-d'));
    }
    
    public static function spdError($message){
        Log::write($message);
        Log::write($message, 'Error','spd-'.date('Y-m-d'));
    }
    public static function spdNotice($message){
        Log::write($message, 'Notice');
        Log::write($message, 'Notice','spd-'.date('Y-m-d'));
    }
    public static function spdWarning($message){
        Log::write($message, 'Warning');
        Log::write($message, 'Warning','spd-'.date('Y-m-d'));
    }
    
    //付款 log 定义三种级别
    public static function fkError($message){
    	Log::write($message);
    	Log::write($message, 'Error','fk-'.date('Y-m-d'));
    }
    public static function fkNotice($message){
    	Log::write($message, 'Notice');
    	Log::write($message, 'Notice','fk-'.date('Y-m-d'));
    }
    public static function fkWarning($message){
    	Log::write($message, 'Warning');
    	Log::write($message, 'Warning','fk-'.date('Y-m-d'));
    }
    
    //同步付款单 log 定义三种级别
    public static function fkdError($message){
    	Log::write($message);
    	Log::write($message, 'Error','fkd-'.date('Y-m-d'));
    }
    public static function fkdNotice($message){
    	Log::write($message, 'Notice');
    	Log::write($message, 'Notice','fkd-'.date('Y-m-d'));
    }
    public static function fkdWarning($message){
    	Log::write($message, 'Warning');
    	Log::write($message, 'Warning','fkd-'.date('Y-m-d'));
    }
    
    //同步收款单 log 定义三种级别
    public static function skdError($message){
    	Log::write($message);
    	Log::write($message, 'Error','skd-'.date('Y-m-d'));
    }
    public static function skdNotice($message){
    	Log::write($message, 'Notice');
    	Log::write($message, 'Notice','skd-'.date('Y-m-d'));
    }
    public static function skdWarning($message){
    	Log::write($message, 'Warning');
    	Log::write($message, 'Warning','skd-'.date('Y-m-d'));
    }
    
    //收款后发送短信 log 定义三种级别
    public static function skdxError($message){
    	Log::write($message);
    	Log::write($message, 'Error','skdx-'.date('Y-m-d'));
    }
    public static function skdxNotice($message){
    	Log::write($message, 'Notice');
    	Log::write($message, 'Notice','skdx-'.date('Y-m-d'));
    }
    public static function skdxWarning($message){
    	Log::write($message, 'Warning');
    	Log::write($message, 'Warning','skdx-'.date('Y-m-d'));
    }
    
    //付款后发送短信 log 定义三种级别
    public static function fkdxError($message){
    	Log::write($message);
    	Log::write($message, 'Error','fkdx-'.date('Y-m-d'));
    }
    public static function fkdxNotice($message){
    	Log::write($message, 'Notice');
    	Log::write($message, 'Notice','fkdx-'.date('Y-m-d'));
    }
    public static function fkdxWarning($message){
    	Log::write($message, 'Warning');
    	Log::write($message, 'Warning','fkdx-'.date('Y-m-d'));
    }
    
    //审核 log 定义三种级别
    public static function auditError($message){
    	Log::write($message);
    	Log::write($message, 'Error','audit-'.date('Y-m-d'));
    }
    public static function auditNotice($message){
    	Log::write($message, 'Notice');
    	Log::write($message, 'Notice','audit-'.date('Y-m-d'));
    }
    public static function auditWarning($message){
    	Log::write($message, 'Warning');
    	Log::write($message, 'Warning','audit-'.date('Y-m-d'));
    }
    
    /**
     * 写入日志
     *
     * 将日志内容写入日志文件,并且当日志文件大小到达2M时,则写入新的日志文件
     * @access public
     * @param string $message   所要写入的日志内容
     * @param string $level     日志类型. 参数：Warning, Error, Notice
     * @param string $logFileName  日志文件名
     * @return boolean
     */
    public static function write($message, $level = 'Error', $logFileName = null) {

    	$log_level = 'Error';
    	//获取日志级别
    	if($level != 'Error' && file_exists(LOG_DIR . 'log_level')){
    		$log_level_tmp = trim(file_get_contents(LOG_DIR . 'log_level'));
    		if(in_array($log_level_tmp, array('Error', 'Notice', 'Warning'))){
    			$log_level = $log_level_tmp;
    		}
    	}
    	if($log_level == 'Error') {
    		if($level == 'Notice' || $level == 'Warning') return true;
    	}else if($log_level == 'Warning') {
    		if($level == 'Notice') return true;
    	}
    	
    	
        //参数分析
        if (!$message) {
            return false;
        }

        //当日志写入功能关闭时
        if(DOIT_LOG == false){
            return true;
        }

        $logFileName = self::getLogFile($logFileName);

        //判断日志目录
        $logDir = dirname($logFileName);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        } elseif (!is_writable($logDir)) {
            chmod($logDir, 0777);
        }

        $st = debug_backtrace();
        $file = '';
        $function = '';
        $line = '';
        
        foreach ($st as & $stack) {
        	if($file) {
        		$function = $stack['function'];
        		break;
        	}
        	if($stack['function'] == 'error' || $stack['function'] == 'warning' || $stack['function'] == 'notice') {
        		$file = $stack['file'];
        		$line = $stack['line'];
        	}
        }
        
        $function = $function ? $function : 'main';
        
        $pos = strrpos($file, '/');
        if(!$pos) {
        	//windows
        	$pos = strrpos($file, '\\');
        }
        $file = substr($file, $pos + 1);
        
        error_log(date('[Y-m-d H:i:s]') . " {$level}:{$file}:{$function}:{$line}: {$message} IP: {$_SERVER['REMOTE_ADDR']}\r\n", 3, $logFileName);
    }

    /**
     * 显示日志内容
     *
     * 显示日志文件内容,以列表的形式显示.便于程序调用查看日志
     * @access public
     * @param string $logFileName 所要显示的日志文件内容,默认为null, 即当天的日志文件名.注:不带后缀名.log
     * @return void
     */
    public static function show($logFileName = null) {

        //参数分析
        $logFileName    = self::getLogFile($logFileName);

        $logContent     = is_file($logFileName) ? file_get_contents($logFileName) : '';

        $listStrArray   = explode("\r\n", $logContent);
        unset($logContent);
        $totalLines    = sizeof($listStrArray);

        //输出日志内容
        echo '<table width="85%" border="0" cellpadding="0" cellspacing="1" style="background:#0478CB; font-size:12px; line-height:25px;">';

        foreach ($listStrArray as $key=>$linesStr) {

            if ($key == $totalLines - 1) {
                continue;
            }

            $bgColor = ($key % 2 == 0) ? '#FFFFFF' : '#C6E7FF';

            echo '<tr><td height="25" align="left" bgcolor="' . $bgColor .'">&nbsp;' . $linesStr . '</td></tr>';
        }

        echo '</table>';
    }

    /**
     * 获取当前日志文件名
     *
     * @access protected
     * @param $logFileName 日志文件名
     * @return string
     */
    protected static function getLogFile($logFileName = null) {
        return LOG_DIR .date('Y-m') . '/' . (is_null($logFileName) ? date('Y-m-d') : $logFileName) . '.log';
    }
}