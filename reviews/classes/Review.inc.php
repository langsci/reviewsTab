<?php

 /*
 *
 * @file plugins/generic/reviewsTab/reviews/classes/Review.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @class Review
 * Data object representing an unregistered user. 
 *
 */

class Review extends DataObject {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	//
	// Get/set methods
	//

	function getSubmissionId(){
		return $this->getData('submissionId');
	}

	function getPublicationId(){
		return $this->getData('publicationId');
	}

	function setSubmissionId($submissionId) {
		return $this->setData('submissionId', $submissionId);
	}

	function setPublicationId($publicationId) {
		return $this->setData('publicationId', $publicationId);
	}

	function setReviewer($reviewer) {
		return $this->setData('reviewer', $reviewer);
	}

	function getReviewer() {
		return $this->getData('reviewer');
	}



	function setDate($date) {
		return $this->setData('date', $date);
	}

	function getDate() {
		return $this->getData('date');
	}



	function setMoneyQuote($moneyQuote) {
		return $this->setData('moneyQuote', $moneyQuote);
	}

	function getMoneyQuote() {
		return $this->getData('moneyQuote');
	}


	function setLink($link) {
		return $this->setData('link', $link);
	}

	function getLink() {
		return $this->getData('link');
	}


	function setLinkName($linkName) {
		return $this->setData('linkName', $linkName);
	}

	function getLinkName() {
		return $this->getData('linkName');
	}

}

?>
