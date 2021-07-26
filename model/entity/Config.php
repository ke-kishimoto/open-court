<?php
namespace entity;

class Config extends BaseEntity
{
    public $lineNotifyFlg;
    public $lineToken;
    public $systemTitle;
    public $bgColor;
    public $logoImgPath;
    public $waitingFlgAutoUpdate;
    public $sendgridApiKey;
    public $clientId;
    public $clientSecret;
    public $channelAccessToken;
    public $channelSecret;


    public function __construct()
    {
        
    }
    
}