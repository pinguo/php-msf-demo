<?php
namespace App\Lib;

use Yii;
use PG\MSF\Server\Controllers\BaseController;
use App\Lib\ParamValidatorHelper;
use App\Lib\TimeZoneHelper;
use App\Lib\SecurityHelper;
use App\Lib\Errno;

class Controller extends BaseController
{
    public $appName = null;
    public $appVersion = null;
    public $appVersionCode = null;
    public $systemVersion = null;
    public $platform = null;
    public $device = null;
    public $deviceId = null;
    public $channel = null;
    public $locale = null;
    public $timeZone = null; // eg: 'Asia/Shanghai'
    public $cid = null;
    public $longitude = null;
    public $latitude = null;
    public $mnc = null;
    public $mcc = null;
    public $initStamp = null;
    public $localTime = null;
    public $UTCOffset = null;
    public $user = null;

    public function verifySign()
    {
        $this->PGLog->profileStart('verifySign');
        $appsecret = getInstance()->config->get('params.app.' . $this->platform . '.appSecret');
        $godSig    = '56610f9fce1cdd07098cd80d';
        if (empty($appsecret)) {
            throw new \Exception('VerifySign::appsecret must be set.');
        }
        // 开发环境不对sig进行验证.
        if (defined('APPLICATION_ENV') && (APPLICATION_ENV == 'newdev' || APPLICATION_ENV == 'develop')) {
            return true;
        }

        $sig = $this->input->postGet('sig');
        // qa、test环境万能签名.
        if (defined('APPLICATION_ENV') && (APPLICATION_ENV == 'testing' || APPLICATION_ENV == 'testing_dev')) {
            if ($godSig && $sig === $godSig) {
                return true;
            }
        }

        $allParams = $this->input->getAllPostGet();
        unset($allParams['sig']);
        $allParams = array_map('trim', $allParams);
        $sign = SecurityHelper::sign($allParams, $appsecret);
        if (strcmp($sig, $sign) !== 0) {
            foreach ($allParams as $k => $v) {
                $allParams[$k] = urlencode($v);
            }
            $sign = SecurityHelper::sign($allParams, $appsecret);
            if (strcmp($sign, $sig) !== 0) {
                throw new \Exception('invalid sign', Errno::SIG_ERROR);
            }
        }

        $this->PGLog->profileEnd('verifySign');
        return true;
    }

    public function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name);
        $this->PGLog->profileStart('init');
        $request = $this->input->getAllPostGet();
        $this->PGLog->pushLog('params', $request);
        $appName  = ParamValidatorHelper::validateString($request, 'appName', 1, 50, '');
        if (!$appName) {
            $appName = ParamValidatorHelper::validateString($request, 'appname', 1, 50);
        }

        $appVersion = ParamValidatorHelper::validateString($request, 'appVersion', 1, 50, '');
        if (!$appVersion) {
            $appVersion = ParamValidatorHelper::validateString($request, 'appversion', 1, 50);
        }
        $appVersionCode = ParamValidatorHelper::validateString($request, 'appVersionCode', 1, 50, '');
        // android低版本未传systemVersion.
        $systemVersion = ParamValidatorHelper::validateString($request, 'systemVersion', 1, 50, '');

        $platform = ParamValidatorHelper::validateEnumString($request, 'platform', array('ios', 'android', 'iphone', 'wp', 'other'));
        if ($platform == 'iphone') {
            $platform = 'ios';
        }
        $device = ParamValidatorHelper::validateString($request, 'device', 0, 100, '');
        $locale = ParamValidatorHelper::validateString($request, 'locale', 0, 100, '');
        $timeZone = ParamValidatorHelper::validateString($request, 'timeZone', 0, 100, '');
        try {
            $timeZone = TimeZoneHelper::fStandardTimeZone($timeZone);
        } catch (\Exception $e) {
           $this->PGLog->warning('error :' . $e->getMessage() . ', code: ' . $e->getCode() . ', file: ' . $e->getFile() . ' of line ' . $e->getLine());
        }
        $channel  = ParamValidatorHelper::validateString($request, 'channel', 0, 100, '');
        $channel = urldecode($channel);
        // cid可能为空.
        $cid = ParamValidatorHelper::validateString($request, 'cid', 1, 200, '');
        // 优先deviceId取eid，如果没有则取deviceId
        $deviceId = ParamValidatorHelper::validateString($request, 'eid', 1, 200, '');
        if (!$deviceId) {
            // 没有deviceId则取$cid.
            $deviceId = ParamValidatorHelper::validateString($request, 'deviceId', 1, 200, $cid);
        }
        $mnc = ParamValidatorHelper::validateString($request, 'mnc', 1, 30, ''); // 移动网络代码
        $mcc = ParamValidatorHelper::validateString($request, 'mcc', 1, 30, ''); // 移动国家代码
        $initStamp = ParamValidatorHelper::validateString($request, 'initStamp', 1, 20, ''); // 用户新装时间戳

        $latitude = $longitude = null;
        if (isset($request['latitude']) && isset($request['longitude'])) {
            if (stripos($request['latitude'], 'e') === false) {
                $latitude = ParamValidatorHelper::validateNumber($request, 'latitude', - 90, 90, null);
            } else { // 科学计数法传过来的.
                $latitude = floatval(sprintf("%0.4f", $request['latitude']));
                $latitude = ParamValidatorHelper::validateNumber($latitude, 'latitude', - 90, 90, null);
            }
            if (stripos($request['longitude'], 'e') === false) {
                $longitude = ParamValidatorHelper::validateNumber($request, 'longitude', - 180, 180, null);
            } else { // 科学计数法传过来的.
                $longitude = floatval(sprintf("%0.4f", $request['longitude']));
                $longitude = ParamValidatorHelper::validateNumber($longitude, 'longitude', - 180, 180, null);
            }
        }

        // 用户本地时间戳
        $localTime = ParamValidatorHelper::validateNumber($request, 'localTime', null, null, 0);
        // 用户本地时间和UTC时间的偏移量,单位秒
        $UTCOffset = ParamValidatorHelper::validateNumber($request, 'UTCOffset', null, null, 0);

        if ($latitude == null || $longitude == null) {
            $latitude = null;
            $longitude = null;
        }

        $this->appName = $appName;
        $this->appVersion = $appVersion;
        $this->appVersionCode = $appVersionCode;
        $this->systemVersion = $systemVersion;
        $this->platform = $platform;
        $this->device = $device;
        $this->deviceId = $deviceId;
        $this->channel = $channel;
        $this->locale = $locale;
        $this->timeZone = $timeZone;
        $this->cid = $cid;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->mnc = $mnc;
        $this->mcc = $mcc;
        $this->initStamp = $initStamp;
        $this->localTime = $localTime;
        $this->UTCOffset = $UTCOffset;
        $this->PGLog->profileEnd('init');

        $this->verifySign();

        return true;
    }
}
