<?php

/**
 * Class UserAjaxAction
 *
 * @author intpp <intpplus@gmail.com>
 */
class UserController extends CController
{
    public function actions()
    {
        return [
            'ajax' => '{aliasToAction}.UserAjaxAction',
        ];
    }
}