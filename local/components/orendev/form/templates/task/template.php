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
?>
<?php if($arResult['isFormNote'] !== 'Y'):?>
    <form class="needs-validation" action="<?=POST_FORM_ACTION_URI?>" method="post" id="js-form-performer">
        <?=bitrix_sessid_post()?>
        <div class="form-group">
            <label class="sr-only" for="inlineFormInputGroup">Username</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Название</div>
                </div>
                <input type="text" class="form-control <?if(!empty($arResult['ERRORS']['title'])):?> is-invalid <?endif?>" id="inlineFormInputGroup" name="input[title]"
                       placeholder="Название задачи" value="<?=$arResult['DATA']['title']?>">
                <div class="invalid-feedback"><?=$arResult['ERRORS']['title']?></div>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Исполнитель</div>
                </div>
                <select class="form-control <?if(!empty($arResult['ERRORS']['status'])):?> is-invalid <?endif?>" name="input[user]">
                    <option disabled value="">Исполнитель</option>
                    <?foreach ($arResult['DATA']['USERS'] as $user):?>
                        <option value="<?=$user['ID']?>" <?if($user['SELECTED']):?> selected<?endif?> > <?=$user['NAME']?> </option>
                    <?endforeach;?>
                </select>
                <div class="invalid-feedback"><?=$arResult['ERRORS']['user']?></div>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Статус</div>
                </div>
                <select class="form-control <?if(!empty($arResult['ERRORS']['status'])):?> is-invalid <?endif?>" name="input[status]">
                    <option disabled value="">Статус задачи</option>
                    <?foreach ($arResult['DATA']['STATUS'] as $status):?>
                        <option value="<?=$status['ID']?>" <?if($status['SELECTED']):?> selected<?endif?>><?=$status['VALUE']?></option>
                    <?endforeach;?>
                </select>
                <div class="invalid-feedback"><?=$arResult['ERRORS']['status']?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="sr-only" for="message-text">Описание</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Описание</div>
                </div>
                <textarea class="form-control" id="message-text" name="input[description]"><?=$arResult['DATA']['description']?></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-success js-add">Сохранить</button>
        <button type="reset" class="btn btn-danger">Отменит</button>
    </form>
<?php else:?>
    <div>
        <h2>Операция выполнена</h2>
        <p></p>
        <a href="/task/" class="btn btn-primary">Вернуться в список задач</a>
    </div>
<?php endif;?>
