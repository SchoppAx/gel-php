
# gel-php - gel API v3 PHP 7.2+ library

An easy-to-use PHP package to communicate with [gel's API].

### Installation

`composer require schoppax/gel-php`

or add `"schoppax/gel-php"` in your "require" object of your project `composer.json`.

### Example

```php
<?php

require 'vendor/autoload.php';

$apiKey = 'api-key';
$depotNr = 0;
$knr = 0;
$test = true;
$gel = new mehrWEBnet\Gel\Gel($apiKey, $depotNr, $knr, $test);

// Modify Shipment By SNR
// var_dump($gel->shipments()->modify('snr', [parameters]));

// List all carriers
// var_dump($gel->shipmentQuotes()->create([parameters]));

try {
    var_dump($gel->shipments()->create([
        'sname1'    => 'company name',
        'sname1'    => 'first name',
        'sname2'    => 'lastname',
        'sstreet'   => 'street',
        'scountry'  => 'D',
        'szipcode'  => 'zip_code',
        'stown'     => 'city',
        'cname1'    => 'name',
        'cstreet'   => 'street',
        'ccountry'  => 'D',
        'ctown'     => 'city',
        'srv'       => '2',
        'xsrv>'     => 'A',
        'collicnt'  => 'int',
        'colli'     => '|weight|length|width|height', // in cm
        'avis'      => 'phone',
        'atype'    => 'A3',
        'email'    => 'mail',
        'rcall'    => ''
    ]));    
} catch(GuzzleHttp\Exception\ClientException $t) {
    var_dump($t);
}
```
