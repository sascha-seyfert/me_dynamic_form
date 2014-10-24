<?php

namespace MoveElevator\MeDynamicForm\Domain\Model;

use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class SendForm
 *
 * @package MoveElevator\MeDynamicForm\Domain\Model
 */
abstract class AbstractBaseModel extends AbstractEntity {
	/**
	 * @var int
	 */
	protected $creationDate = 0;

	/**
	 * Initialize model and set creation date im empty
	 */
	public function initializeObject() {
		if ((int)$this->creationDate === 0) {
			$this->setCreationDate(time());
		}
	}

	/**
	 * @param int $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @return int
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

}