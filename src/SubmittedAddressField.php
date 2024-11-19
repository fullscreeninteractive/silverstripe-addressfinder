<?php

namespace FullscreenInteractive\SilverStripe;

use SilverStripe\UserForms\Model\Submission\SubmittedFormField;

if (!class_exists(SubmittedFormField::class)) {
    return;
}

class SubmittedAddressField extends SubmittedFormField
{
    private static $table_name = 'SubmittedAddressField';

    private static $extensions = [
        SubmittedAddressFieldExtension::class
    ];

    private static $db = [
        'PostalLine1' => 'Varchar(200)',
        'PostalLine2' => 'Varchar(200)',
        'PostalLine3' => 'Varchar(200)',
        'PostalLine4' => 'Varchar(200)',
        'PostalLine5' => 'Varchar(200)',
        'PostalLine6' => 'Varchar(200)',
        'Suburb' => 'Varchar(200)',
        'City' => 'Varchar(200)',
        'Postcode' => 'Varchar(200)',
        'Latitude' => 'Varchar(200)',
        'Longitude' => 'Varchar(200)'
    ];
}
