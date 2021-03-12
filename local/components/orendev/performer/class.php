<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CCacheManager $CACHE_MANAGER */

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


class COrendevPerformer extends CBitrixComponent
{
    protected $uid = 0;
    protected $errors = [];

    /**
     * @throws Main\LoaderException
     */
    protected function checkModules(): void
    {
        if (!Loader::includeModule('iblock')) {
            throw new Main\LoaderException('not install module iblock');
        }

        if (!Loader::includeModule('orendev.custom')) {
            throw new Main\LoaderException('not install module orendev.custom');
        }

    }

    public function onPrepareComponentParams($arParams)
    {
        if(!isset($arParams["CACHE_TIME"])){
            $arParams["CACHE_TIME"] = 36000000;
        }

        return parent::onPrepareComponentParams($arParams);
    }

    protected function prepareDate(&$arItem) {

    }

    protected function getResult()
    {
        if ($this->errors){
            throw new Main\SystemException( implode(',', $this->errors) );
        }

        $arParams = $this->arParams;
        $arResult = [];
        $order = array('sort' => 'asc');
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
            $this->prepareDate($arItem);
            $arResult["USERS"][] = $arItem;
        }

        $this->arResult = $arResult;
    }


    public function executeComponent()
    {
        try
        {
            global $USER;


            if ($USER->IsAuthorized()) {
                $this->uid = $USER->GetID();
            } else {
                throw new Main\SystemException('not Authorized');
            }

            $this->checkModules();
            $this->getResult();
            $this->includeComponentTemplate();
        }
        catch (Main\SystemException | Main\LoaderException $e)
        {
            ShowError($e->getMessage());
        }

    }



}