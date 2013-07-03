<?php
/**
 * GridField component for uploading images in bulk
 *
 * @author colymba
 * @package GridFieldBulkEditingTools
 */
//class FindManyManyDropdown implements GridField_HTMLProvider, GridField_ActionProvider {

class FindManyManyDropdown implements GridField_HTMLProvider, GridField_ActionProvider, GridField_DataManipulator {
		
	
		
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
		Requirements::javascript(FindManyManyDropdown_PATH . '/javascript/FindManyManyDropdownForm.js');
		
		$targetFragment = 'before';
		
		if ( $gridField->getConfig()->getComponentByType('GridFieldButtonRow') )
		{
			$targetFragment = 'buttons-before-right';
		}
		
		$dataClass = ($gridField->list->dataClass); 
		
		 $dropdownOptions = new DropdownField(
            'gridfield_relationdropdown',
            'Please choose an object',
            Dataobject::get($dataClass)->map("ID", "Title")
        );
        $dropdownOptions->setEmptyString('Select:');
		
		
		
		
		$addAction = new GridField_FormAction($gridField, 'gridfield_relationadd',
			_t('GridField.LinkExisting', "Link Existing"), 'addDDto', 'addDDto');
		$addAction->setAttribute('data-icon', 'chain--plus');
		
		
		
		$fieldList = new FieldList($dropdownOptions);
		
		
		$forTemplate = new ArrayData(array());
		$forTemplate->Fields = new ArrayList();
		
		$forTemplate->Fields->push($dropdownOptions);
//		$forTemplate->Fields->push($findAction);
		$forTemplate->Fields->push($addAction);
		
		/*

		$forTemplate = new ArrayData(array(
			'Name' => 'SomeKindOfName',
			'DropDownField' => $dropdownOptions //$fieldList//
		));
		*/
		
		
		
		
		return array(
			$targetFragment => $forTemplate->renderWith('FindManyManyDropdownForm')
		);
		
		
		//return array(
		//	$targetFragment => $ArrayDataForTemplate->renderWith('FindManyManyDropdownForm')
		//);
	}
	
	
	public function getActions($gridField) {
		return array('addDDto');
	}
	
	public function handleAction(GridField $gridField, $actionName, $arguments, $data) {

		if(isset($data['gridfield_relationdropdown']) && $data['gridfield_relationdropdown']){
			$gridField->State->GridFieldAddRelation = $data['gridfield_relationdropdown'];
		}
		$gridField->State->GridFieldSearchRelation = '';
			
	}
	
	 
	
	
	public function getManipulatedData(GridField $gridField, SS_List $dataList) {
		if(!$gridField->State->GridFieldAddRelation) {
			return $dataList;
		}
		$objectID = Convert::raw2sql($gridField->State->GridFieldAddRelation);
		if($objectID) {
			$object = DataObject::get_by_id($dataList->dataclass(), $objectID);
			if($object) {
				$dataList->add($object);
			}
		}
		$gridField->State->GridFieldAddRelation = null;
		return $dataList;
	}	
	
}