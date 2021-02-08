<?php

 /*
 *
 * @file plugins/generic/catalogEntryTab/reviews/controllers/grid/ReviewsGridCellProvider.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 */


import('lib.pkp.classes.controllers.grid.GridCellProvider');
import('lib.pkp.classes.linkAction.request.RedirectAction');

class ReviewsGridCellProvider extends GridCellProvider {

	/**
	 * Constructor
	 */
	function __constructr() {
		parent::__construct();
	}

	//
	// Template methods from GridCellProvider
	//

	/**
	 * Extracts variables for a given column from a data element
	 * so that they may be assigned to template before rendering.
	 * @param $row GridRow
	 * @param $column GridColumn
	 * @return array
	 */
	function getTemplateVarsFromRowColumn($row, $column) {
		$review = $row->getData();
		switch ($column->getId()) {
			case 'reviewer':
				// The action has the label
				return array('label' => $review->getReviewer());
			case 'moneyQuote':
				// The action has the label
				return array('label' => $review->getMoneyQuote());
			case 'date':
				// The action has the label
				return array('label' => $review->getDate());
			case 'link':
				// The action has the label
				return array('label' => $review->getLink());
			case 'linkName':
				// The action has the label
				return array('label' => $review->getLinkName());
		}
	}
}

?>
