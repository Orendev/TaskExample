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


class Performer extends Controller
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

        $user = new \CUser();
        if($user->delete($id)){
            $result->setData(['id' => $id]);
        } else{
            $this->addError(new Error('Error delete'));
        }

        return $result->getData();
    }
}