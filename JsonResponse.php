<?php
namespace intpp\yii\actions;

/**
 * Class JsonResponse
 *
 * @package intpp\yii\actions
 */
class JsonResponse extends Response
{

    public function prepare($data = [])
    {
        header('Content-type: application/json');

        return \CJSON::encode(array_merge(['code' => 0], $data));
    }
}
