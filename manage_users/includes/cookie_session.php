<!-- Các hàm liên quan đến cookie hoặc session -->
<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    //Gán session
    function setSession( $key, $value ){
        return $_SESSION[$key] = $value;
    }

    //Đọc session
    function getSession($key = ''){
        if(empty($key)){
            return $_SESSION;
        }else{
            if(isset($_SESSION[$key])){
                return $_SESSION[$key];
            }
        }
    }

    //Xóa session
    function removeSession($key = ''){
        if(empty($key)){
            session_destroy();
            return true;
        }else{
            if(isset($_SESSION[$key])){
                unset($_SESSION[$key]);
                return true;
            }
        }
    }

    //gán flash data: flash data là dữ liệu quá khứ. 
    function setFlashData( $key, $value ){
        $key = 'flash_'.$key;
        return setSession($key, $value);
    }

    //Đọc flash data
    function getFlashData($key){
        $key = 'flash_'.$key;
        $data = getSession($key);
        removeSession($key);
        return $data;
    }

?>