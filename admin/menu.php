<?php

return [
    "parent_menu" => "global_menu_services",
    "section" => "vt_forms",
    "sort" => 0,
    "text" => "AJAX формы",
    "icon" => "form_menu_icon",
    "items_id" => "menu_vt_forms",
    "items" => [
        [
            "text" => "Результаты",
            "url" => "vt_forms_result_list.php?lang=" . LANGUAGE_ID,
        ],
    ],
];
