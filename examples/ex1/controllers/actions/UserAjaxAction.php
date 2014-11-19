<?php

/**
 * Class UserAjaxAction
 *
 * @author intpp <intpplus@gmail.com>
 */
class UserAjaxAction extends \intpp\yii\actions\AjaxAction
{
    public function getList()
    {
        $users = CHtml::listData(User::model()->findAll(), 'id', 'name');

        $this->setOutput('result', !empty($users));
        $this->setOutput('users', $users);
    }
}