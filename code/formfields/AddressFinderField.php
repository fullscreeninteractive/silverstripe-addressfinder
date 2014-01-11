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
	 * @param string $name
	 * @param string $title
	 * @param mixed $value
	 */
	public function __construct($name, $title = null, $value = null) {
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
	public function Field($properties = array()) {
		Requirements::javascript('http://www.addressfinder.co.nz/assets/v2/widget.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript('addressfinder/javascript/addressfinder.js');
		
		return parent::Field();
	}

	/**
	 * @param array $properties
	 *
	 * @return string
	 */
	public function FieldHolder($properties = array()) {
		return parent::FieldHolder(array(
			'ApiKey' => Config::inst()->get('AddressFinder', 'api_key'),
			'ManualAddressFields' => $this->getManualFields(),
			'AllowManualAddress'
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

	}

	/**
	 * @return void
	 */
	public function Value() {

	}
	
	/**
	 * @param DataObjectInterface
	 */
	public function saveInto(DataObjectInterface $object) {

	}
	
	/**
	 * @param Validator $validator
	 *
	 * @return bool
	 */
	public function validate($validator) {
		if($validator->fieldIsRequired($this->Name())) {
			/*
				$validator->validationError(
					$this->Name(), 
					"Please enter a valid New Zealand address.",
					"validation", 
					false
				);
			
				return false;
			*/
		}
		
		return true;
	}
}