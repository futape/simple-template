<?php


use Futape\SimpleTemplate\Template;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Futape\SimpleTemplate\Template
 */
class TemplateTest extends TestCase
{
    const TEST_CONSTANT = 'foo';

    public function testRenderVariables()
    {
        $template = new Template('Foo {{$bar}}');
        $template->addVariable('bar', 'Bar');

        $this->assertEquals('Foo Bar', $template->render());
    }

    public function testRenderUndefinedVariables()
    {
        $template = new Template('Foo {{$bar}}');

        $this->assertEquals('Foo ', $template->render());

        $template->setConfig(
            [
                'discardUndefinedVariables' => false
            ]
        );

        $this->assertEquals('Foo {{$bar}}', $template->render());
    }

    public function testRenderEscapedPlaceholders()
    {
        $template = new Template('Foo {\\{$bar}}');

        $this->assertEquals('Foo {{$bar}}', $template->render());

        $template->setConfig(
            [
                'unescapePlaceholders' => false
            ]
        );

        $this->assertEquals('Foo {\\{$bar}}', $template->render());
    }

    public function testRenderConstants()
    {
        $template = new Template('Foo {{TemplateTest::TEST_CONSTANT}}');

        $this->assertEquals('Foo ' . self::TEST_CONSTANT, $template->render());
    }

    public function testRenderUndefinedConstants()
    {
        $template = new Template('Foo {{TemplateTest::UNDEFINED_CONSTANT}}');

        $this->assertEquals('Foo ', $template->render());

        $template->setConfig(
            [
                'discardUndefinedVariables' => false
            ]
        );

        $this->assertEquals('Foo {{TemplateTest::UNDEFINED_CONSTANT}}', $template->render());
    }
}
