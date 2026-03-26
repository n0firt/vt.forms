<?php

use Vt\Forms\Base\Dto\FieldDto;
use Vt\Forms\Base\Fields\EmailField;
use Vt\Forms\Base\Fields\PhoneField;
use Vt\Forms\Base\Fields\TextField;
use Vt\Forms\Base\Form;

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
                                    false
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
                                    'Email',
                                    true
                                )
                            ),
                            new TextField(
                                new FieldDto(
                                    'MESSAGE',
                                    'Комментарий',
                                    true,
                                    isTextArea: true
                                )
                            )
                        )
                    );

                    return $repository;
                }
            ]
        ]
    ]
];
