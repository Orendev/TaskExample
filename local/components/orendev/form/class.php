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
use Bitrix\Main\Result;
use Bitrix\Main\Error;
Loc::loadMessages(__FILE__);

class COrendevForm extends CBitrixComponent
{
    protected $uid;

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
    public function onPrepareComponentParams($arParams): array
    {
        return parent::onPrepareComponentParams($arParams);
    }

    public function getData(){
        $arParams = $this->arParams;
        $elementId = $this->arParams['ELEMENT_ID'];
        $templateName = $this->getTemplateName();
        if(empty($elementId)){
            return false;
        }
        if($templateName === 'performer'){
            $arUsers = CUser::GetByID($elementId)->Fetch();
            $arData = [];
            foreach ($arUsers as $arUser){
                $arData['name'] = $arUsers['NAME'];
                $arData['position'] = $arUsers['WORK_POSITION'];
                $arData['email'] = $arUsers['EMAIL'];
            }

        }

        if($templateName === 'task'){

            $rsEl = CIBlockElement::GetList(
                [],
                ["IBLOCK_ID"=>$arParams["IBLOCK_ID"], '=ID' => $arParams['ELEMENT_ID']], false, false,
                ['IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_TEXT', 'PROPERTY_USER', 'PROPERTY_STATUS']);
            if ($arTask = $rsEl->Fetch()){
                $arData['title'] = $arTask['NAME'];
                $arData['description'] = $arTask['PREVIEW_TEXT'];
            }

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
                $arItem['SELECTED'] = false;
                if(!empty($arTask['PROPERTY_USER_VALUE']) && $arTask['PROPERTY_USER_VALUE'] == $arItem['ID']){
                    $arItem['SELECTED'] = true;
                }
                $arUsers[] = $arItem;
            }
            $arData['USERS'] = $arUsers;

            $rsPropertyEnums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>"STATUS"));
            while($arItem = $rsPropertyEnums->Fetch())
            {
                $arItem['SELECTED'] = false;

                if(!empty($arTask['PROPERTY_STATUS_VALUE']) && $arTask['PROPERTY_STATUS_VALUE'] == $arItem['VALUE']){
                    $arItem['SELECTED'] = true;
                }
                $arStatus[] = $arItem;
            }
            $arData['STATUS'] = $arStatus;
        }

        $this->arResult['DATA'] = $arData;
    }

    public function executeComponent()
    {
        try
        {
            global $USER, $APPLICATION;

            if ($USER->IsAuthorized()) {
                $this->uid = $USER->GetID();
            } else {
                throw new Main\SystemException('not Authorized');
            }
            $this->checkModules();

            $this->arResult['isFormNote'] = 'N';
            $this->reset();
            $this->getData();
            if ($this->validate()) {
                $res = $this->save();
                if ($res->isSuccess()) {
                    $this->arResult['isFormNote'] = 'Y';
                    $this->reset();
                }else{
                    ShowError(implode(',', $res->getErrorMessages()));
                }
            }
            if ($this->request->isAjaxRequest()) {
                $APPLICATION->RestartBuffer();
            }

            $this->includeComponentTemplate();

            if ($this->request->isAjaxRequest()) {
                die();
            }

        }
        catch (Main\SystemException | Main\LoaderException $e)
        {
            ShowError($e->getMessage());
        }

    }

    public function validate(): bool
    {

        $this->arResult['ERRORS'] = [];
        $arData = [];
        if ($this->request->getPost('input')) {
            $arInput = $this->request->getPost('input');

            foreach ($arInput as $code => $value){

                switch ($code){
                    case 'email':{
                        if (in_array($code, $this->arParams['INPUT_REQ'], true) && empty($value)){
                            $this->arResult['ERRORS'][$code] = Loc::getMessage('ERR0R_INPUT_' . $code);
                        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)){
                            $this->arResult['ERRORS'][$code] =  Loc::getMessage('ERR0R_INPUT_FILTER_' . $code);
                        } else{
                            $arData[$code] = trim(htmlspecialchars($value));
                        }
                        break;
                    }
                    default:{
                        if (in_array($code, $this->arParams['INPUT_REQ'], true) && empty($value)){
                            $this->arResult['ERRORS'][$code] = Loc::getMessage('ERR0R_INPUT_' . $code);
                        } else{
                            $arData[$code] = trim(htmlspecialchars($value));
                        }
                    }
                }
            }

            $this->arResult['DATA'] = $arData;

            return empty($this->arResult['ERRORS']);
        }
        return false;
    }


    public function save(): Result
    {
        $result = new Result();
        $templateName = $this->getTemplateName();
        $arResult = [];

        if($templateName === 'performer'){
            $uid = $this->arParams['ELEMENT_ID'];
            $obUser = new CUser();
            $arFields = [
                'LOGIN' => $this->arResult['DATA']['email'],
                'NAME' => $this->arResult['DATA']['name'],
                'EMAIL' => $this->arResult['DATA']['email'],
                'WORK_POSITION' => $this->arResult['DATA']['position'],
                'WORK_COMPANY' => $this->arResult['DATA']['company'],
                "PASSWORD"          => "123456",
                "CONFIRM_PASSWORD"  => "123456",
            ];

            if(!empty($uid)){
                $id = $obUser->Update($uid, $arFields);
                if ($id > 0) {
                    $arResult['ID'] = $id;
                }else{
                    $result->addError(new Error( $obUser->LAST_ERROR));
                }
            }else{
                $id = $obUser->Add($arFields);
                if ($id > 0) {
                    $arResult['ID'] = $id;
                }else{
                    $result->addError(new Error( $obUser->LAST_ERROR));
                }
            }


        }
        if($templateName === 'task'){
            $elementId = $this->arParams['ELEMENT_ID'];

            if($elementId){
                $arFields = [
                    "MODIFIED_BY"    => $this->uid, // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                    "IBLOCK_ID"      => $this->arParams['IBLOCK_ID'],
                    "NAME"           => $this->arResult['DATA']["title"],
                    "ACTIVE"         => "Y",            // активен
                    "PREVIEW_TEXT"   => $this->arResult['DATA']["description"],
                ];

                if(!empty( $this->arResult['DATA']['status'])){
                    $arProps['STATUS'] = $this->arResult['DATA']['status'];
                }
                if(!empty($this->arResult['DATA']['user'])){
                    $arProps['USER'] = $this->arResult['DATA']['user'];
                }

                if(!empty($arProps)){
                    $arFields["PROPERTY_VALUES"] = $arProps;
                }

                $el = new \CIBlockElement();

                if($el->Update($elementId,$arFields)){
                    $arResult['ID'] = $elementId;
                }else{
                    $result->addError(new Error( $el->LAST_ERROR));
                }
            }

        }
        $result->setData($arResult);

        return $result;
    }

    public function reset(): void
    {
        $this->arResult['DATA'] = [];
    }

}
