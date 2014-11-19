<?php

/**
 * Class UserAjaxAction
 *
 * @author intpp <intpplus@gmail.com>
 */
class UserAjaxAction extends \intpp\yii\actions\AjaxAction
{
    /**
     * Get list of users
     */
    public function getList()
    {
        $users = CHtml::listData(User::model()->findAll(), 'id', 'name');

        $this->setOutput('result', !empty($users));
        $this->setOutput('users', $users);
    }

    /**
     * Return user
     */
    public function getUser()
    {
        if (!$this->hasParam('userId')) {
            $this->throwError('Param "userId" is required.');
        }

        $userId = $this->getParam('userId');

        if (($model = User::model()->findByPk($userId)) === null) {
            $this->throwError('User not found.');
        }

        $this->setOutput('result', true);
        $this->setOutput('user', $model->attributes);
    }
}