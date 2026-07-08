<?php

return [

    'title' => 'Giriş Yap',

    'heading' => 'Hesabınıza giriş yapın',

    'actions' => [

        'register' => [
            'before' => 'veya',
            'label' => 'hesap oluşturun',
        ],

        'request_password_reset' => [
            'label' => 'Şifrenizi mi unuttunuz?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'E-posta adresi',
        ],

        'password' => [
            'label' => 'Şifre',
        ],

        'remember' => [
            'label' => 'Beni hatırla',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Giriş yap',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'Girilen bilgiler kayıtlarımızla eşleşmiyor.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Çok fazla giriş denemesi',
            'body' => 'Lütfen :seconds saniye sonra tekrar deneyin.',
        ],

    ],

];
