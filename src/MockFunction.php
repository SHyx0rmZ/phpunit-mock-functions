<?php

namespace SHyx0rmZ\PhpUnit\MockFunction;

use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Framework_MockObject_Builder_InvocationMocker;
use PHPUnit_Framework_MockObject_InvocationMocker;
use PHPUnit_Framework_MockObject_Matcher_Invocation;

class MockFunction implements \PHPUnit_Framework_MockObject_MockObject
{
    private $phpunitInvocationMocker;
    private $phpunitOriginalObject;
    private $functionName;

    public function __construct($functionName)
    {
        $this->functionName = $functionName;
    }

    /**
     * Registers a new expectation in the mock object and returns the match
     * object which can be infused with further details.
     *
     * @param  PHPUnit_Framework_MockObject_Matcher_Invocation $matcher
     * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    public function expects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        $invocationMocker = $this->__phpunit_getInvocationMocker()->expects($matcher);
        $invocationMocker->method($this->functionName);

        return $invocationMocker;
    }

    /**
     * @return PHPUnit_Framework_MockObject_InvocationMocker
     * @since  Method available since Release 2.0.0
     */
    public function __phpunit_setOriginalObject($originalObject)
    {
        $this->phpunitOriginalObject = $originalObject;
    }

    /**
     * @return PHPUnit_Framework_MockObject_InvocationMocker
     */
    public function __phpunit_getInvocationMocker()
    {
        if ($this->phpunitInvocationMocker === null) {
            $this->phpunitInvocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return $this->phpunitInvocationMocker;
    }

    /**
     * Verifies that the current expectation is valid. If everything is OK the
     * code should just return, if not it must throw an exception.
     *
     * @throws PHPUnit_Framework_ExpectationFailedException
     */
    public function __phpunit_verify()
    {
        $this->__phpunit_getInvocationMocker()->verify();
        $this->phpunitInvocationMocker = null;
    }


    public function __phpunit_hasMatchers()
    {
        return $this->__phpunit_getInvocationMocker()->hasMatchers();
    }
}
