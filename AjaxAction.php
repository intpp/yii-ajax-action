<?php
namespace intpp\yii\actions;

/**
 * Class AjaxAction
 *
 * @author intpp <intpplus@gmail.com>
 * @package intpp\yii\actions
 */
class AjaxAction extends \CAction
{
    private $request = [];
    private $response = ['result' => false];

    const FORMAT_JSON = 'json';
    const FORMAT_TEXT = 'text';

    public function run()
    {
        if (!\Yii::app()->request->isAjaxRequest) {
            $this->throwError(\Yii::t('ajaxAction', 'We love only ajax requests.'));
        }

        $this->loadParams();

        if (!$method = $this->getParam('method')) {
            $this->throwError(\Yii::t('ajaxAction', 'Please send request with method.'));
        }

        $methodReflection = null;
        try {
            $methodReflection = new \ReflectionMethod($this, $method);
        } catch (\ReflectionException $e) {
            $this->throwError(\Yii::t('ajaxAction', 'The method does not exist.'));
        }

        if (!$methodReflection->isPublic()) {
            $this->throwError(\Yii::t('ajaxAction', 'The method does not exist.'));
        } else {
            $methodReflection->invoke($this);
        }

        $this->sendResponse();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasParam($key)
    {
        return $this->getParam($key) !== null;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    public function getParam($key, $defaultValue = null)
    {
        return array_key_exists($key, $this->request) ? $this->request[$key] : $defaultValue;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->request;
    }

    /**
     * Load request data from $_POST & $_GET
     */
    private function loadParams()
    {
        $this->request = array_merge($_POST, $_GET);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setOutput($key, $value)
    {
        $this->response[$key] = $value;
    }

    /**
     * @param null $data
     * @param string $format
     */
    protected function sendResponse($data = null, $format = self::FORMAT_JSON)
    {
        if ($data === null) {
            $data = $this->response;
        }

        if ($format == self::FORMAT_JSON) {
            $data = \CJSON::encode($data);
        }

        echo $data;
        \Yii::app()->end();
    }

    /**
     * @param $message
     * @param int $code
     * @param string $format
     */
    protected function throwError($message, $code = 0, $format = self::FORMAT_JSON)
    {
        $this->sendResponse(['code' => $code, 'result' => false, 'message' => $message], $format);
    }
}