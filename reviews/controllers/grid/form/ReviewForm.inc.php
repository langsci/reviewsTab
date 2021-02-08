<?php

 /*
 *
 * @file plugins/generic/catalogEntryTab/reviews/controllers/grid/form/ReviewForm.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @brief Class to provide the form to edit review entries.
 *
 */

import('lib.pkp.classes.form.Form');

class ReviewForm extends Form {

	var $submissionId;

	private $reviewId;

	private $_plugin;

	/**
	 * Constructor
	 */
	function __construct($monograph, $publication, $reviewId = null) {

		// we have an issue here:
		// this form is not called from a plugin context, so 
		// templateMgr only searches templates and lib/pkp/templates folders for tpl files
		// we add a template resource ourselves

		$_plugin = PluginRegistry::getPlugin('generic','reviewstabplugin');
		$templatePath = $_plugin->getPluginPath().'/reviews/templates/';
		$pluginTemplateResource = new PKPTemplateResource($templatePath);
		$templateMgr = TemplateManager::getManager();
		$templateMgr->registerResource($_plugin->getTemplateResource(null, false), $pluginTemplateResource);

		parent::__construct($_plugin->getTemplateResource(false).'editReviewForm.tpl');

		$this->submissionId = $monograph->getId();
		$this->publicationId = $publication->getId();
		$this->reviewId = $reviewId;

		$this->setMonograph($monograph);
		$this->setPublication($publication);
		$this->setDefaultFormLocale($publication->getData('locale'));

		// Add form checks
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidator($this,'linkName','required', 'plugins.generic.reviewsTab.reviews.reviewerRequired'));

		$this->addCheck(new FormValidatorCustom(
				$this, 'date', 'optional', 'plugins.generic.reviewsTab.reviews.dateFormat',
				function($date,$form) {
						if (ctype_digit($date)&&strlen($date)==8) {
							return true;
						} else {
							return false;}
					},
				array(&$this)
		));

		$this->addCheck(new FormValidatorCustom(
				$this, 'link', 'required', 'plugins.generic.reviewsTab.reviews.urlFormat',
				function($link,$form) {
                    if (!filter_var($link, FILTER_VALIDATE_URL) === false) {
                        return true;
                    } else {
                        return false;
                    }
                },
				array(&$this)
		));

	}

	/**
	 * Initialize form data
	 */
	function initData() {

		if ($this->reviewId) {
			$reviewsDao = new ReviewsDAO();
			$review = $reviewsDao->getById($this->reviewId);

			$this->setData('submissionId', $review->getSubmissionId());
			$this->setData('publicationId', $review->getPublicationId());
			$this->setData('reviewer', $review->getReviewer());
			$this->setData('moneyQuote', $review->getMoneyQuote());
			$this->setData('date', $review->getDate());
			$this->setData('link', $review->getLink());
			$this->setData('linkName', $review->getLinkName());
		}
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('reviewer','moneyQuote','date','link','linkName','submissionId','publicationId'));
	}

	/**
	 * @see Form::fetch
	 */
	function fetch($request, $template = NULL, $display = false) {

		$templateMgr = TemplateManager::getManager();
		$templateMgr->assign('reviewId', $this->reviewId);
		$templateMgr->assign('submissionId', $this->submissionId);
		$templateMgr->assign('publicationId', $this->publicationId);

		return parent::fetch($request);
	}

	/**
	 * Save form values into the database
	 */
	function execute(...$functionArgs) {

		$reviewsDao = new ReviewsDAO();
		if ($this->reviewId) {
			// Load and update an existing review
			$review = $reviewsDao->getById($this->reviewId);
		} else {
			// Create a new review
			$review = $reviewsDao->newDataObject();
		}
		$review->setSubmissionId($this->getData('submissionId'));
		$review->setPublicationId($this->getData('publicationId'));
		$review->setReviewer($this->getData('reviewer'));
		$review->setMoneyQuote($this->getData('moneyQuote'));
		$review->setDate($this->getData('date'));
		$review->setLink($this->getData('link'));
		$review->setLinkName($this->getData('linkName'));

		if ($this->reviewId) {
			$reviewsDao->updateObject($review);
		} else {
			$reviewsDao->insertObject($review);
		}

	}

	//
	// Getters/Setters
	//
	/**
	 * Get the monograph associated with this chapter grid.
	 * @return Monograph
	 */
	function getMonograph() {
		return $this->_monograph;
	}

	/**
	 * Set the monograph associated with this chapter grid.
	 * @param $monograph Monograph
	 */
	function setMonograph($monograph) {
		$this->_monograph = $monograph;
	}

	/**
	 * Get the publication associated with this chapter grid.
	 * @return Publication
	 */
	function getPublication() {
		return $this->_publication;
	}

	/**
	 * Set the publication associated with this chapter grid.
	 * @param $publication Publication
	 */
	function setPublication($publication) {
		$this->_publication = $publication;
	}
}

?>
