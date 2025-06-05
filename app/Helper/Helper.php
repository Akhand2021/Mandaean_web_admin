<?php
function getTimeDifference($from,$to)
{
    $date1 = strtotime($from);
    $date2 = strtotime($to);
    
    $diff = abs($date2 - $date1);
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    
    return (($years*365*24*60) + ($months*30*24*60) + ($days*24*60) + ($hours*60) + $minutes);
}

function ___mail_sender($email, $name, $template, $data, $subject)
{
    // config(['mail.host' => _getConfigurationByKey('smtp_server_host'), 'mail.port' => _getConfigurationByKey('smtp_port_number'), 'mail.username' => _getConfigurationByKey('smtp_uName'), 'mail.password' => _getConfigurationByKey('smtp_uPass')]);
    config(['mail.host' => 'smtp.gmail.com', 'mail.port' => '587', 'mail.username' => 'mandaean2023@gmail.com', 'mail.password' => 'xtzpqsjphljnqjlo']);
    
    Illuminate\Support\Facades\Mail::send($template, $data, function ($message) use ($email, $name, $subject) {
        $message->to($email, $name)->subject($subject);
        $message->from('mandaean2023@gmail.com', 'Mandaean');
        return 'success';
    });
}

function rand_string($length){
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);
}

function _getSKU($code_len = '12')
{
    $chars = "123456789";
    $code = "";
    for ($i = 0; $i < $code_len; $i++) {
        $code .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $code;
}

?>