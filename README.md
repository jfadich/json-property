# JsonField
Enable saving/reading valid JSON to an Eloquent model

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

