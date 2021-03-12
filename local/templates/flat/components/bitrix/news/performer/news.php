<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
global $APPLICATION;
$APPLICATION->IncludeComponent(
    'orendev:performer',
    '.default',
    array(
        'CACHE_TIME' => 3600,
        'CACHE_TYPE' => 'A',
    ), false
);
?>
