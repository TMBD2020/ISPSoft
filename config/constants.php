<?php

return [

    'developer' => [
        'name' => 'Tech Makers BD',
        'url'   =>'http://techmakersbd.com'
    ],

    'development' => [
        'session' => "2019-2023"
    ],

    'software' => [
        'name' => ''
    ],

    'system' => [
        'type' => 'IMS'
    ],

    'sms_bundle' => [
        'token'=>''
    ],

    'sms_template_category'=> [
        "Notice",
        "Promotion",
        "Greetings",
        "Due",
        "Other"
    ],

    'sms'=> [
        [
            "name"=>"Bill Receive",
            "type"=>"bill_receive",
            "cat"=>"Notice",
            'text'=>"Dear {{name}}, Top UP Tk {{amount}} successfully credited in your account {{client_id}}.\nThanks for choosing us.",
            'keyword'=>"{{name}},{{amount}},{{client_id}}",
            "status"=>1
        ],
        [
            "name"=>"Due",
            "type"=>"due",
            "cat"=>"Notice",
            'text'=>"Dear {{client_name}} ({{client_id}}), your payment {{due_amount}}tk has been due. please pay asap.",
            'keyword'=>"{{client_name}},{{client_id}},{{due_amount}}",
            "status"=>1
        ],
        [
           'name'=>"Welcome",
           "type"=>"welcome",
           "cat"=>"Greetings",
           'text'=>"Dear {{name}}, thanks to being with us.\nYour username: {{client_id}} and Password: {{password}}",
            'keyword'=>"{{name}},{{client_id}},{{password}}",
           "status"=>1
        ],
        [
           'name'=>"Bill Generate",
           "type"=>"bill_generate",
           "cat"=>"Notice",
           'text'=>"Dear {{name}} ({{client_id}}), the bill for the month of {{month_text}} has been generated as Tk {{bill_amount}}.\nUnless payment is not confirmed the link will automatically expire on {{date_with_suffix}} {{month_text}}. Total due Tk{{total_amount}}. ",
            'keyword'=>"{{name}},{{client_id}},{{month_text}},{{bill_amount}},{{date_with_suffix}},{{total_amount}}",
            "status"=>1
        ]
    ]
];