<?php

use Jfadich\JsonProperty\JsonPropertyException;
use Jfadich\JsonProperty\JsonPropertyInterface;

class JsonPropertyTest extends \PHPUnit\Framework\TestCase
{
    public function testDecodeJson()
    {
        $model = new TestModel();

        $model->jsonProperty = 'foo';
        $model->foo = '{"bar":"baz"}';

        $this->assertEquals(json_decode('{"bar":"baz"}'), $model->foo()->all());

        return $model;
    }

    /**
     * @depends testDecodeJson
     */
    public function testGettingProperties(JsonPropertyInterface $model)
    {
        // Use get method directly
        $this->assertEquals('baz', $model->foo()->get('bar'));

        // Use get shortcut
        $this->assertEquals('baz', $model->foo('bar'));

        // Get default for missing key
        $this->assertEquals('buzz', $model->foo()->get('fizz', 'buzz'));

        return $model;
    }

    /**
     * @depends testGettingProperties
     */
    public function testSettingProperties(JsonPropertyInterface $model)
    {
        $model->foo()->set('new', ['key' => 'value']);

        $this->assertEquals('value', $model->foo()->get('new.key'));

        $model->foo()->set('new.key', 'updated');

        $this->assertEquals('updated', $model->foo('new.key'));

        $model->foo()->set('nullValue', null);

        $this->assertEquals(false, $model->foo()->has('nullValue'));

        return $model;
    }

    /**
     * @depends testSettingProperties
     */
    public function testMerging(JsonPropertyInterface $model)
    {
        $original = $model->foo()->all();

        $model->foo()->merge(['evilKey' => 'should be ignored']);

        $this->assertEquals($original, $model->foo()->all());

        $model->foo()->merge(['niceKey' => 'should be added'], ['niceKey']);

        $this->assertEquals('should be added', $model->foo('niceKey'));

        $model->foo()->forget('niceKey');

        $this->assertEquals($original, $model->foo()->all());

        return $model;
    }

    public function testArrayPush()
    {
        $model = new TestModel();

        $model->jsonProperty = 'foo';
        $model->foo = '{"array_test": ["item_1"]}';

        $model->foo()->push('array_test', 'item_2');

        $this->assertEquals(['item_1', 'item_2'], $model->foo('array_test'));
    }

    public function testArrayPushFail()
    {
        $model = new TestModel();

        $model->jsonProperty = 'foo';
        $model->foo = '{"array_test": "string value"}';

        $this->expectException(JsonPropertyException::class);

        $model->foo()->push('array_test', 'item_2');
    }

    /**
     * @depends testMerging
     */
    public function testJsonEncoding(JsonPropertyInterface $model)
    {
        $this->assertEquals('{"bar":"baz","new":{"key":"updated"},"nullValue":null}', $model->foo);
    }
}