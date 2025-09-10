<?php

return [
    /*
     * The API key of a MailChimp account. You can find yours at
     * https://us10.admin.mailchimp.com/account/api-key-popup/.
     */
    'apiKey' => env('MAILCHIMP_APIKEY'),

    /*
     * The listName to use when no listName has been specified in a method.
     */
    'defaultListName' => 'subscribers',

    /*
     * Here you can define properties of the lists.
     */
    'lists' => [
        'subscribers' => [
            'id' => env('MAILCHIMP_LIST_ID'),
        ],
    ],

    // 'ssl' => true,
    'ssl' => false,
];
