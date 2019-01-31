# AddressFinder for SilverStripe

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fullscreeninteractive/silverstripe-addressfinder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fullscreeninteractive/silverstripe-addressfinder/?branch=master)
[![Build Status](https://travis-ci.org/fullscreeninteractive/silverstripe-addressfinder.svg?branch=master)](https://travis-ci.org/fullscreeninteractive/silverstripe-addressfinder)
[![Latest Stable Version](https://poser.pugx.org/fullscreeninteractive/silverstripe-addressfinder/v/stable)](https://packagist.org/packages/fullscreeninteractive/silverstripe-addressfinder)

## Maintainer Contact
 * Will Rossiter
   <will (at) fullscreen (dot) io>

## Requirements
 * SilverStripe 4.0 or higher

## Overview

This module provides a custom `AddressFinderField` which implements the
javascript AddressFinder widget ([1](http://addressfinder.co.nz/docs/widget_docs))

To get started, sign up for an account at
[addressfinder.co.nz/plans](http://addressfinder.co.nz/plans) and set your
AddressFinder key values via the Config system

*mysite/_config/addressfinder.yml*
```
FullscreenInteractive\SilverStripe\AddressFinderField:
  api_key: 123
```

Then add an instance of `AddressFinderField` to your form fields

```
use FullscreenInteractive\SilverStripe\AddressFinderField;

$fields->push(new AddressFinderField('Address'));
```

This will provide your form with a single text box that provides an autocomplete
dropdown as well as a toggle for the user to enter a manual address in the event
the API is unaccessible.

The form field provides the saveInto logic for automatically saving into a
DataObject model if defined. The fields that the module will save too (if in the
database) are

* Address *single line representation, should be the name of your field*
* PostalLine1
* PostalLine2
* PostalLine3
* PostalLine4
* PostalLine5
* PostalLine6
* Suburb
* City
* Postcode
* Latitude
* Longitude

An example model which will capture all the information from AddressFinder is
outlined below:

```php
<?php

use SilverStripe\ORM\DataObject;

class AddressObject extends DataObject
{
	private static $db = [
		'Address' => 'Text',
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
```

