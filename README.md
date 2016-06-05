FindManyManyDropdown
====================

Addon for SilverStripe GridField to find add ManyMany items using a dropdown rather than search box

##Example
```php
<?php

class Page extends SiteTree {

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Pages', 
            $grid = new GridField('Pages', 'All pages', SiteTree::get())
        );

        // GridField configuration
        $config = $grid->getConfig();

        //
        // Remove the Search field (autocompleter and add the dropdown Field.
        //
        $config->removeComponentsByType('GridFieldAddExistingAutocompleter')
        $config->addComponent(new FindManyManyDropdown('Title'));

        return $fields;
    }
}

```
