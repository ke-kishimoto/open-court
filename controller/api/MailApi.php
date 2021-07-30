<?php
namespace api;
require_once('vendor/autoload.php');
use dao\ConfigDao;
use Exception;

class MailApi
{
    // パスワードリセットメール送信
    public function passreset_mail(string $email, string $pass)
    {
        $subject = "【FromStreet】パスワードリセットのお知らせ";

        $msg = "※このメールはシステムより自動送信されています。
このメールに心当たりのない場合はお手数ですが削除をお願いします。

下記のパスワードで再度ログインをしてパスワードを再設定してください。
{$pass}
";

        return $this->send_mail($email, '', $subject, $msg);
    }

    // 問い合わせ確認メール送信
    public function inquiry_mail($inquiry) 
    {
        $subject = "【FromStreet】お問い合わせ確認";

        $msg = "※このメールはシステムより自動送信されています。
このメールに心当たりのない場合はお手数ですが削除をお願いします。


以下の内容でお問い合わせを受け付けました。
担当者からご連絡いたしますので今しばらくお待ちください。


------------------------------
{$inquiry['content']}\n
------------------------------
";

      return $this->send_mail($inquiry['email'], $inquiry['name'], $subject, $msg);

    }

    private function send_mail($mailTo, $userName, $subject, $message)
    {
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("fromstreetb@gmail.com", "FromStreet");
        $email->setSubject($subject);
        $email->addTo($mailTo, $userName);
        $email->addContent("text/plain", $message);
        // $email->addContent(
        //     "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        // );
        $configDao = new ConfigDao();
        $config = $configDao->selectById(1);

        $sendgrid = new \SendGrid($config['sendgrid_api_key']);
        // $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
            return $response->statusCode();
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
            return $e->getMessage();
        }
    }
}

