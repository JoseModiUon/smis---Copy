<?php

namespace app\components;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use yii\base\Component;

class PhoneNumber extends Component{
    public function formatKe($no){
        $phoneUtil = PhoneNumberUtil::getInstance();

        $swissNumberProto = $phoneUtil->parse($no, "KE");
        $isValid = $phoneUtil->isValidNumber($swissNumberProto);
        if (!$isValid) {
            $_SESSION['RS_ERROR'] .= ucwords('Phone Number : '.$no.' is not valid.').'<br/>';
            throw new \Exception('Phone Number : '.$no.' is not valid.');
        }
        return $phoneUtil->format($swissNumberProto, PhoneNumberFormat::E164);
    }
}
