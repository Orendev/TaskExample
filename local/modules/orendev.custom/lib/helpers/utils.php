<?php

namespace Orendev\Custom\Helpers;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\IO;
use Bitrix\Main\Result;
use Bitrix\Main\Type;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Loader;
use Bitrix\Iblock;
use Bitrix\Main\Data;
use Bitrix\Main\SystemException;


class Utils
{
    const MODULE_ID = 'orendev.custom';


    /**
     * @param $entity
     * @param $data
     * @param string $title
     * @param string $logFile
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     */
    public static function logger($entity, $data, $title = '', $logFile = 'log.log')
    {

        $pathLog = Option::get(self::MODULE_ID, 'FOLDER_LOG', '/upload/logs/');

        $path = Application::getDocumentRoot() . $pathLog;

        $dir = new IO\Directory($path);

        if (!$dir->isExists()) {
            $dir->create();
        }

        $objDateTime = new Type\DateTime();

        $file = $pathLog . $logFile;

        Debug::writeToFile(
            [
                'TITLE' => $title,
                'ENTITY' => $entity,
                'DATE' => $objDateTime->format("Y-m-d H:i:s"),
                'DATA' => [$data]
            ],
            'LOG: ' . $title . ' : ' . $objDateTime->format("Y-m-d H:i:s"),
            $file
        );
    }

    /**
     * @param $input
     * @param int $start
     * @param int $level
     * @return array
     */
    public static function getChilds($input, &$start = 0, $level = 0): array
    {
        $childs = [];

        if (!$level) {
            $lastDepthLevel = 1;
            if (is_array($input)) {
                foreach ($input as $i => $arItem) {
                    if ($arItem["DEPTH_LEVEL"] > $lastDepthLevel) {
                        if ($i > 0) {
                            $input[$i - 1]["IS_PARENT"] = 1;
                        }
                    }
                    $lastDepthLevel = $arItem["DEPTH_LEVEL"];
                }
            }
        }

        for ($i = $start, $count = count($input); $i < $count; ++$i) {
            $item = $input[$i];
            if ($level > $item['DEPTH_LEVEL'] - 1) {
                break;
            }

            if (!empty($item['IS_PARENT'])) {
                ++$i;
                $item['CHILD'] = self::getChilds($input, $i, $level + 1);
                --$i;
            }
            $childs[] = $item;
        }

        $start = $i;

        return $childs;
    }

    public static function getIblockId($code)
    {
        $result = null;
        \Bitrix\Main\Loader::includeModule('iblock');
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache(86400, 'iblock|id|' . $code, 'iblockid')) {
            $arIblock = $cache->getVars();
            $result = $arIblock['ID'];
        } else {
            $dbIblock = \Bitrix\Iblock\IblockTable::query()
                ->where('CODE', $code)
                ->setSelect(['ID'])
                ->exec();
            if ($arIblock = $dbIblock->fetch()) {
                $result = $arIblock['ID'];
            } else {
                throw new \Bitrix\Main\ArgumentException('iblock not found');
            }
        }

        return $result;
    }
}