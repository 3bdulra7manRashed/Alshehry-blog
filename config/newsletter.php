<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Newsletter Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls which mailer is used for sending newsletter
    | campaigns. By default, it uses the dedicated 'newsletter' mailer
    | defined in config/mail.php, but you can override it via environment
    | variable to use 'log', 'smtp', or any other configured mailer.
    |
    | Supported: Any mailer defined in config/mail.php mailers array
    |
    */

    'mailer' => env('NEWSLETTER_MAILER', 'newsletter'),

];
