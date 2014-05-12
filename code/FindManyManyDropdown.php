<?php
/**
 * GridField component for finding ManyMany items
 *
 * create a dropdown of objects that can be found through a many_many relation
 * this saves people trying to search as they can clearly see items that can be added
 */

class FindManyManyDropdown implements GridField_HTMLProvider, GridField_ActionProvider, GridField_DataManipulator {
		

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

		
		$forTemplate = new ArrayData(array());
		$forTemplate->Fields = new ArrayList();
		
		$forTemplate->Fields->push($dropdownOptions);
		$forTemplate->Fields->push($addAction);

		return array(
			$targetFragment => $forTemplate->renderWith('FindManyManyDropdownForm')
		);
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
