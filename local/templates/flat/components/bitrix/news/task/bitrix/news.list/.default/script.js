BX.namespace('BX.Orendev.Task');

(function () {
    'use strict';
    BX.Orendev.Task = (function () {

        let Task = function (ctx) {
            this.context = ctx || {};
            this.controls = {};
            this.isMobile = BX.browser.IsMobile();
            return this;
        };

        Task.prototype.init = function (params) {
            this.params = params.params || {};
            this.result = params.result || {};
            this.sessid = params.sessid || '';

            this.input = {
                title: '',
                user: '',
                status: '',
                description: ''
            }
            this.errors = {
                title: '',
                user: '',
                status: '',
            }

            this.controls.modal = 'js-task-modal';

            this.element = {
                title: BX(this.controls.modal).querySelector('[name="title"]'),
                user: BX(this.controls.modal).querySelector('[name="user"]'),
                status: BX(this.controls.modal).querySelector('[name="status"]'),
                description: BX(this.controls.modal).querySelector('[name="description"]')
            }



            this.bindEvents();
        }

        Task.prototype.bindEvents = function () {
            BX.bindDelegate(
                BX(this.controls.modal),
                'click',
                {tagName: 'button', className: 'js-add'},
                BX.delegate(this.addHandler, this)
            );
            BX.bindDelegate(
                BX('js-table'),
                'click',
                {tagName: 'a', className: 'js-delete'},
                BX.delegate(this.deleteHandler, this)
            );
        }

        Task.prototype.deleteHandler = function (event){
            let that = this;
            let target = event.target || event.srcElement,
                id = target.getAttribute('data-id'),
                el = document.getElementById("js-table").querySelector('tbody');

            if (id){
                let request = this.sendPost('delete', {id: id})

                request.then(function (response){
                    if(response.status === "success" && el){
                        el.removeChild(target.closest('tr'));
                        new jBox('Notice', {content: 'Задача удалена', color: 'green'});
                    }else{

                    }
                }, function (reason){
                    if(response.status === "error"){

                    }
                })
            }

            event.preventDefault();
        }

        Task.prototype.addHandler = function (event){
            let that = this;
            let target = event.target || event.srcElement,
                el = document.getElementById("js-table").querySelector('tbody');
            this.getInput();
            if (this.validate('add')){
                let request = this.sendPost('add', {fields: this.input})
                let user, status;
                request.then(function (response){
                    if(response.status === "success"){
                        $('#js-task-modal').modal('hide');
                        if(el){
                            if(Object.prototype.hasOwnProperty.call(that.result, 'STATUS')){
                                status = that.result.STATUS.find(item => item.ID == response.data.status);

                            }
                            if(Object.prototype.hasOwnProperty.call(that.result, 'USERS')){
                                user = that.result.USERS.find(item => item.ID == response.data.user);
                            }

                            if(user && status){
                                let tr = document.createElement('tr');
                                tr.innerHTML = '<th>'+response.data.id+'</th><td>' +
                                    response.data.title+'</td><td>' +
                                    user.NAME+'</td><td>' +
                                    status.PROPERTY_NAME+'</td><td>' +
                                    response.data.description+'</td><td>' +
                                    '<a href="javascript:void(0)" class="js-delete" data-id="'+response.data.id+'">Удалить</a> /' +
                                    '<a href="detail/'+response.data.id+'/?action=edit">Редактировать</a></td>';
                                el.prepend(tr);
                            }

                        }
                        new jBox('Notice', {content: 'Задача добавлена', color: 'green'});
                    }else{

                    }
                }, function (reason){
                    if(response.status === "error"){

                    }
                })
            }

            this.showError();

            event.preventDefault();
        }

        Task.prototype.getInput = function (){
            if(this.element.title){
                this.input.title = this.element.title.value;
            }
            if(this.element.status){
                this.input.status = this.element.status.value;
            }
            if(this.element.user){
                this.input.user = this.element.user.value;
            }
            if(this.element.description){
                this.input.description = this.element.description.value;
            }
        }

        Task.prototype.validate = function (action = 'add'){
            let isValid = true;

            if(action === 'add'){
                if (!this.input.title){
                    this.errors.title = 'Обязательное поле';
                    isValid = false;
                }else {
                    this.errors.title = ""
                }
                if (!this.input.status){
                    this.errors.status = 'Обязательное поле';
                    isValid = false;
                }else {
                    this.errors.status = "";
                }

                if (!this.input.user){
                    this.errors.user = 'Обязательное поле';
                    isValid = false;
                }else {
                    this.errors.user = "";
                }
            }


            return isValid
        }


        Task.prototype.sendPost = function (action, data) {
            return BX.ajax.runAction('orendev:custom.api.task.' + action, {
                data: data
            });
        }

        Task.prototype.showError = function () {
            let that = this;

            if(this.errors.title){
                this.element.title.closest('.input-group').querySelector(".invalid-feedback").innerHTML = this.errors.title;
                if(!this.element.title.classList.contains('is-invalid')){
                    this.element.title.classList.add('is-invalid');
                }

            }else if (this.element.title.closest('.input-group').querySelector(".invalid-feedback")) {
                if(this.element.title.classList.contains('is-invalid')){
                    this.element.title.classList.remove('is-invalid');
                }
                this.element.title.closest('.input-group').querySelector(".invalid-feedback").innerHTML = "";
            }

            if(this.errors.status){
                this.element.status.closest('.input-group').querySelector(".invalid-feedback").innerHTML = this.errors.status
                if(!this.element.status.classList.contains('is-invalid')){
                    this.element.status.classList.add('is-invalid');
                }

            }else if (this.element.status.closest('.input-group').querySelector(".invalid-feedback")) {
                if(this.element.status.classList.contains('is-invalid')){
                    this.element.status.classList.remove('is-invalid');
                }
                this.element.status.closest('.input-group').querySelector(".invalid-feedback").innerHTML = "";
            }

            if(this.errors.user){
                this.element.user.closest('.input-group').querySelector(".invalid-feedback").innerHTML = this.errors.user
                if(!this.element.user.classList.contains('is-invalid')){
                    this.element.user.classList.add('is-invalid');
                }

            }else if (this.element.user.closest('.input-group').querySelector(".invalid-feedback")) {
                if(this.element.user.classList.contains('is-invalid')){
                    this.element.user.classList.remove('is-invalid');
                }
                this.element.user.closest('.input-group').querySelector(".invalid-feedback").innerHTML = "";
            }
        }



        return new Task();
    })();

})();