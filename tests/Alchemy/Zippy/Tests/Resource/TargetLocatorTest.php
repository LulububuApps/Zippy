<?php

namespace Alchemy\Zippy\Tests\Resource;

use Alchemy\Zippy\Tests\TestCase;
use Alchemy\Zippy\Resource\TargetLocator;

class TargetLocatorTest extends TestCase
{
    /**
     * @dataProvider provideLocationData
     */
    public function testLocate($expected, $context, $resource)
    {
        $locator = new TargetLocator();
        $this->assertEquals($expected, $locator->locate($context, $resource));
    }

    /**
     * @expectedException Alchemy\Zippy\Exception\InvalidArgumentException
     */
    public function testLocateThatShouldFail()
    {
        $locator = new TargetLocator();
        $locator->locate("some-context", array());
    }

    /**
     * @expectedException Alchemy\Zippy\Exception\InvalidResourceException
     */
    public function testLocateThatShouldFail2()
    {
        $locator = new TargetLocator();
        $locator->locate("some-context", fopen('file://', 'rb'));
    }

    public function provideLocationData()
    {
        $updir = dirname(__DIR__) . '/';

        return array(
            array(basename(__FILE__), __DIR__, __FILE__),
            array(basename(__FILE__), __DIR__, 'file://' . __FILE__),
            array(basename(__FILE__), __DIR__, fopen(__FILE__, 'rb')),
            array('temporary-file.jpg', __DIR__, '/tmp/temporary-file.jpg'),
            array('temporary-file.jpg', __DIR__, '/tmp/temporary-file.jpg'),
            array(str_replace($updir, '', __FILE__), $updir, __FILE__),
            array(basename(__FILE__), $updir, fopen(__FILE__, 'rb')),
            array('plus-badge.png', $updir, 'http://www.google.com/+/business/images/plus-badge.png'),
            array('plus-badge.png', $updir, fopen('http://www.google.com/+/business/images/plus-badge.png', 'rb')),
            array('hedgehog.png', $updir, 'ftp://192.168.1.1/images/hedgehog.png'),
        );
    }
}
