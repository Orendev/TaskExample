<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode( true );
?>

<?if( !empty( $arResult ) ):?>
    <ul class="nav nav-tabs">
        <?foreach( $arResult as $key => $arItem ):?>
            <li class="nav-item">
                <a class="nav-link <?=($arItem["SELECTED"] ? "active" : "")?>" href="<?=$arItem["LINK"]?>">
                    <?=$arItem["TEXT"]?>
                </a>
            </li>
        <?php endforeach;?>
    </ul>
<?php endif;?>
