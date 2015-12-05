# JsonField
Enable saving/reading valid JSON to an Eloquent package

## IOnstallation
Use composer to install the package

`composer require Jfadich/JsonField`

## Usage
### Set Up
To use JsonField simply add the trait to the model and set the json_field property. The json_field should be the field on the model that contains the Json string

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use Jfadich\JsonField\JsonFieldTrait;

    class SampleModel extends Model
    {
        use JsonFieldTrait;

        protected $json_field = 'meta';
    }

### Available Methods