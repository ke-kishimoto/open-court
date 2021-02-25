<?php
require_once('vendor/autoload.php');
require_once(__DIR__.'/../../controller/api/MailApi.php');
require_once('./model/dao/BaseDao.php');
require_once('./model/dao/ConfigDao.php');
require_once('./model/dao/InquiryDao.php');
require_once('./model/dao/DaoFactory.php');
require_once('./model/dao/TestPDO.php');
require_once('./model/dao/MyPDO.php');
require_once('./model/entity/BaseEntity.php');
require_once('./model/entity/Config.php');
require_once('./model/entity/Inquiry.php');

use PHPUnit\Framework\TestCase;
use api\MailApi;
use entity\Inquiry;

class MailApiTest extends TestCase
{
    public function testInquiryMail()
    {
        $mail = new MailApi();
        $inquiry = new Inquiry();
        $inquiry->name = "岸本";
        $inquiry->email = "nebinosuk@gmail.com";
        $inquiry->content = "問い合わせのテストです。
        ちゃんと届くかな？？
        バスケ下手だけどいいですか？";
        $result = $mail->inquiry_mail($inquiry);
        $this->assertSame(202, $result);
    }
}
