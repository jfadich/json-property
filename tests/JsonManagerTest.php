<?php

use Jfadich\JsonProperty\JsonPropertyInterface;
use Jfadich\JsonProperty\JsonPropertyException;
use Jfadich\JsonProperty\JsonPropertyTrait;
use Jfadich\JsonProperty\JsonProperty;

class JsonManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testStringMethodCall()
    {
        $model = new TestModel();

        $model->jsonProperty = 'foo';
        $model->foo = '';

        $this->assertInstanceOf(JsonProperty::class, $model->foo());
        $this->assertInstanceOf(JsonProperty::class, $model->foo());

        $this->assertNull($model->undefinedProperty());
    }

    public function testArrayMethodCall()
    {
        $model = new TestModel();

        $model->jsonProperty = ['foo', 'bar'];
        $model->foo = null;
        $model->bar = null;

        $this->assertInstanceOf(JsonProperty::class, $model->foo());
        $this->assertInstanceOf(JsonProperty::class, $model->bar());
    }

    public function testThrowsExceptionOnInvalidOptions()
    {
        $model = new TestModel();

        $this->expectException(JsonPropertyException::class);

        $manager = new \Jfadich\JsonProperty\JsonManager($model, 123);
    }
}

class TestModel implements JsonPropertyInterface
{
    use JsonPropertyTrait;
}