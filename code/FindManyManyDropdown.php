<?php
/**
 * GridField component for finding ManyMany items
 *
 * create a dropdown of objects that can be found through a many_many relation
 * this saves people trying to search as they can clearly see items that can be added
 */

class FindManyManyDropdown implements GridField_HTMLProvider, GridField_ActionProvider, GridField_DataManipulator {

	/**
	 * sortBy Field for selection options
	 * @var string
	 */
	protected $sortBy = 'ID';

	/**
	 * FindManyManyDropdown constructor.
	 * @param string $sortBy
	 */
	public function __construct($sortBy = '')
	{
		if(!empty(strval($sortBy))) {
			$this->sortBy = $sortBy;
		}
	}

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
            $this->fieldName($gridField),
            'Please choose an object',
            Dataobject::get($dataClass)->sort($this->sortBy)->map("ID", "Title")
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

		if(isset($data[$this->fieldName($gridField)]) && $data[$this->fieldName($gridField)]){
			$gridField->State->GridFieldAddRelation = $data[$this->fieldName($gridField)];
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

	/**
	 * creates a unique form field name for each dropdown
	 * @param GridField $gridField
	 * @return string
	 */
	protected function fieldName(GridField $gridField)
	{
		return 'gridfield_relationdropdown_' . $gridField->getAttribute('name');
	}
}
