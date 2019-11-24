# futape/simple-template

This library offers a very simple template engine.

The engine's only function is to resolve placeholders in the template to defined variable values or PHP constants.

## Install

```bash
composer require futape/simple-template
```

## Usage

```php
use Futape\SimpleTemplate\Template;

$template = (new Template(
    "Hello {{\$name}},\n\nthis is an usage example of futape/simple-template.\nYour installed PHP version is " .
        "{{PHP_VERSION}}."
))
    ->addVariable('name', 'Stranger');

echo $template->render();
/* Hello Stranger,

this is an usage example of futape/simple-template.
Your installed PHP version is 7.2.0. */
```

Placeholders may be escaped using a `\` in front of the second `{`.  
`{\{$foo}}` would result in `{{$foo}}` (or `{\{$foo}}` if the `unescapePlaceholders` config is disabled).

The engine can be configured, either via a constructor's second argument or by calling `setConfig()`.  
The following configuration options exist:

| Name | Type | Default | Description |
| --- | --- | --- | --- |
| unescapePlaceholders | bool | true | If `\` characters used to escape placeholders should be removed from the rendered template |
| resolveConstants | bool | true | If resolving placeholders to constants is enabled |
| discardUndefinedVariables | bool | true | If unresolvable placeholders should be removed from the rendered template |

## Testing

The library is tested by unit tests using PHP Unit.

To execute the tests, install the composer dependencies (including the dev-dependencies), switch into the `tests`
directory and run the following command:

```bash
../vendor/bin/phpunit
```
