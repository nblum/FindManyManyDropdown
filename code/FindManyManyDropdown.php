<?php
/**
 * GridField component for uploading images in bulk
 *
 * @author colymba
 * @package GridFieldBulkEditingTools
 */
class FindManyManyDropdown implements GridField_HTMLProvider, GridField_URLHandler {
		
	/**
	 * component configuration
	 * 
	 * 'fileRelationName' => field name of the $has_one File/Image relation
	 * 'editableFields' => fields editable on the Model
	 * 'fieldsClassBlacklist' => field types that will be removed from the automatic form generation
	 * 'fieldsNameBlacklist' => fields that will be removed from the automatic form generation
	 * 
	 * @var array 
	 */
	 
		
	/**
	 *
	 * @param GridField $gridField
	 * @return array 
	 */
	public function getHTMLFragments($gridField) {	
		
		Requirements::css(FindManyManyDropdown_PATH . '/css/FindManyManyDropdown.css');
		
		
		$targetFragment = 'before';
		
		if ( $gridField->getConfig()->getComponentByType('GridFieldButtonRow') )
		{
			$targetFragment = 'buttons-before-right';
		}
		
		$dataClass = ($gridField->list->dataClass); 
		
		 $dropdownOptions = new DropdownField(
            $dataClass,
            'Please choose an object',
            Dataobject::get($dataClass)->map("ID", "Title")
        );
        $dropdownOptions->setEmptyString('Search:');
		
		

		$bulkUploadBtn = new ArrayData(array(
			'Name' => 'SomeKindOfName',
			'DropDownField' => $dropdownOptions
		));
		
		
		return array(
			$targetFragment => $bulkUploadBtn->renderWith('FindManyManyDropdownForm')
		);
	}
	
	 
	
	/**
	 *
	 * @param GridField $gridField
	 * @return array 
	 */
	public function getURLHandlers($gridField) {
			return array();
	}
	
	
}