<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Messages\MailMessage;

trait MailChannel
{
    protected $mailSubject;

    protected $mailTemplate;

    protected $mailTemplateArgs;

    /**
     * Set the arguments for the toMail() method
     *
     * @param string $subject  Email subject
     * @param string $template Markdown template to use
     * @param array  $args     Arguments to pass to the template
     */
    public function setMailable(string $subject, string $template, array $args)
    {
        $this->mailSubject = $subject;
        $this->mailTemplate = $template;
        $this->mailTemplateArgs = $args;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed       $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $fromAddress = config('mail.from.address', 'noreply@airjamaicavirtual.com');
        $fromName = config('mail.from.name', 'Air Jamaica Virtual Airlines');
        
        return (new MailMessage())
            ->from($fromAddress, $fromName)
            ->replyTo($fromAddress, $fromName)
            ->subject($this->mailSubject)
            ->markdown($this->mailTemplate, $this->mailTemplateArgs);
    }
}
