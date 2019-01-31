<?php

namespace FullscreenInteractive\SilverStripe\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\RequiredFields;
use FullscreenInteractive\SilverStripe\AddressFinderField;

class AddressFinderFieldTest extends SapphireTest
{
    public function testConstructor()
    {
        $field = new AddressFinderField('name', 'Title');

        $this->assertEquals('Title', $field->Title());
        $this->assertEquals(11, $field->getManualFields()->count(), '11 address fields');
    }

    public function testSetRequireLatLngManual()
    {
        $field = new AddressFinderField('name', 'Title');
        $field = $field->setRequireLatLngManual(true);

        $fieldHolder = $field->FieldHolder();

        $this->assertContains('input type="text" name="name[Longitude]"', $fieldHolder);
    }

    public function testValidator()
    {
        $field = new AddressFinderField('name', 'Title');
        $required = new RequiredFields('name');

        $this->assertFalse($field->validate($required));

        $field->setValue([
            'Address' => '1 Test Street, Test Land, 90210',
            'PostalLine1' => '1 Test Street',
            'City' => 'Test Land',
            'Postcode' => '90210'
        ]);

        $this->assertTrue($field->validate($required));
    }
}
