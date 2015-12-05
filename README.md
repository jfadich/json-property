# JsonField
Enhances a plain text SQL field converting it to JSON and exposing helpful methods.

## Installation
Use composer to install the package

>composer require composer require jfadich/json-field

### Requirements
- PHP >= 5.5.9

### Set Up
Simply add the `JsonFieldTrait` trait to the model and set the json_field property. The json_field should be the field on the model that contains the Json string. 

Your model is required implement a `save()` function. If you're using Laravel you're good to go.

    namespace App;

    use Jfadich\JsonField\JsonFieldTrait;

    class SampleModel
    {
        use JsonFieldTrait;

        protected $json_field = 'meta';
    }

### Usage
To get the JsonField instance call the method matching the json field name. From there you have access to all the methods below. You can also get a value by just passing in the key.

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
