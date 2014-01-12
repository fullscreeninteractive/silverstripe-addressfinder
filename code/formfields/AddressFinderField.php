<?php

/**
 * A wrapper for the AddressFinder API.
 *
 * Standard layout is a simple text field with the required javascript events,
 * but as per best practices (http://addressfinder.co.nz/docs/best_practices) 
 * this FormField also provides fallback fields in case the user cannot find 
 * their address
 *
 * @package addressfinder
 */

class AddressFinderField extends TextField {

	/**
	 * @var FieldList
	 */
	private $manualFields;

	/**
	 * @var TextField
	 */
	private $addressField;

	/**
	 * @var HiddenField
	 */
	private $manualToggle;

	/**
	 * @param string $name
	 * @param string $title
	 * @param mixed $value
	 */
	public function __construct($name, $title = null, $value = null) {
		$this->addressField = new TextField("{$name}[Address]", $title);
		$this->manualToggle = new HiddenField("{$name}[ManualAddress]");
		$this->manualFields = new FieldList();
		

		for($i = 1; $i < 4; $i++) {
			$this->manualFields->push(new TextField(
				"{$name}[PostalLine{$i}]",
				_t("AddressFinderField.POSTALLINE{$i}", "Postal Line {$i}")
			));
		}

		for($i = 4; $i < 7; $i++) {
			$this->manualFields->push(new HiddenField(
				"{$name}[PostalLine{$i}]"
			));
		}

		$this->manualFields->push(new HiddenField("{$name}[Longitude]"));
		$this->manualFields->push(new HiddenField("{$name}[Latitude]"));

		$this->manualFields->push(new TextField(
			"{$name}[Suburb]", 
			_t("AddressFinderField.SUBURB", "Suburb")
		));

		$this->manualFields->push(new TextField(
			"{$name}[City]", 
			_t("AddressFinderField.CITY", "City")
		));

		$this->manualFields->push(new NumericField(
			"{$name}[Postcode]", 
			_t("AddressFinderField.POSTCODE", "Postcode")
		));

		parent::__construct($name, $title, $value);
	}

	/**
	 * @param bool $bool
	 *
	 * @return void
	 */
	public function setReadonly($bool) {
		parent::setReadonly($bool);

		foreach($this->manualFields as $field) {
			$field->setReadonly($bool);
		}
	}

	/**
	 * @param string $message
	 * @param string $messageType
	 */
	public function setError($message, $messageType) {
		// show the manual fields
		$this->manualToggle->setValue('1');

		$name = $this->getName();
		$field = $this->manualFields->dataFieldByName("{$name}[{$messageType}]");

		if($field) { 
			$field->setError($message, 'validation');
		}
	}

	/**
	 * @return void
	 */
	public function performReadonlyTransformation() {
		parent::performReadonlyTransformation();

		$readonly = new FieldList();

		foreach($this->manualFields as $field) {
			$readonly->push($field->performReadonlyTransformation());
		}

		$this->manualFields = $readonly;
	}

	/**
	 * @param bool $bool
	 *
	 * @return void
	 */
	public function setDisabled($bool) {
		parent::setDisabled($bool);

		foreach($this->manualFields as $field) {
			$field->setDisabled($bool);
		}
	}

	/**
	 * @param array $properties
	 *
	 * @return string
	 */
	public function FieldHolder($properties = array()) {
		Requirements::javascript('http://www.addressfinder.co.nz/assets/v2/widget.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript('addressfinder/javascript/addressfinder.js');

		return parent::FieldHolder(array(
			'ApiKey' => Config::inst()->get('AddressFinder', 'api_key'),
			'ManualAddressFields' => $this->getManualFields(),
			'AddressField' => $this->addressField->Field(),
			'ManualToggleField' => $this->manualToggle
		));
	}
	
	/**
	 * @return FieldList
	 */
	public function getManualFields() {
		return $this->manualFields;
	}

	/**
	 * @param array $value
	 * @param DataObjectInterface $record
	 */
	public function setValue($value, $record = null) {
		if($record && is_object($record)) {
			$this->addressField->setValue($record->{$this->getName()});

			foreach($this->getManualFields() as $field) {
				$name = $this->getNestedFieldName($field);

				$field->setValue($record->{$name});
			}
		} else if(is_array($value)) {
			if(isset($value['Address'])) {
				$this->addressField->setValue($value['Address']);
			}

			foreach($this->getManualFields() as $field) {
				$nested = $this->getNestedFieldName($field);

				if(isset($value[$nested])) {
					$field->setValue($value[$nested]);
				}
			}
		}
	}
	
	/**
	 * @param DataObjectInterface
	 */
	public function saveInto(DataObjectInterface $object) {
		$object->{$this->getName()} = $this->addressField->Value();

		foreach($this->getManualFields() as $field) {
			$fieldName = $this->getNestedFieldName($field);

			$object->{$fieldName} = $field->Value();
		}
	}

	/**
	 * Returns the actual name of a child field without the prefix of this 
	 * field.
	 *
	 * @param FormField $field
	 *
	 * @return string
	 */
	protected function getNestedFieldName($field) {
		return substr($field->getName(), strlen($this->getName()) + 1, -1);
	}
	
	/**
	 * @param string $name
	 *
	 * @return AddressFinderField
	 */
	public function setName($name) {
		parent::setName($name);

		$this->addressField->setName("{$name}[Address]");

		foreach($this->getManualFields() as $field) {
			$nested = $this->getNestedFieldName($field);

			$field->setName("{$name}[{$nested}]");
		}

		return $this;
	}

	/**
	 * If this field is required then we require at least the first postal line
	 * along with the town and postcode. Either this has been manually filled
	 * in or, automatically filled in by 
	 *
	 * @param Validator $validator
	 *
	 * @return bool
	 */
	public function validate($validator) {
		$name = $this->getName();

		if($validator->fieldIsRequired($name)) {
			// remove this as a required field as we're doing the checking here.
			$validator->removeRequiredField($name);

			$fields = $this->getManualFields();
			$postal = $fields->dataFieldByName("{$name}[PostalLine1]");

			if(!$postal->Value()) {
				$validator->validationError(
					$name, 
					_t("AddressFinderField.ENTERAVALIDADDRESS", "Please enter a valid address."),
					'PostalLine1', 
					false
				);
			
				return false;
			}

			$city = $fields->dataFieldByName("{$name}[City]");

			if(!$city->Value()) {
				$validator->validationError(
					$name, 
					_t("AddressFinderField.ENTERAVALIDCITY", "Please enter a valid city."),
					"City", 
					false
				);
			
				return false;
			}

			$postcode = $fields->dataFieldByName("{$name}[Postcode]");

			if(!$postcode->Value()) {
				$validator->validationError(
					$name, 
					_t("AddressFinderField.ENTERAVALIDPOSTCODE", "Please enter a valid postcode."),
					"Postcode", 
					false
				);
			
				return false;
			}
		}

		return true;
	}
}