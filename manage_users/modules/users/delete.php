<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    $filterAll = filter();
    if(!empty($filterAll['id'])){
        $userId = $filterAll['id'];
        $userDetail = getRows("SELECT * FROM users WHERE id=$userId");
        if($userDetail > 0){
            $deleteToken = delete('tokenlogin', "user_Id=$userId");
            if($deleteToken){
                $deleteUser = delete("users", "id=$userId");
                if($deleteUser){
                    setFlashData('smg','Xóa người dùng thành công!');
                    setFlashData('smg_type','success');
                }else{
                    setFlashData('smg','Lỗi hệ thống. Vui lòng thử lại sau!');
                    setFlashData('smg_type','danger');
                }
            }
        }
    }else{
        setFlashData('smg','id không tồn tại!');
        setFlashData('smg_type','danger');
    }

    redirect('?module=users&action=list');
?>