<?php

namespace Vt\Forms\Controller;

use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;

class FormResult extends Controller
{
    public function getDefaultPreFilters()
    {
        return [
            new HttpMethod(
                [HttpMethod::METHOD_POST]
            ),
            new Csrf()
        ];
    }

    public function addAction()
    {
        return [
            'message' => 'works'
        ];
    }
}
