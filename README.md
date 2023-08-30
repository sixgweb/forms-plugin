# Forms Plugin

The forms plugin utilizes [Attributize](https://octobercms.com/plugin/sixgweb-attributize) and [Conditions](https://octobercms.com/plugin/sixgweb-conditions) to create custom forms for frontend and backend use.

- Redirect after submission
- Save entries to the database
- Automatically purge saved entries
- Throttle frontend entry creation via Laravel Rate Limiting
- Send entry notifications via the RainLab Notify integration
- Events for extensibility

## Installation

### Requirements
- OctoberCMS 3.x
- [Sixgweb.Attributize](https://octobercms.com/plugin/sixgweb-attributize)
- [Sixgweb.Conditions](https://octobercms.com/plugin/sixgweb-conditions)

### Marketplace

Add the plugin to your project via the [OctoberCMS Marketplace](https://octobercms.com/plugins) and run the following command in your project root:

```
php artisan project:sync
```

### Composer 

Install via composer by running the following command in your project root:
```
composer require sixgweb/forms-plugin
```

## Forms Controller
The forms area contains all of the forms for your project and provides the following configuration:

### Details Tab
| Field | Description |
| --- | -- |
| Is Enabled | Enable or disable the form |
| Name | The name of the form |
| Description | The description of the form, shown above the form fields |
| Confirmation Message | The message displayed to the user upon successful entry.  Message does not display if form set to redirect after submission |

### Settings Tab
| Field | Description |
| ----------- | ----------- |
| Submission Redirect URL | URL to redirect to after submission. Leave blank to show form confirmation message. |
| Save Entries | Save entries to the database |
| Purge Entries | Purge entries saved to the database, after a specified number of days |
| Purge Days | Number of days to keep form entries in database |
| Throttle Entries | Limit the number of Entries Allowed in a given Time Period/Unit |
| Entries Allowed | Number of entries allowed in the given time period |
| Entry Time Period | Time period number to allow number of entries |
| Entry Time Period Unit | Unit of time used for the time period value |
| Throttle by IP Address | Throttle entries by IP address, instead of session ID. If user is logged in, throttling is done by user ID regardless of this setting |

### Fields Tab

The Attributize fields editor is available, after the form has been created.

Displays the Attributize field editor.

::: tip Automatic Conditions
Fields created under the Fields Tab will automatically have a condition created for the current form.
:::

### Conditions Tab
Displays the form Conditions editor, allowing conditions required to view the form.

## Entries Controller

The Entries Controller displays all the form submissions for your website, filterable by Form and/or Creation Date.

Once a form is selected in the Form filter, you will have the corresponding fields available in the List Setup and any fields with the setting **Is Filterable** added to the filter widget.

### Entry Editor

Fields without form conditions will alway be visible.  Fields with form conditions will appear/hide based on the selected form value.

### Entry Import/Export
Form entries can be exported directly from the list view or via the export behavior.  Entries can be imported via the import behavior.  [Read the Documentation](https://docs.octobercms.com/3.x/extend/importexport/importexport-controller.html) to learn more about OctoberCMS import/export behaviors.

#### List View Export
Set up your desired list columns and filter values and press the **Download Results** button to export the current list to a .csv file

#### Export Behavior
Press the **Export** button to view the entry exporter.  Selecting a Form value in the exporter will update the available columns.

#### Import Behavior
Press the **Import** button to view the entry importer.  Selecting a Form value in the importer will update the available columns.

## Twig Functions
The following functions are provided by the Forms plugin.

### entryFieldValuesToHTML(): string
Generates nested HTML from the model's field values.

``` twig
{% set options = {'container':'div', 'wrapper':'div', 'label':'strong', 'labelSeparator':' - ' } %}
{{ entryFieldValuesToHTML(entry, options)}}
```

#### Parameters

##### model
[Fieldable model](https://sixgweb.github.io/oc-plugin-documentation/attributize/api/behaviors.html#sixgweb-attributize-behaviors-fieldable).  In this case, the Entry model 

##### options

| Key | Description | Default |
| ----------- | ----------- | ----------- |
| container | Container HTML element. | ul |
| wrapper | Element wrapper HTML element. | li |
| label | Element wrapper for field name. | strong |
| labelSeparator | Separator between field name an value | : |