<!-- Đăng xuất -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    if(isLogin()){
        $token = getSession('tokenLogin');
        delete('tokenlogin', "token='$token'");
        removeSession("tokenlogin");
        redirect('?module=auth&action=login');
    }
?>