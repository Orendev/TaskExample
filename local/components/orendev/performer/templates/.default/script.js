BX.namespace('BX.Orendev.Performer');

(function () {
    'use strict';
    BX.Orendev.Performer = (function () {

        let Performer = function (ctx) {
            this.context = ctx || {};
            this.controls = {};
            this.isMobile = BX.browser.IsMobile();
            return this;
        };

        Performer.prototype.init = function (params) {
            this.params = params.params || {};
            this.result = params.result || {};
            this.sessid = params.sessid || '';

            this.bindEvents();
        }

        Performer.prototype.bindEvents = function () {

            BX.bindDelegate(
                BX('js-table'),
                'click',
                {tagName: 'a', className: 'js-delete'},
                BX.delegate(this.deleteHandler, this)
            );
        }

        Performer.prototype.deleteHandler = function (event){
            let that = this;
            let target = event.target || event.srcElement,
                id = target.getAttribute('data-id'),
                el = document.getElementById("js-table").querySelector('tbody');

            if (id){
                let request = this.sendPost('delete', {id: id})

                request.then(function (response){
                    if(response.status === "success" && el){
                        el.removeChild(target.closest('tr'));
                        new jBox('Notice', {content: 'Исполнитель удален', color: 'green'});
                    }else{

                    }
                }, function (reason){
                    if(response.status === "error"){

                    }
                })
            }

            event.preventDefault();
        }

        Performer.prototype.sendPost = function (action, data) {
            return BX.ajax.runAction('orendev:custom.api.performer.' + action, {
                data: data
            });
        }

        return new Performer();
    })();

})();