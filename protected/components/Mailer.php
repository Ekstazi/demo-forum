<?php

namespace app\components;


class Mailer extends Component
{
    public $log = true;

    public function sendEmail($to, $subject, $content)
    {
        mb_send_mail($to, $subject, $content);
        $this->log($to, $subject, $content);

    }

    protected function log($to, $subject, $content)
    {
        $path = App::instance()->getBasePath() . '/runtime/mails';
        if (!is_dir($path)) {
            mkdir($path, 0666, true);
        }
        $file = $path . '/' . time();
        $time = date('d-m-Y h:i:s');
        $message = <<<MSG
Email sended at time {$time}
to: {$to}
subject: {$subject}
content: {$content}
MSG;

        file_put_contents($file, $message);
    }
}