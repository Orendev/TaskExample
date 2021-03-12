<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this->setFrameMode(true);
use Bitrix\Main\Web;
?>

<table class="table table-bordered" id="js-table">
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">Название</th>
        <th scope="col">Исполнитель</th>
        <th scope="col">Статус</th>
        <th scope="col">Описание</th>
        <th scope="col">Действие</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?

        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <tr id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <th scope="row"><?= $arItem['ID'] ?></th>
            <td><?= $arItem['NAME'] ?></td>
            <td><?=!empty($arItem['DISPLAY_PROPERTIES']['USER']) ? $arItem['DISPLAY_PROPERTIES']['USER']['FULL_NAME'] : ''?></td>
            <td>
                <?= !empty($arItem['DISPLAY_PROPERTIES']['STATUS']) ? $arItem['DISPLAY_PROPERTIES']['STATUS']['DISPLAY_VALUE'] : ''?>
            </td>
            <td><?= $arItem['PREVIEW_TEXT'] ?></td>
            <td><a href="javascript:void(0)" class="js-delete" data-id="<?=$arItem['ID']?>" >Удалить</a> / <a href="detail/<?= $arItem['ID'] ?>/?action=edit">Редактировать</a></td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>

<div class="d-flex justify-content-end">
    <button class="btn btn-success" data-toggle="modal" data-target="#js-task-modal" id="js-add">Добавить</button>
</div>


<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
    <br/><?= $arResult["NAV_STRING"] ?>
<? endif; ?>


<script>
    BX.ready(function () {
        let params, result, sessid;
        params = <?=Web\Json::encode($arParams)?>;
        result = <?=Web\Json::encode($arResult)?>;
        sessid = "<?=bitrix_sessid()?>";


        if (BX.Orendev.Task) {
            BX.Orendev.Task.init({
                'params': params,
                'result': result,
                'sessid': sessid
            });
        }
    })
</script>