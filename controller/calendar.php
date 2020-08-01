<?php
require_once('../model/dao/GameInfoDao.php');
use dao\GameInfoDao;

$gameInfoDao = new GameInfoDao();

// 現在の年月を取得 
// $year = date('Y');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y') ;
// $month = date('n');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$lastmonth = $month === 1 ? 12 : $month - 1;
$nextmonth = $month === 12 ? 1 : $month + 1;
// $lastmonth = date('n',strtotime('-1 month'));
// $nextmonth = date('n',strtotime('+1 month'));
$pre_year = $month === 1 ? $year - 1 : $year;
$next_year = $month === 12 ? $year + 1 : $year;

// 月末日を取得
$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
 
$calendar = array();
$j = 0;

// 月末日までループ
for ($i = 1; $i < $last_day + 1; $i++) {
 
    // 曜日を取得
    $week = date('w', mktime(0, 0, 0, $month, $i, $year));
 
    // 1日の場合
    if ($i == 1) {
 
        // 1日目の曜日までをループ
        for ($s = 1; $s <= $week; $s++) {
 
            // 前半に空文字をセット
            $calendar[$j]['day'] = '';
            $calendar[$j]['link'] = false;
            $j++;
 
        }
 
    }
 
    $info = $gameInfoDao->getGameInfoListByDate($year . '-' . $month . '-' . $i);
    // 配列に日付をセット
    $calendar[$j]['day'] = $i;
    if (!empty($info)) {
        $calendar[$j]['link'] = true;
    } else {
        $calendar[$j]['link'] = false;
    }
    $j++;
 
    // 月末日の場合
    if ($i == $last_day) {
 
        // 月末日から残りをループ
        for ($e = 1; $e <= 6 - $week; $e++) {
 
            // 後半に空文字をセット
            $calendar[$j]['day'] = '';
            $calendar[$j]['link'] = false;
            $j++;
        }
    }
}
 
?>