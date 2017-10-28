<?php


namespace MyApp\Core\Adapters;


use Mailgun\Mailgun;
use MyApp\Core\MailService;

class MailGunAdapter implements MailService
{
    /**
     * @var Mailgun
     */
    private $mailgun;
    /**
     * @var string
     */
    private $domain;

    public function __construct($domain, Mailgun $mailgun)
    {
        $this->mailgun = $mailgun;
        $this->domain  = $domain;
    }

    public function sendHtml($to = '', $from = '', $subject = '', $html = '')
    {
        $this->mailgun->messages()->send($this->domain, [
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'html'    => $html,
        ]);
    }

    public function sendText($to = '', $from = '', $subject = '', $text = '')
    {
        $this->mailgun->messages()->send($this->domain, [
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'text'    => $text,
        ]);
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }


}