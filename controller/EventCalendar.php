<?php
namespace controller;

use dao\GameInfoDao;

class EventCalendar
{
    public $year;
    public $month;
    public $lastMonth;
    public $nextMonth;
    public $lastYear;
    public $nextYear;
    public $calendar;

    public $gameInfoList;

    public $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
      ];

    public function __construct($year, $month)
    {
        $gameInfoDao = new GameInfoDao();
        $this->gameInfoList = $gameInfoDao->getGameInfoList($year, $month);

        $this->year = $year;
        $this->month = $month;
        $this->lastmonth = $month == 1 ? 12 : $month - 1;
        $this->nextmonth = $month == 12 ? 1 : $month + 1;
        $this->lastYear = $month == 1 ? $year - 1 : $year;
        $this->nextYear = $month == 12 ? $year + 1 : $year;

         // 月末日を取得
         $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
        
         // カレンダー初期化
         $this->calendar = array();
         $j = 0;
         // 今日の日付
         $today = (int)date('d');

         // 月末日までループ
        for ($i = 1; $i < $last_day + 1; $i++) {
        
            // 曜日を取得
            $firstDayWeek = date('w', mktime(0, 0, 0, $month, $i, $year));
        
            // 1日の場合
            if ($i == 1) {
        
                // 1日目の曜日までをループ
                for ($s = 0; $s < $firstDayWeek; $s++) {
        
                    // 前半に空文字をセット
                    $this->calendar[$j]['day'] = '';
                    $this->calendar[$j]['link'] = false;
                    $this->calendar[$j]['today'] = '';
                    // $calendar[$j]['weekName'] = '';
                    // 曜日判定
                    if($s % 7 === 0) {
                        $this->calendar[$j]['weekName'] = 'sunday';
                    }elseif($s % 7 === 6) {
                        $this->calendar[$j]['weekName'] = 'saturday';
                    } else {  
                        $this->calendar[$j]['weekName'] = '';
                    }
                    $j++;
        
                }
        
            }
        
            $info = $gameInfoDao->getGameInfoListByDate($year . '-' . $month . '-' . $i);
            // 配列に日付をセット
            $this->calendar[$j]['day'] = $i;

            // 今日かどうか
            if($i === $today) {
                $this->calendar[$j]['today'] = 'today';
            } else {
                $this->calendar[$j]['today'] = '';
            }

            // 曜日判定
            if($j % 7 === 0) {
                $this->calendar[$j]['weekName'] = 'sunday';
            }elseif($j % 7 === 6) {
                $this->calendar[$j]['weekName'] = 'saturday';
            } else {  
                $this->calendar[$j]['weekName'] = '';
            }

            // イベント有無の判定
            if (!empty($info)) {
                $this->calendar[$j]['link'] = true;
                $this->calendar[$j]['info'] = $info;
            } else {
                $this->calendar[$j]['link'] = false;
            }
            $j++;
        
            // 月末日の場合
            if ($i == $last_day) {
        
                // 月末日から残りをループ
                for ($e = 0; $e < 6 - $firstDayWeek; $e++) {
        
                    // 後半に空文字をセット
                    $this->calendar[$j]['day'] = '';
                    $this->calendar[$j]['link'] = false;
                    $this->calendar[$j]['today'] = '';
                    // $calendar[$j]['weekName'] = '';
                    // 曜日判定
                    if($j % 7 === 0) {
                        $this->calendar[$j]['weekName'] = 'sunday';
                    }elseif($j % 7 === 6) {
                        $this->calendar[$j]['weekName'] = 'saturday';
                    } else {  
                        $this->calendar[$j]['weekName'] = '';
                    }
                    $j++;
                }
            }
        }
    }

}
