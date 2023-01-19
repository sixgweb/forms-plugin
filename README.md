# Form Plugin

The forms plugin utilizes [Attributize](https://octobercms.com/plugin/sixgweb-attributize) and [Conditions](https://octobercms.com/plugin/sixgweb-conditions) to create custom forms for various purposes.

## Forms
The forms area contains all of the forms for your project and provides the following configuration:

### Details Tab

#### Name
The name of the form.

#### Description
The description of the form, shown above the form fields.

#### Confirmation Message
The message displayed to the user upon successful entry.

### Settings Tab

#### Is Enabled
Enable or disable the form

#### Save Entries
Save entries to the database.

#### Purge Entries
Purge entries saved to the database, after a specified number of days.

#### Days to Keep Entries
Number of days to keep entries, if Purge Entries is enabled.

#### Throttle Entries
Whether visitors should have to wait before submitting the form again.

#### Throttle Timeout
Number of seconds the visitor must wait, if throttling is enabled.

#### Throttle Threshold
Number of submissions allowed before the throttle timeout is enforced (default 1).

### Fields Tab
Displays the Attributize field editor, conditioned to the current form

Fields created under the Fields Tab will automatically have a condition created for the current form.

### Conditions Tab
Displays the form Conditions editor, allowing conditions required to view the form.