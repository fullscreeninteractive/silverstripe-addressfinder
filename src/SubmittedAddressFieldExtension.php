<?php

namespace FullscreenInteractive\SilverStripe;

use SilverStripe\Core\Extension;

class SubmittedAddressFieldExtension extends Extension
{

    public function onPopulationFromField($field)
    {
        $recordData = $field->getRecordData();

        $keys = [
            'PostalLine1',
            'PostalLine2',
            'PostalLine3',
            'PostalLine4',
            'PostalLine5',
            'PostalLine6',
            'Suburb',
            'City',
            'Postcode',
            'Latitude',
            'Longitude'
        ];

        foreach ($keys as $key) {
            if (isset($recordData[$key])) {
                $this->owner->$key = $recordData[$key];
            }
        }
    }
}
