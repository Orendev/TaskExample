<?php

namespace Orendev\Custom\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Loader;
use Bitrix\Main\Request;
use Bitrix\Main\Context;
use Bitrix\Main;
use Bitrix\Main\Result;
use Bitrix\Main\Error;

use Bitrix\Currency\CurrencyManager;


class Task extends Controller
{
    protected $uid = 0;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);


        try {
            $this->checkModules();
            global $USER;

            if ($USER->IsAuthorized()) {
                $this->uid = $USER->GetID();
            }

        } catch (\Exception $e) {
            $this->addError(new Error($e->getMessage()));
        }
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    protected function checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Main\LoaderException('not install module iblock');
        };

    }

    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'add' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST
                    ]),
                    new ActionFilter\Csrf()
                ],
                'postfilters' => []
            ],
            'delete' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([
                        ActionFilter\HttpMethod::METHOD_POST
                    ]),
                    new ActionFilter\Csrf()
                ],
                'postfilters' => []
            ]
        ];
    }

    public function deleteAction($id){
        $result = new Result();

        $el = new \CIBlockElement();
        if($el->delete($id)){
            $result->setData(['id' => $id]);
        } else{
            $this->addError(new Error('Error delete'));
        }

        return $result->getData();
    }

    public function addAction($fields = [])
    {
        $result = new Result();

        $validate = $this->validate($fields);

        if ($validate->isSuccess()) {

            $arFields = $this->preparationFields($fields);
            $el = new \CIBlockElement();
            if($id = $el->Add($arFields)){
                $result->setData(array_merge(['id' => $id], $fields));
            } else{
                $this->addError(new Error($el->LAST_ERROR));
            }
        } else {
            $this->addErrors($validate->getErrors());
        }

        return $result->getData();
    }

    public function validate(array $fields)
    {
        $result = new Result();
        if (empty($fields)) {
            $result->addError(new Error('поля не могут быть пустыми', 'all'));
        }

        foreach ($fields as $code => $value) {
            switch ($code) {
                case 'title':
                {
                    if (empty($value)) {
                        $result->addError(new Error('Название задачи обязательно', $code));
                    }
                    break;
                }
                case 'user':
                {
                    if (empty($value)) {
                        $result->addError(new Error('Исполнитель обязательное поле', $code));
                    }
                    break;
                }
            }
        }


        return $result;
    }

    public function preparationFields($fields): array
    {
        if(!empty( $fields['status'])){
            $arProps['STATUS'] = $fields['status'];
        }
        if(!empty($fields['user'])){
            $arProps['USER'] = $fields['user'];
        }

        $arFields = [
            "MODIFIED_BY"    => $this->uid, // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"      => \Orendev\Custom\Helpers\Utils::getIblockId('task'),
            "NAME"           => $fields["title"],
            "ACTIVE"         => "Y",            // активен
            "PREVIEW_TEXT"   => $fields["description"],
        ];

        if(!empty($arProps)){
            $arFields["PROPERTY_VALUES"] = $arProps;
        }

        return $arFields;
    }

}