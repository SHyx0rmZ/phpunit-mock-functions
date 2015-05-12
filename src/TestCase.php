<?php

namespace SHyx0rmZ\PhpUnit\MockFunction;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_Invocation_Object
     */
    private $mockObjects = [];

    /**
     * @param object $object
     * @param string $function
     * @return MockFunction
     */
    public function getMockedFunctionForObject($object, $function)
    {
        $namespace = (new \ReflectionObject($object))->getNamespaceName();
        $template = new \Text_Template(__DIR__ . DIRECTORY_SEPARATOR . 'function.tpl.dist');

        $invocationMocker = $this->getMockedFunctionInvocationMockerName($namespace, $function);

        if (!function_exists($namespace . '\\' . $function)) {
            $template->setVar(['namespace' => $namespace, 'function' => $function, 'mocker' => $invocationMocker]);
            $rendered = $template->render();
            eval($rendered);
        }

        $GLOBALS[$invocationMocker] = new MockFunction($function);
        $this->mockObjects[] = $GLOBALS[$invocationMocker];
        return $GLOBALS[$invocationMocker];
    }

    /**
     * @param string $namespace
     * @param string $function
     * @return string
     */
    private function getMockedFunctionInvocationMockerName($namespace, $function)
    {
        return '__phpunit_mock_function_' . str_replace('\\', '_', $namespace) . '_' . $function . '_invocation_mocker';
    }

    /**
     * @return void
     */
    protected function verifyMockObjects()
    {
        parent::verifyMockObjects();

        foreach ($this->mockObjects as $mockObject) {
            if ($mockObject->__phpunit_hasMatchers()) {
                $this->addToAssertionCount(1);
            }

            $mockObject->__phpunit_verify();
        }
    }

    /**
     * @return void
     */
    public function runBare()
    {
        $this->mockObjects = [];

        parent::runBare();
    }
}
