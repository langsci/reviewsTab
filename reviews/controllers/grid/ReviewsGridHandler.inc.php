<?php

 /*
 *
 * @file plugins/generic/reviewsTab/reviews/controllers/grid/ReviewsGridHandler.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @brief This grid handler provides the table grid view for the reviews workflow tab.
 *
 */

import('lib.pkp.classes.controllers.grid.GridHandler');
import('plugins.generic.reviewsTab.reviews.controllers.grid.ReviewsGridRow');
import('plugins.generic.reviewsTab.reviews.controllers.grid.ReviewsGridCellProvider');
import('plugins.generic.reviewsTab.reviews.classes.ReviewsDAO');

class ReviewsGridHandler extends GridHandler {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		$this->addRoleAssignment(
			array(ROLE_ID_SUB_EDITOR, ROLE_ID_MANAGER, ROLE_ID_SITE_ADMIN),
			array('fetchGrid', 'fetchRow','addReview', 'editReview', 'updateReview', 'delete')
		);
	} 

	//
	// Implement template methods from PKPHandler
	//
	/**
	 * @see PKPHandler::authorize()
	 * @param $request PKPRequest
	 * @param $args array
	 * @param $roleAssignments array
	 */
	function authorize($request, &$args, $roleAssignments) {
		// authorization is based on the current publication ID
		import('lib.pkp.classes.security.authorization.PublicationAccessPolicy');
		$this->addPolicy(new PublicationAccessPolicy($request, $args, $roleAssignments));
		return parent::authorize($request, $args, $roleAssignments);
	}

	//
	// Overridden template methods
	//
	/**
	 * @copydoc Gridhandler::initialize()
	 */
	function initialize($request, $args = null) {
		parent::initialize($request);

		// Set the grid details.
		$this->setTitle('plugins.generic.reviewsTab.reviews.title');
		$this->setEmptyRowText('plugins.generic.reviewsTab.reviews.noneCreated');

		// Add grid-level actions
		$router = $request->getRouter();
		$actionArgs = $this->getRequestArgs();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		$this->addAction(
			new LinkAction(
				'addReview',
				new AjaxModal(
					$router->url($request, null, null, 'addReview',null,$actionArgs),
					__('plugins.generic.reviewsTab.reviews.addReview'),
					'modal_add_item'
				),
				__('plugins.generic.reviewsTab.reviews.addReview'),
				null,
				__('plugins.generic.reviewsTab.reviews.tooltip.addReview')
			)
		);

		// Columns
		$cellProvider = new ReviewsGridCellProvider();

		$this->addColumn(new GridColumn(
			'linkName',
			'plugins.generic.reviewsTab.reviews.linkName',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));

		$this->addColumn(new GridColumn(
			'reviewer',
			'plugins.generic.reviewsTab.reviews.reviewer',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));

		$this->addColumn(new GridColumn(
			'date',
			'plugins.generic.reviewsTab.reviews.date',
			null,
			'controllers/grid/gridCell.tpl', // Default null not supported in OMP 1.1
			$cellProvider
		));


	}

	/**
	 * @see GridDataProvider::getRequestArgs()
	 */
	function getRequestArgs() {
		return array_merge(
			parent::getRequestArgs(),
			array(
				'submissionId' => $this->getMonograph()->getId(),
				'publicationId' => $this->getPublication()->getId(),
			)
		);
	}

		/**
	 * Get the monograph associated with this chapter grid.
	 * @return Monograph
	 */
	function getMonograph() {
		return $this->getAuthorizedContextObject(ASSOC_TYPE_MONOGRAPH);
	}

	/**
	 * Get the publication associated with this chapter grid.
	 * @return Publication
	 */
	function getPublication() {
		return $this->getAuthorizedContextObject(ASSOC_TYPE_PUBLICATION);
	}

	//
	// Overridden methods from GridHandler
	//

	/**
	 * @copydoc Gridhandler::getRowInstance()
	 */
	function getRowInstance() {
		return new ReviewsGridRow();
	}

	/**
	 * An action to add a new user
	 * @param $args array Arguments to the request
	 * @param $request PKPRequest Request object
	 */
	function addReview($args, $request) {

		return $this->editReview($args, $request);
	}

	/**
	 * An action to edit a user
	 * @param $args array Arguments to the request
	 * @param $request PKPRequest Request object
	 * @return string Serialized JSON object
	 */
	function editReview($args, $request) {

		$reviewId = $request->getUserVar('reviewId');
		$submission = $this->getMonograph();
		$publication = $this->getPublication();
		$this->setupTemplate($request);

		// Create and present the edit form
		import('plugins.generic.reviewsTab.reviews.controllers.grid.form.ReviewForm');
		$reviewForm = new ReviewForm($submission, $publication, $reviewId);
		$reviewForm->initData();
		$json = new JSONMessage(true, $reviewForm->fetch($request));
		return $json->getString();
	}

	/**
	 * Update a user
	 * @param $args array
	 * @param $request PKPRequest
	 * @return string Serialized JSON object
	 */
	function updateReview($args, $request) {

		$reviewId = $request->getUserVar('reviewId');
		$submission = $this->getMonograph();
		$publication = $this->getPublication();
		$this->setupTemplate($request);

		// Create and populate the form
		import('plugins.generic.reviewsTab.reviews.controllers.grid.form.ReviewForm');
		$reviewForm = new ReviewForm($submission, $publication, $reviewId);
		$reviewForm->readInputData();

		// Check the results
		if ($reviewForm->validate()) {
			// Save the results
			$reviewForm->execute();
 			return DAO::getDataChangedEvent();
		} else {
			// Present any errors
			$json = new JSONMessage(true, $reviewForm->fetch($request));
			return $json->getString();
		}
	}

	function fetchGrid($args, $request) {
		return parent::fetchGrid($args, $request) ;
	}

		/**
	 * @see GridHandler::loadData
	 */
	function loadData($request, $filter) {
		$reviewsDao = new ReviewsDAO();

		return $reviewsDao->getByPublicationId($this->getPublication()->getId())
			->toAssociativeArray();
	}

	/**                               
	 * @param $args array
	 * Delete a user
	 * @param $request PKPRequest
	 * @return string Serialized JSON object
	 */
	function delete($args, $request) {

		$reviewId = $request->getUserVar('reviewId');

		$reviewsDao = new ReviewsDAO();
		$review = $reviewsDao->getById($reviewId);

		$reviewsDao->deleteObject($review);

		return DAO::getDataChangedEvent();
	}

}

?>
