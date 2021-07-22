<?php

use App\Service\Slugify;

require_once dirname(__FILE__) . '/../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color());

$t->is(Slugify::generate('Sensio'), 'sensio');
$t->is(Slugify::generate('sensio labs'), 'sensio-labs');
$t->is(Slugify::generate('sensio   labs'), 'sensio-labs');
$t->is(Slugify::generate('paris,france'), 'paris-france');
$t->is(Slugify::generate('  sensio'), 'sensio');
$t->is(Slugify::generate('sensio  '), 'sensio');
