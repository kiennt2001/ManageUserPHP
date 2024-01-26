<!-- Các hằng số của project -->
<?php
    const _MODULE = 'home';
    const _ACTION = 'dashboard';
    const _CODE = true;
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    //Thiết lập host
    define('_WEB_HOST', 'http://'.$_SERVER['HTTP_HOST'].'/Demo/manage_users/');
    define('_WEB_HOST_TEMPLATE',_WEB_HOST.'template');

    //Thiết lập path
    define('_WEB_PATH', __DIR__); //Đường dẫn từ xampp vào tới project
    define('_WEB_PATH_TEMPLATE',_WEB_PATH.'\template');

    //Thông tin kết nối
    const _HOST = '127.0.0.1';
    const _DB = 'demowithphp1';
    const _USER = 'root';
    const _PASS = '';
?>