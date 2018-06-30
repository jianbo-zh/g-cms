<?php
declare(strict_types=1);

function testParams(string $authCode, int $state=null)
{
    return $state;
}

$result = testParams('jjjj');

var_dump($result);