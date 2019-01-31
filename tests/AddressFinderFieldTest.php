<?php

namespace FullscreenInteractive\SilverStripe\Tests;

use SilverStripe\Dev\SapphireTest;
use FullscreenInteractive\SilverStripe\AddressFinderField;

class AddressFinderFieldTest extends SapphireTest
{
    public function testConstructor()
    {
        $field = new AddressFinderField('name', 'Title');

        $this->assertEquals('Title', $field->getTitle());
        $this->assertEquals(6, $field->getManualFields()->count());
    }
}
