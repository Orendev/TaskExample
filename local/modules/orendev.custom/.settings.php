<?php
return array(
    'controllers' => array(
        'value' => array(
            'namespaces' => array(
                '\\Orendev\\Custom\\Controller' => 'api',
            ),
            'defaultNamespace' => '\\Orendev\\Custom\\Controller',
        ),
        'readonly' => true,
    )
);

/**
 * <script>
 * var request = BX.ajax.runAction('orendev:custom.api.test.example', {
 * data: {
 * param1: 'hhh'
 * }
 * });
 *
 * request.then(function(response){
 * console.dir(response);
 * });
 * </script>
 */