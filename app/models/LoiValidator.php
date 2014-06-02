<?php
/**
* This class is needed to register new custome validator in my project
* validatecaptcha() => the rule will be named as captcha
* validate is the hard prefix
*/
class LoiValidator extends Illuminate\Validation\Validator {

    public function validatecaptcha($attribute, $value, $parameters)
    {
        return $value == Session::get('captcha');
    }

}

//Next, you need to register your custom Validator extension: with validator resolver
//this can be place in the global.php

// Validator::resolver(function($translator, $data, $rules, $messages){
// 	return new LoiValidator($translator, $data, $rules, $messages);
// });

//now create new validation message for the Validator in vlanguage/validation.php file
// "captcha"          => "The captcha is not correct.",

//finally can customize the message as you need in the controller
// 'captcha.captcha' 			=> 'Mã bảo vệ không khớp.',