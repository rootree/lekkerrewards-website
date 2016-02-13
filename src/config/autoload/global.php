<?php

return array(
    'company' => array(
        'name' => 'LekkerRewards',
        'card' => 'LekkerCard',
        'emailRobot' => 'LekkerRewards',
        'baseHost' => 'http://lekkerrewards.nl/',
        'countryCode' => 'NL',
        'phone' => '+31 6 83899381',
        'email' => 'support@lekkerrewards.nl',
        'emailForFeedback' => 'ivan.chura@gmail.com',
        'nameForFeedback' => 'ivan.j.chura',
        'facebook' => 'https://www.facebook.com/lekkerrewards/',
        'twitter' => 'https://twitter.com/lekkerrewards',
        'instgram' => 'https://instagram.com/lekkerrewards/',
        'timezone' => 'Europe/Amsterdam',
    ),
    'isSendingEmailsIsEnabled' => 1,
    'locale' => array(
        'default' => 'en_US',
        'available' => array(
            'ru' => 'ru_RU',
            'nl' => 'nl_NL',
            'en' => 'en_US',
        ),
    ),
    'logic' => array(
        'canTakeNewCardAfter' => '23', // Default 23 Hours
        'pointsPerVisit' => '1', // Default 1
        'pointsForFirstVisit' => '1',// Default 1
        'coolDown' => '6',// Default 6 Hours for new check-in
    ),
);
