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
    /**
     * @var Response
     */
    private $response;

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
            $methodReflection->invokeArgs($this, $this->getMethodParams($methodReflection));
        }

        $this->sendResponse();
    }

    /**
     * @param \ReflectionMethod $methodReflection
     *
     * @return array
     */
    private function getMethodParams($methodReflection)
    {
        $params = [];

        foreach ($methodReflection->getParameters() as $param) {
            $name = $param->getName();
            $defaultValue = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;

            if (!$this->hasParam($name) && !$param->isOptional() && !$param->isDefaultValueAvailable()) {
                $this->throwError(\Yii::t('yii', 'Missing required parameter "{param}".', [
                    '{param}' => $name,
                ]));
            } elseif ($this->hasParam($name)) {
                $value = $this->getParam($name);

                if ($param->isArray() && !is_array($value)) {
                    $this->throwError(\Yii::t('yii', 'Parameter "{param}" must be an array.', [
                        '{param}' => $name,
                    ]));
                } elseif (!$param->isArray() && is_array($value)) {
                    $this->throwError(\Yii::t('yii', 'Parameter "{param}" must be a string.', [
                        '{param}' => $name,
                    ]));
                }

                $params[] = $value;
            } else {
                if ($param->isArray() && !is_array($defaultValue)) {
                    $this->throwError(\Yii::t('yii', 'Parameter "{param}" must be an array.', [
                        '{param}' => $name,
                    ]));
                }

                $params[] = $defaultValue;
            }
        }

        return $params;
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
        Response::add($key, $value);
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param null $data
     * @param string $format
     */
    protected function sendResponse($data = null, $format = self::FORMAT_JSON)
    {
        Response::make($format)->send($data);
    }

    /**
     * @param $message
     * @param int $code
     * @param string $format
     */
    protected function throwError($message, $code = 1, $format = self::FORMAT_JSON)
    {
        Response::make($format)->throwError($message, $code);
    }
}