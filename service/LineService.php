<?php

namespace service;

use dao\DetailDao;
use dao\UsersDao;

class LineService
{
    public function getTargetUser($occupation, $sex, $eventId)
    {
        if(empty($eventId)) {
            $userDao = new UsersDao();
            $targetUser = $userDao->getLineUser($occupation, $sex);
        } else {
            $detailDao = new DetailDao();
            $targetUser = $detailDao->getLineUser($occupation, $sex, $eventId);
        }
        return $targetUser;
    }
}