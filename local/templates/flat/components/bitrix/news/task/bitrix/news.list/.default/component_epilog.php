<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {die();}
/** @var array $arParams */
/** @var array $arResult */

?>

<div class="modal" tabindex="-1" role="dialog" id="js-task-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление задачи</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" action="<?=POST_FORM_ACTION_URI?>" >
                    <div class="form-group">
                        <label class="sr-only" for="inlineFormInputGroup">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Название</div>
                            </div>
                            <input type="text" class="form-control" id="inlineFormInputGroup" name="title"
                                   placeholder="Название задачи">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Исполнитель</div>
                            </div>
                            <select class="form-control" name="user">
                                <option selected disabled value="">Исполнитель</option>
                                <?foreach ($arResult['USERS'] as $user):?>
                                    <option value="<?=$user['ID']?>"><?=$user['NAME']?></option>
                                <?endforeach;?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Статус</div>
                            </div>
                            <select class="form-control" name="status">
                                <option selected disabled value="">Статус задачи</option>
                                <?foreach ($arResult['STATUS'] as $status):?>
                                    <option value="<?=$status['ID']?>"><?=$status['VALUE']?></option>
                                <?endforeach;?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="message-text">Описание</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Описание</div>
                            </div>
                            <textarea class="form-control" id="message-text" name="description"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success js-add">Сохранить</button>
                    <button type="reset" class="btn btn-danger">Отменит</button>
                </form>
            </div>
        </div>
    </div>
</div>
