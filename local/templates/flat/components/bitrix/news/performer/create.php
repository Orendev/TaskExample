<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
global $APPLICATION;
$APPLICATION->IncludeComponent(
    'orendev:form',
    'performer',
    array(
        'CACHE_TIME' => 3600,
        'CACHE_TYPE' => 'A',
        'INPUT_REQ' => ['name', 'position', 'email']
    ), false
);
?>