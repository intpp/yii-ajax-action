<?php
namespace intpp\yii\actions;

/**
 * Class Response
 *
 * @package intpp\yii\actions
 */
abstract class Response
{
    const FORMAT_JSON = 'json';
    const FORMAT_TEXT = 'text';

    protected static $data = [];

    /**
     * @param string $type
     *
     * @return Response
     */
    public static function make($type = self::FORMAT_JSON)
    {
        switch ($type) {
            case self::FORMAT_TEXT:
                $response = new TextResponse();
                break;
            default:
                $response = new JsonResponse();
        }

        return $response;
    }

    public function send($data = [])
    {
        if (is_scalar($data)) {
            $data = [$data];
        }
        $data = array_merge(self::$data, $data);

        echo $this->prepare($data);
        \Yii::app()->end();

    }

    abstract public function prepare($data = []);

    public static function add($data, $key = null)
    {
        self::$data[$key] = $data;
    }

    public function throwError($message, $code = 1)
    {
        $this->send(['code' => $code, 'result' => false, 'message' => $message]);
    }
}