<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main\Loader;
use Orendev\Custom\Helpers;

if(Loader::includeModule('orendev.custom') && !empty($arResult)){
    $arResult = Helpers\Utils::getChilds($arResult);

    $isCatalog=false;
    $arCatalogItem=array();
    foreach($arResult as $key=>$arItem){
        if(isset($arItem["PARAMS"]["NOT_SHOW"]) && $arItem["PARAMS"]["NOT_SHOW"]=="Y"){
            unset($arResult[$key]);
        }
        if($arItem["CHILD"]){
            foreach($arItem["CHILD"] as $key2=>$arChild){
                if(isset($arChild["PARAMS"]["NOT_SHOW"]) && $arChild["PARAMS"]["NOT_SHOW"]=="Y"){
                    unset($arResult[$key]["CHILD"][$key2]);
                }
                if($arChild["PARAMS"]["PICTURE"]){
                    $img=CFile::ResizeImageGet($arChild["PARAMS"]["PICTURE"], Array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    $arResult[$key]["CHILD"][$key2]["PARAMS"]["IMAGES"]=$img;
                }
            }
        }
    }
}