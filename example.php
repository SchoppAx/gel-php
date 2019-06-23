<?php

require 'vendor/autoload.php';

$gel = new mehrWEBnet\Gel\Gel('api-key', 'depotNr', 'knr', ['test']);

// Modify Shipment By SNR
// var_dump($gel->shipments()->modify('snr', '[parameters]'));

// List all carriers
// var_dump($gel->shipmentQuotes()->create('[parameters]'));

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
    'xsrv>'     => 'A'
    'collicnt'  => 'int',
    'colli'     => '|weight|length|width|height', // in cm
    'avis'      => 'phone',
    'atype'    => 'A3',
    'email'    => 'mail',
    'rcall'    => ''
]));
