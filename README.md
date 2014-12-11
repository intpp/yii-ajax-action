Yii Ajax Action
===============

#### Usage:
1. Create class for ajax actions (ex.: SiteAjaxAction)

```php
namespace your\namespace\here;

use intpp\yii\actions\AjaxAction;

class SiteAjaxAction extends AjaxAction
{
    /**
     * @param string $name
     * @param int $age
     * @param string $gender
     * @param array $hobbies
     */
    public function getFormattedInfo($name, $age, $gender = 'male', array $hobbies)
    {
        $this->setOutput('result', true);
        $this->setOutput('text', implode(', ', [
            'Your name is ' . $name,
            'age: ' . $age,
            'gender: ' . $gender,
            'hobbies: ' . implode(', ', $hobbies)
        ]));
    }
}
```

2. Include in your controller:

```php
    public function actions()
    {
        return [
            // Other included actions
            'ajax' => 'your\namespace\here\SiteAjaxAction',
        ];
    }
```

3. Use in your JS applications:

3.1 In your view or layout file set JS variable with url to ajax action, for example:
```php
    $ajaxUrl = Yii::app()->createUrl('/site/ajax');
    Yii::app()->clientScript->registerScript('ajaxUrl', "var ajaxUrl='{$ajaxUrl}';");
```

3.2 In your JS application you use that variable for ajax requests, for example:
```javascript
    $(document).on('click', 'a#getInfo', function() {
        $.ajax({
            url: ajaxUrl,
            data: {
                method: 'getFormattedInfo', // <---- Name of a function in your AjaxAction class
                name: 'Vasya', age: 12, hobbies: ['sport', 'dev', 'sex'] // <---- Parameters for the function
            },
            dataType: 'JSON',
            success: function(response) {
                if(response.result === true) {
                    alert(response.text);
                }
            }
        });
    });
```

P.S: Other examples in examples directory =)