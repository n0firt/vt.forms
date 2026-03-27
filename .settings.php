<?php

use Vt\Forms\Base\Dto\FieldDto;
use Vt\Forms\Base\Fields\EmailField;
use Vt\Forms\Base\Fields\PhoneField;
use Vt\Forms\Base\Fields\TextField;
use Vt\Forms\Base\Form;
use Vt\Forms\CustomFormatter;

return [
    'components' => [
        'value' => [
            'vt:form'
        ],
        'readOnly' => true
    ],
    'controllers' => [
        'value' => [
            'defaultNamespace' => 'Vt\\Forms\\Controller'
        ],
        'readOnly' => true
    ],
    'services' => [
        'value' => [
            'vt.forms.repository' => [
                'constructor' => static function () {
                    $repository = new \Vt\Forms\Service\FormRepository();

                    $repository->add(
                        new Form(
                            'request',
                            new TextField(
                                new FieldDto(
                                    'NAME',
                                    'Имя',
                                    true
                                )
                            ),
                            new PhoneField(
                                new FieldDto(
                                    'PHONE',
                                    'Телефон',
                                    true
                                )
                            ),
                            new EmailField(
                                new FieldDto(
                                    'EMAIL',
                                    'Email'
                                )
                            ),
                            new TextField(
                                new FieldDto(
                                    'MESSAGE',
                                    'Комментарий',
                                    isTextArea: true
                                )
                            )
                        )
                    );

                    return $repository;
                }
            ],
            'vt.forms.logger' => [
                'constructor' => static function () {
                    $logger = new \Bitrix\Main\Diag\FileLogger($_SERVER['DOCUMENT_ROOT'] . '/local/modules/vt.forms/log.txt');
                    $logger->setFormatter(new CustomFormatter());

                    return $logger;
                },
            ]
        ]
    ]
];
