# JsonField
Enhances a plain text SQL field converting it to JSON and exposing helpful methods.

## Installation
Use composer to install the package

>composer require composer require jfadich/json-field

### Requirements
- PHP >= 5.5.9

### Configuration
1. Add the `JsonFieldTrait` trait to the model
2. Set the `jsonField` property. This is the name of the method that will be called to access the JsonField object
```
    namespace App;
    use Jfadich\JsonField\JsonFieldTrait;

    class SampleModel implements JsonFieldInterface
    {
        use JsonFieldTrait;
        protected $jsonField = 'meta';
    }`
```
### Customization
The default implementation of `JsonFieldInterface` looks for a property on the model based on the value of `jsonField` to access the raw JSON string. It also assumes there is a `$model->save()` method available to persist the changes. 

If your set up differs you simply need to override the `getJsonString()` and/or `saveJsonString($jsonString)` methods.

## Usage
To get the JsonField instance call `$model->{$jsonField}()`. From there you have access to all the methods below.

    $model = new SampleModel();

    // These are both equivalent
    $value = $model->meta()->get('getName');
    $value = $model->meta('keyName');


### Available Methods

`has($key)`

Checks if there is a value for the given key

    if($model->meta()->has('keyName')) {
        // Do something
    }

`get( $key, $default = null )`

Get a value, or default if it is not present in the array. You can use the dot notation to access nested arrays.

`set($key, $value)`

Set/Update the given key/value pair. This will automatically persist to the database.

`merge( array $values )`

Merge the given array into the save one. *Note: This will only merge values for keys that already exist in the array. To add new keys you must use set().*

`forget($key)`

Remove element from array. This will automatically be persisted

`all()`

Get all the elements from the array
