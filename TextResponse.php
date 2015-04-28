<?php
namespace intpp\yii\actions;

/**
 * Class TextResponse
 *
 * @package intpp\yii\actions
 */
class TextResponse extends Response
{
    public function prepare($data = [])
    {
        return implode('', $data);
    }

    public function throwError($message, $code = 1)
    {
        $this->send([$message]);
    }
}