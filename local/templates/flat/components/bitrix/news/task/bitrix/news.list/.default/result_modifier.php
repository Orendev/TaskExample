<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */


if(!empty($arResult['ITEMS'])){
    foreach ($arResult['ITEMS'] as $key => $arItem){
        if(!empty($arItem['DISPLAY_PROPERTIES']['USER'])){
            $rsUser = CUser::GetByID((int)$arItem['DISPLAY_PROPERTIES']['USER']['DISPLAY_VALUE']);
            if($arUser = $rsUser->Fetch()){
                $arResult['ITEMS'][$key]['DISPLAY_PROPERTIES']['USER']['FULL_NAME'] = $arUser["NAME"];
            }
        }

    }
}
$cp = $this->__component; // объект компонента
if (is_object($cp))
{
    $arUsers = [];
    $order = array('NAME' => 'asc');
    $tmp = 'sort';
    $filter = Array
    (
        "ACTIVE"      => "Y",
        "WORK_COMPANY" => "Demo",
    );
    $userParams = array(
        'SELECT' => array(),
        'FIELDS' => array(
            'ID',
            'ACTIVE',
            'NAME',
            'LAST_NAME',
            'SECOND_NAME',
            'WORK_COMPANY',
            'WORK_POSITION',
            'WORK_PHONE'
        )
    );
    $rsUser =  CUser::GetList($order, $tmp, $filter, $userParams);
    while ($arItem = $rsUser->Fetch()) {
        $arUsers[] = $arItem;
    }

    $cp->arResult['USERS'] = $arUsers;

    $arStatus = [];
    $rsPropertyEnums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arResult["IBLOCK_ID"], "CODE"=>"STATUS"));
    while($arItem = $rsPropertyEnums->Fetch())
    {
        $arStatus[] = $arItem;
    }
    $cp->arResult['STATUS'] = $arStatus;

    $cp->SetResultCacheKeys(['STATUS', 'USERS']);

    $arResult['STATUS'] = $cp->arResult['STATUS'];
    $arResult['USERS'] = $cp->arResult['USERS'];


}