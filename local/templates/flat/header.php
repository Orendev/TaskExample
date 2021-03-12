<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;

global $APPLICATION, $USER;
Loc::loadMessages(__FILE__);

?>
<!doctype html>

<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php $APPLICATION->ShowHead(); ?>
    <title><?php $APPLICATION->ShowTitle() ?></title>

    <?php
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/bootstrap.min.css');
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/jBox.all.min.css');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jquery-3.6.0.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/popper.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/bootstrap.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jBox.all.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/main.js');
    ?>

</head>

<body class="<?php $APPLICATION->ShowProperty('body_class') ?>">
<?php if ($USER->IsAdmin()) { ?>
    <div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>
<? } ?>
<!-- BEGIN page-->
<main class="container">


