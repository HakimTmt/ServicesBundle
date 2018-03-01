<?php

namespace Tmt\ServicesBundle\Services\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Mailer service
 *
 * @author TMT
 */
class Mailer {

    protected $mailer;
    private $templating;
    public $adminEmails;

    public function __construct(Container $container) {
        $this->mailer = $container->get('mailer');
        $this->templating = $container->get('templating');
        $this->crmEmail = $container->getParameter('crm_email');
        $this->em = $container->get('doctrine')->getManager();
    }

    public function getAdminsEmails() {
        $this->adminEmails = $this->em->getRepository('TmtUserBundle:User')->getAdminsEmails();
    }

    public function send_html_mail($subject, $from, $to, $template, $data = array(), $attachments = array()) {


        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody(
                $this->templating->render($template, $data), 'text/html'
        );

        foreach ($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        $this->mailer->send($message);
    }

    public function send_mail($subject, $from, $to, $body, $attachments = array()) {
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody($body);
        foreach ($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }
        $this->mailer->send($message);
    }

}
