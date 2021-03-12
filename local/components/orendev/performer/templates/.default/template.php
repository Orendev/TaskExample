<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web;
?>
<table class="table table-bordered" id="js-table">
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">Имя</th>
        <th scope="col">Должность</th>
        <th scope="col">Действие</th>
    </tr>
    </thead>
    <tbody>
    <?foreach ($arResult['USERS'] as $arUser):?>
        <tr>
            <th scope="row"><?=$arUser['ID']?></th>
            <td><?=$arUser['NAME']?></td>
            <td><?=$arUser['WORK_POSITION']?></td>
            <td><a href="javascript:void(0)" data-id="<?=$arUser['ID']?>" class="js-delete">Удалить</a> / <a href="detail/<?=$arUser['ID']?>/?action=edit">Редактировать</a></td>
        </tr>
    <?endforeach;?>
    </tbody>
</table>

<div class="d-flex justify-content-end">
    <a href="/performer/create/" class="btn btn-success">Добавить</a>
</div>
<script>
    BX.ready(function () {
        let params, result, sessid;
        params = <?=Web\Json::encode($arParams)?>;
        result = <?=Web\Json::encode($arUser)?>;
        sessid = "<?=bitrix_sessid()?>";


        if (BX.Orendev.Performer) {
            BX.Orendev.Performer.init({
                'params': params,
                'result': result,
                'sessid': sessid
            });
        }
    })
</script>
