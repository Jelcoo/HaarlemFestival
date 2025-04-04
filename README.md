# The Festival

The Festival website is a website that allows users to buy tickets for events in Haarlem.

## Configuration

1. Copy the example configuration file:
   ```bash
   cp config.php.example config.php
   ```

2. Edit the `config.php` file with your specific settings:

   - Database Configuration:
     - `DB_HOST`: Set to 'mysql' (default)
     - `DB_PORT`: Set to 3306 (default)
     - `DB_USER`: Set to 'developer' (default)
     - `DB_PASSWORD`: Set to 'secret123' (default)
     - `DB_NAME`: Set to 'festivaldb' (default)

   - Stripe Configuration:
     - `STRIPE_PUBLIC_KEY`: Your Stripe publishable key
     - `STRIPE_SECRET_KEY`: Your Stripe secret key
     - `STRIPE_WEBHOOK_SECRET`: Your Stripe webhook signing secret
     - `ENABLE_STRIPE`: Set to true to enable Stripe payments

   - Mail Configuration:
     - `MAIL_HOST`: Your SMTP server
     - `MAIL_USER`: Your SMTP username
     - `MAIL_PASSWORD`: Your SMTP password
     - `MAIL_PORT`: SMTP port (default: 587)
     - `MAIL_DEBUG`: Set to false in production

   - Other Settings:
     - `APP_URL`: Your application URL
     - `APP_ENV`: Set to 'production' or 'development'
     - `APP_NAME`: Your application name
     - `TURNSTILE_KEY`: Your Cloudflare Turnstile site key
     - `TURNSTILE_SECRET`: Your Cloudflare Turnstile secret key

## Setting up Stripe Webhooks

1. Log in to your Stripe Dashboard
2. Go to Developers → Webhooks
3. Click "Add endpoint"
4. Set the endpoint URL to: `https://your-domain.com/webhook/stripe`
5. Select the following events to listen for:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `checkout.session.completed`
6. Copy the "Signing secret" and add it to your `config.php` as `STRIPE_WEBHOOK_SECRET`

For more information about Stripe webhooks, visit the [Stripe Webhook Documentation](https://stripe.com/docs/webhooks).

## Starting the Application

1. Start the Docker containers:
   ```bash
   docker compose up -d
   ```

2. The application will be available at:
   - Main application: http://localhost
   - PHPMyAdmin: http://localhost:8080

3. To stop the application:
   ```bash
   docker compose down
   ```

## Test Accounts

The following test accounts are available in the database:

1. Admin Account:
   - Email: johndoe@example.com
   - Password: 123456
   - Role: admin

2. Employee Account:
   - Email: doejohn@example.com
   - Password: 123456
   - Role: employee

3. Customer Account:
   - Email: customer@example.com
   - Password: 123456
   - Role: user

These accounts can be used to test different user roles and permissions in the application.
