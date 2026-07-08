<?php

return [

    'direction' => 'ltr',

    'actions' => [

        'associate' => [
            'label' => 'İlişkilendir',
        ],

        'associate_another' => [
            'label' => 'İlişkilendir & başka ekle',
        ],

        'attach' => [
            'label' => 'Ekle',
        ],

        'attach_another' => [
            'label' => 'Ekle & başka ekle',
        ],

        'cancel' => [
            'label' => 'İptal',
        ],

        'create' => [
            'label' => 'Oluştur',
        ],

        'create_another' => [
            'label' => 'Oluştur & başka ekle',
        ],

        'delete' => [
            'label' => 'Sil',
        ],

        'detach' => [
            'label' => 'Ayır',
        ],

        'dissociate' => [
            'label' => 'İlişkiyi kaldır',
        ],

        'edit' => [
            'label' => 'Düzenle',
        ],

        'force_delete' => [
            'label' => 'Kalıcı olarak sil',
        ],

        'restore' => [
            'label' => 'Geri yükle',
        ],

        'save' => [
            'label' => 'Kaydet',
        ],

        'view' => [
            'label' => 'Görüntüle',
        ],

    ],

    'empty' => [
        'heading' => 'Kayıt bulunamadı',
        'description' => 'Başlamak için bir :model oluşturun.',
    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => 'Filtreleri uygula',
            ],

            'remove' => [
                'label' => 'Filtreyi kaldır',
            ],

            'remove_all' => [
                'label' => 'Tüm filtreleri kaldır',
            ],

            'reset' => [
                'label' => 'Sıfırla',
            ],

        ],

        'indicator' => 'Aktif filtreler',

        'multi_select' => [
            'placeholder' => 'Tümü',
        ],

        'select' => [
            'placeholder' => 'Tümü',
        ],

        'trashed' => [

            'label' => 'Silinmiş kayıtlar',

            'only_trashed' => 'Sadece silinmiş kayıtlar',

            'with_trashed' => 'Silinmiş kayıtlarla birlikte',

            'without_trashed' => 'Silinmiş kayıtlar hariç',

        ],

    ],

    'grouping' => [

        'fields' => [
            'group' => 'Gruplama ölçütü',
            'direction' => 'Gruplama yönü',
        ],

    ],

    'pagination' => [

        'label' => 'Sayfalama',

        'overview' => ':total sonuçtan :first ile :last arası gösteriliyor',

        'fields' => [

            'records_per_page' => [

                'label' => 'sayfa başına',

                'options' => [
                    'all' => 'Tümü',
                ],

            ],

        ],

        'actions' => [

            'go_to_page' => [
                'label' => ':page. sayfaya git',
            ],

            'next' => [
                'label' => 'Sonraki',
            ],

            'previous' => [
                'label' => 'Önceki',
            ],

        ],

    ],

    'reorder_indicator' => 'Kayıtları sürükleyip bırakarak yeniden sıralayın.',

    'selection_indicator' => [

        'selected_count' => '1 kayıt seçildi|:count kayıt seçildi',

        'actions' => [

            'select_all' => [
                'label' => ':count tümünü seç',
            ],

            'deselect_all' => [
                'label' => 'Tümünün seçimini kaldır',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Sıralama ölçütü',
            ],

            'direction' => [

                'label' => 'Sıralama yönü',

                'options' => [
                    'asc' => 'Artan',
                    'desc' => 'Azalan',
                ],

            ],

        ],

    ],

];
