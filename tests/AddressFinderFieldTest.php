<?php

namespace FullscreenInteractive\SilverStripe\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\Validation\RequiredFieldsValidator;
use FullscreenInteractive\SilverStripe\AddressFinderField;

class AddressFinderFieldTest extends SapphireTest
{
    public function testConstructor()
    {
        $field = new AddressFinderField('name', 'Title');

        $this->assertEquals('Title', $field->Title());
        $this->assertEquals(12, $field->getManualFields()->count(), '12 manual address fields');
    }

    public function testSetRequireLatLngManual()
    {
        $field = new AddressFinderField('name', 'Title');
        $field = $field->setRequireLatLngManual(true);

        $fieldHolder = $field->FieldHolder();

        $this->assertStringContainsString('input type="text" name="name[Longitude]"', $fieldHolder);
    }

    public function testValidator()
    {
        $field = new AddressFinderField('name', 'Title');
        $validator = RequiredFieldsValidator::create(['name']);
        $form = new Form(null, 'TestForm', FieldList::create($field), FieldList::create(), $validator);

        $this->assertFalse($field->validate()->isValid());

        $field->setValue([
            'Address' => '1 Test Street, Test Land, 90210',
            'PostalLine1' => '1 Test Street',
            'City' => 'Test Land',
            'Postcode' => '90210'
        ]);

        $this->assertTrue($field->validate()->isValid());
    }
}
