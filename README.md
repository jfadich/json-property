[![Build Status](http://img.shields.io/travis/jfadich/json-property.svg?style=flat-square)](https://travis-ci.org/jfadich/json-property)
[![Latest Version](http://img.shields.io/packagist/v/jfadich/json-property.svg?style=flat-square)](https://packagist.org/packages/jfadich/json-property)
[![HHVM Status](http://hhvm.h4cc.de/badge/jfadich/json-property.svg?style=flat-square)](http://hhvm.h4cc.de/package/jfadich/json-property)

# JsonProperty
JsonProperty provides a simple interface for storing key/value pairs to a single column on a model.
This is useful for storing meta data or anything without a standard structure. The data is automatically serialized to a JSON when saving.

## Installation
Use composer to install the package

>composer require composer require jfadich/json-property

### Requirements
- PHP >= 5.5.9

### Configuration
1. Add the `JsonPropertyTrait` trait to the model
2. Set the `jsonProperty` property. This is the name of the method that will be called to access the JsonProperty object. You can set this to an array to enable multiple properties on a single model.
```
    namespace App;
    
    use Jfadich\JsonProperty\JsonPropertyTrait;
    use Jfadich\JsonProperty\JsonPropertyInterface;
    
    class SampleModel implements JsonPropertyInterface
    {
        use JsonPropertyTrait;
        protected $jsonProperty = 'meta';
    }
```

## Usage
Call a method on the model named after the values you set to `$jsonProperty` to access the data stored in the JSON string

    $model = new SampleModel();

    $model->meta()->set('key', 'value');
    $value = $model->meta()->get('key'); // 'value'
    $value = $model->meta('key'); // 'value'


### Available Methods

`has($key)`

Checks if there is a value for the given key

    if($model->meta()->has('keyName')) {
        // Do something
    }

`get( $key, $default = null )`

Get a value, or default if it is not present in the array. You can use the dot notation to access nested arrays.

`set($key, $value)`

Set/Update the given key/value pair.

`merge( array $values, array $allowedKeys = [] )`

Merge the given array into the saved object. This will not add keys that don't exist in original object unless the key is included in the whitelist.

`push( $key, $value )`

If the value for the given $key is an array the value will be pushed to the array.

`forget($key)`

Remove element from array. This will automatically be persisted

`all()`

Get all the elements from the array

### Examples
The property on the object will always be an up to date JSON string so you can use what ever persistance method you choose.

    $model = new SampleModel();
    
    // Use dot notation to access nested values
    $model->meta()->set('book.title', 'Cracking the Coding Interview');
    $model->meta()->set('book.author', 'Gayle Laakmann McDowell');
    
    // $model->meta
    // { "book":{"title": "Cracking the Coding Interview", "author": "Gayle Laakmann McDowell"} }
    //
    // $model->meta()->all()
    // array:3 [
    //   "book" => array:2 [
    //     "title" => "Cracking the Coding Interview"
    //     "author" => "Gayle Laakmann McDowell"
    //   ]
    // ]

### Customization
The JsonProperty object keeps the property on the model up to date with the current JSON string. If you want to automatically persist the data on update feel free to override the `saveJsonString()` method on the model.

    public function saveJsonString($property, $jsonString)
    {
        parent::saveJsonString($property, $jsonString);

        $this->saveToSQL(); // Persist to the database or any other method
    }
