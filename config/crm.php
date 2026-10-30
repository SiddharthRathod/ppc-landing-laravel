<?php

return [

    /*
    | Turn the CRM sync off (e.g. locally). Submissions are still saved,
    | with crm_status "skipped".
    */
    'enabled' => env('CRM_ENABLED', true),

    /*
    | Which CrmClient class to use, see AppServiceProvider. Supported: "log".
    */
    'driver' => env('CRM_DRIVER', 'log'),

];
