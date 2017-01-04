<?php

namespace RabbitCMS\Templates\Contracts;

interface MailRecipient
{
    /**
     * Get recipient email.
     * @return string
     */
    public function getRecipientEmail();

    /**
     * Get recipient name.
     * @return string
     */
    public function getRecipientName();
}