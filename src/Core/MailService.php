<?php


namespace MyApp\Core;


interface MailService
{
    public function getDomain();
    public function sendHtml($to = '', $from = '', $subject = '', $html = '');
    public function sendText($to = '', $from = '', $subject = '', $text = '');
}