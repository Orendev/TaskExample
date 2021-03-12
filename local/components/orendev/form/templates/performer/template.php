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
    <form action="<?=POST_FORM_ACTION_URI?>" method="post" id="js-form-performer">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="input[company]" value="Demo">
        <div class="form-group">
            <label class="sr-only" for="inlineFormName">Username</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Имя</div>
                </div>
                <input name="input[name]" type="text" class="form-control <?if(!empty($arResult['ERRORS']['name'])):?>is-invalid<?php endif;?>" id="inlineFormName"
                       placeholder="Имя" value="<?=$arResult['DATA']['name']?>">
                <?if(!empty($arResult['ERRORS']['name'])):?>
                    <div class="invalid-feedback">
                      <?=$arResult['ERRORS']['name']?>
                    </div>
                <?endif;?>
            </div>
        </div>
        <div class="form-group">
            <label class="sr-only" for="inlineFormEmail">Email</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Email</div>
                </div>
                <input name="input[email]" type="text" class="form-control <?if(!empty($arResult['ERRORS']['email'])):?>is-invalid<?php endif;?>" id="inlineFormEmail"
                       placeholder="Email" value="<?=$arResult['DATA']['email']?>">
                <?if(!empty($arResult['ERRORS']['email'])):?>
                    <div class="invalid-feedback">
                        <?=$arResult['ERRORS']['email']?>
                    </div>
                <?endif;?>
            </div>
        </div>
        <div class="form-group">
            <label class="sr-only" for="inlineFormPosition">Должность</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Должность</div>
                </div>
                <input name="input[position]" type="text" class="form-control <?if(!empty($arResult['ERRORS']['position'])):?>is-invalid<?php endif;?>" id="inlineFormPosition"
                       placeholder="Должность" value="<?=$arResult['DATA']['position']?>">
                <?if(!empty($arResult['ERRORS']['position'])):?>
                    <div class="invalid-feedback">
                        <?=$arResult['ERRORS']['position']?>
                    </div>
                <?endif;?>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Сохранить</button>
        <button type="reset" class="btn btn-danger">Отменить</button>
    </form>
<?php else:?>
    <div>
        <h2>Операция выполнена</h2>
        <p>Исполнитель добавлен</p>
        <a href="/performer/" class="btn btn-primary">Вернуться на страницу исполнителей</a>
    </div>
<?php endif;?>
