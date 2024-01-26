<?php
    if(!defined('_CODE')){
        die('Access denied.....');
    }

    $data = [
        'pageTitle'=> 'Trang chủ',
    ];

    //Nhúng file header thông qua hàm trong file function.php
    layouts('header', $data);

    if(!isLogin()){
        redirect('?module=auth&action=login');
    }
?>

<h1>Dashboard</h1>

<?php 
    layouts('footer');
?>