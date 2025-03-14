<?php

namespace App\Services;

use App\Application\PageLoader;
use App\Models\Invoice;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use Stripe\Service\InvoiceService;

class EmailWriterService
{
    private EmailService $emailService;
    private PageLoader $pageLoader;

    public function __construct()
    {
        $this->emailService = new EmailService();
        $this->pageLoader = new PageLoader();
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

    public function sendInvoice(User $user, Invoice $invoice): void
    {
        $title = "Invoice #{$invoice->id}";
        $body = $this->pageLoader->renderEmail('invoice', [
            'user' => $user,
            'invoice' => $invoice,
            'total' => 1 #TODO: integrate with calculation
        ]);

        $this->emailService
            ->addRecipient($user->email)
            ->setContent(
                $title,
                $body
            )
            ->send();
    }
}
