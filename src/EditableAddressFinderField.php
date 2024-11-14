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
}
