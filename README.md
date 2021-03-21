# AddressFinder for Silverstripe

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fullscreeninteractive/silverstripe-addressfinder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fullscreeninteractive/silverstripe-addressfinder/?branch=master)
[![Build Status](https://travis-ci.org/fullscreeninteractive/silverstripe-addressfinder.svg?branch=master)](https://travis-ci.org/fullscreeninteractive/silverstripe-addressfinder)
[![Latest Stable Version](https://poser.pugx.org/fullscreeninteractive/silverstripe-addressfinder/v/stable)](https://packagist.org/packages/fullscreeninteractive/silverstripe-addressfinder)

## Maintainer Contact
 * Will Rossiter
   <will (at) fullscreen (dot) io>

## Requirements
 * SilverStripe 4.0 or higher

## Overview

This module provides a custom Silverstripe `AddressFinderField` which implements the
javascript AddressFinder widget ([1](http://addressfinder.co.nz/docs/widget_docs)) for address and postcode lookups in New Zealand and Australia.

## Getting Started

To get started, sign up for an account at
[addressfinder.co.nz/plans](http://addressfinder.co.nz/plans) and set your
AddressFinder key values via the Silverstripe Config system

*mysite/_config/addressfinder.yml*
```
FullscreenInteractive\SilverStripe\AddressFinderField:
  api_key: 123
```

Or you can use environment variables if you prefer

*mysite/_config/addressfinder.yml*
```
FullscreenInteractive\SilverStripe\AddressFinderField:
  api_key: '`ADDRESS_FINDER_KEY`'
```

Then add an instance of `AddressFinderField` to your form fields

```
use FullscreenInteractive\SilverStripe\AddressFinderField;

$fields->push(new AddressFinderField('Address'));
```

This will provide your form with a single text box that provides an autocomplete
dropdown as well as a toggle for the user to enter a manual address in the event
the API is inaccessible.

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

To prefix these fields, call `setFieldPrefix($prefix)`  on your `AddressFinderField` 
instance.

```php
AddressFinderField::create('HomeAddress')
    ->setFieldPrefix('Home')
AddressFinderField::create('WorkAddress')
    ->setFieldPrefix('Work')

// requires the following model
private static $db = [
    'HomeAddress' => 'Text',
    'HomeAddressPostalLine1' => 'Varchar(200)',
    'HomeAddressPostalLine2' => 'Varchar(200)',
    'HomeAddressPostalLine3' => 'Varchar(200)',
    'HomeAddressPostalLine4' => 'Varchar(200)',
    'HomeAddressPostalLine5' => 'Varchar(200)',
    'HomeAddressPostalLine6' => 'Varchar(200)',
    'HomeAddressSuburb' => 'Varchar(200)',
    'HomeAddressCity' => 'Varchar(200)',
    'HomeAddressPostcode' => 'Varchar(200)',
    'HomeAddressLatitude' => 'Varchar(200)',
    'HomeAddressLongitude' => 'Varchar(200)',
    'WorkAddress' => 'Text',
    'WorkAddressPostalLine1' => 'Varchar(200)',
    'WorkAddressPostalLine2' => 'Varchar(200)',
    //...
];
```
