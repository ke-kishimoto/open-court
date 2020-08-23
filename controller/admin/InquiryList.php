<?php 
require_once('../../model/dao/InquiryDao.php');
require_once('./Header.php');  
use dao\InquiryDao;

$inquiryDao = new InquiryDao();
$inquiryList = $inquiryDao->getInquiryList();

$title = 'お問い合わせ一覧';
include('../../view/admin/common/head.php');
include('../../view/admin/common/header.php');
include('../../view/admin/inquiryList.php');
include('../../view/admin/common/footer.php');
?>