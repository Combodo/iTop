<?php
$localFile = __DIR__ . '/../../PEAR/Exception.php';
if (file_exists($localFile)) {
    require_once $localFile;
} else {
    require_once 'PEAR/Exception.php';
}

class PEAR_ExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException PEAR_Exception
     * @expectedExceptionMessage foo
     */
    public function testThrow()
    {
        throw new PEAR_Exception('foo');
    }

    public function testGetCauseNone()
    {
        $e = new PEAR_Exception('foo bar');
        $this->assertNull($e->getCause());
    }

    public function testGetCauseException()
    {
        $cause = new Exception('foo bar');
        $e = new PEAR_Exception('I caught an exception', $cause);
        $this->assertNotNull($e->getCause());
        $this->assertInstanceOf('Exception', $e->getCause());
        $this->assertEquals($cause, $e->getCause());
    }

    public function testGetCauseMessage()
    {
        $cause = new Exception('foo bar');
        $e = new PEAR_Exception('I caught an exception', $cause);

        $e->getCauseMessage($causes);
        $this->assertEquals('I caught an exception', $causes[0]['message']);
        $this->assertEquals('foo bar', $causes[1]['message']);
    }

    public function testGetTraceSafe()
    {
        $e = new PEAR_Exception('oops');
        $this->assertInternalType('array', $e->getTraceSafe());
    }

    public function testGetErrorClass()
    {
        $e = new PEAR_Exception('oops');
        $this->assertEquals('PEAR_ExceptionTest', $e->getErrorClass());
    }

    public function testGetErrorMethod()
    {
        $e = new PEAR_Exception('oops');
        $this->assertEquals('testGetErrorMethod', $e->getErrorMethod());
    }

    public function test__toString()
    {
        $e = new PEAR_Exception('oops');
        $this->assertInternalType('string', (string) $e);
        $this->assertContains('oops', (string) $e);
    }

    public function testToHtml()
    {
        $e = new PEAR_Exception('oops');
        $html = $e->toHtml();
        $this->assertInternalType('string', $html);
        $this->assertContains('oops', $html);
    }
}
?>
