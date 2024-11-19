<?php

namespace FullscreenInteractive\SilverStripe;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\UserForms\Model\EditableFormField;


if (class_exists(EditableFormField::class)) {
    return;
}

class EditableAddressFinderField extends EditableFormField
{
    private static $singular_name = 'AddressFinder Field';

    private static $plural_name = 'AddressFinder Fields';

    private static $table_name = 'EditableAddressFinderField';

    private static $db = [
        'ShowManualFields' => 'Boolean',
    ];

    private $recordData = null;

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldToTab(
                'Root.Main',
                CheckboxField::create(
                    'ShowManualFields',
                    _t('SilverStripe\\UserForms\\Model\\EditableFormField.SHOWMANUALFIELDS', 'Show manual fields?')
                )
            );
        });

        return parent::getCMSFields();
    }


    public function getFormField()
    {
        $field = AddressFinderField::create($this->Name, $this->Title ?: false)
            ->setFieldHolderTemplate('EditableAddressFinderField_holder')
            ->setTemplate(EditableFormField::class);

        if ($this->ShowManualFields) {
            $field->setShowManualFields(true);
        } else {
            $field->setShowManualFields(false);
        }

        $this->doUpdateFormField($field);

        return $field;
    }


    public function getValueFromData($data)
    {
        $this->recordData = isset($data[$this->Name]) ? $data[$this->Name] : null;

        if ($this->recordData && is_array($this->recordData)) {
            if (isset($this->recordData['Address'])) {
                return $this->recordData['Address'];
            }

            // no address found so return all the manul fields imploded
            $manual = [];

            foreach ($this->recordData as $key => $value) {
                if ($key === 'Latitude' || $key === 'Longitude') {
                    continue;
                }

                if ($key !== 'Address') {
                    $manual[] = $value;
                }
            }

            $latLng = '';

            if (isset($this->recordData['Latitude']) && isset($this->recordData['Longitude'])) {
                $latLng = ' (' . $this->recordData['Latitude'] . ', ' . $this->recordData['Longitude'] . ')';
            }

            return implode(', ', $this->manual) . $latLng;
        }

        return '';
    }


    public function getSubmittedFormField()
    {
        return SubmittedAddressField::create();
    }


    public function getRecordData()
    {
        return $this->recordData;
    }
}
