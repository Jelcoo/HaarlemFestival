<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\InvoiceHelper;

class EmailWriterService
{
    private EmailService $emailService;
    private InvoiceHelper $invoiceHelper;

    public function __construct()
    {
        $this->emailService = new EmailService();
        $this->invoiceHelper = new InvoiceHelper();
    }

    public function sendWelcomeEmail(User $user): void
    {
        $this->emailService
            ->addRecipient($user->email)
            ->setContent(
                'Welcome to our website',
                "Hello {$user->firstname} {$user->lastname}, welcome to our website!"
            )
            ->send();
    }

    public function sendEmailUpdate(User $user): void
    {
        $this->emailService
            ->addRecipient($user->email)
            ->setContent(
                'Your email address has been updated',
                "Hello {$user->firstname} {$user->lastname}, the email address for your account has been updated."
            )
            ->send();
    }

    public function sendAccountUpdate(User $user): void
    {
        $this->emailService
            ->addRecipient($user->email)
            ->setContent(
                'Your account has been updated',
                "Hello {$user->firstname} {$user->lastname}, your account has been updated."
            )
            ->send();
    }

    public function sendPasswordUpdate(User $user): void
    {
        $this->emailService
            ->addRecipient($user->email)
            ->setContent(
                'Your password has been updated',
                "Hello {$user->firstname} {$user->lastname}, your password has been updated."
            )
            ->send();
    }

    public function sendInvoiceWithTickets(User $user, int $invoiceId): void
    {
        $invoicePath = $this->invoiceHelper->generateInvoicePdf($invoiceId);
        $ticketPaths = $this->invoiceHelper->generateAllTicketsForInvoice($invoiceId);

        $this->emailService
            ->addRecipient($user->email)
            ->setContent(
                'Your invoice and tickets',
                "Hello {$user->firstname} {$user->lastname},\n\nPlease find attached your invoice and tickets for your recent order."
            )
            ->addAttachment($invoicePath);

        foreach ($ticketPaths as $ticketPath) {
            $this->emailService->addAttachment($ticketPath);
        }

        $this->emailService->send();
    }
}
