<?php

 /*
 *
 * @file plugins/generic/catalogEntryTab/reviews/controllers/grid/ReviewsGridRow.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 */

import('lib.pkp.classes.controllers.grid.GridRow');

class ReviewsGridRow extends GridRow {

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	//
	// Overridden template methods
	//
	/**
	 * @copydoc GridRow::initialize()
	 */
	function initialize($request, $template = null) {

		parent::initialize($request, $template);

		$reviewId = $this->getId();
		if (!empty($reviewId)) {
			$router = $request->getRouter();

			// Create the "edit user" action
			import('lib.pkp.classes.linkAction.request.AjaxModal');
			$this->addAction(
				new LinkAction(
					'editReview',
					new AjaxModal(
						$router->url($request, null, null, 'editReview', null, array_merge(['reviewId' => $reviewId],$this->_requestArgs)),
						__('grid.action.edit'),
						'modal_edit',
						true),
					__('grid.action.edit'),
					null,
					__('common.edit')
				)
			);

			// Create the "delete user" action
			import('lib.pkp.classes.linkAction.request.RemoteActionConfirmationModal');
			$this->addAction(
				new LinkAction(
					'delete',
					new RemoteActionConfirmationModal(
						$request->getSession(),
						__('common.confirmDelete'),
						__('common.delete'),
						$router->url($request, null, null, 'delete', null, array_merge(['reviewId' => $reviewId],$this->_requestArgs)), 'modal_delete'
					),
					__('common.delete'),
					'delete'
				)
			);
		}
	}
}

?>
