<?php

 /*
 *
 * @file plugins/generic/reviewsTab/classes/ReviewsDAO.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 */


import('lib.pkp.classes.db.DAO');
import('plugins.generic.reviewsTab.reviews.classes.Review');

class ReviewsDAO extends DAO {

	function __construct() {
		parent::__construct();
	}

	function getById($reviewId) {

		$result = $this->retrieve(
			'SELECT * FROM langsci_review_links WHERE review_id ='.$reviewId
		);

		$returner = null;
		if ($result->RecordCount() != 0) {
			$returner = $this->_fromRow($result->GetRowAssoc(false));
		}
		$result->Close();
		return $returner;
	}

	function getBySubmissionId($submissionId) {
		$result = $this->retrieveRange(
			'SELECT * FROM langsci_review_links WHERE submission_id = ? ORDER BY reviewer',
			$submissionId
		);

		return new DAOResultFactory($result, $this, '_fromRow');
	}

	function getByPublicationId($publicationId) {
		$result = $this->retrieveRange(
			'SELECT * FROM langsci_review_links WHERE publication_id = ? ORDER BY reviewer',
			$publicationId
		);

		return new DAOResultFactory($result, $this, '_fromRow');
	}

	function insertObject($review) {

		$this->update(
			'INSERT INTO langsci_review_links (submission_id, publication_id,  reviewer, money_quote, date,link,link_name)
			VALUES (?,?,?,?,?,?,?)',
			array(
				$review->getSubmissionId(),
				$review->getPublicationId(),
				$review->getReviewer(),
				$review->getMoneyQuote(),
				$review->getDate(),
				$review->getLink(),
				$review->getLinkName()
			)
		);

		$review->setId($this->getInsertId());

		return $review->getId();
	}

	function updateObject($review) {

		$this->update(
			'UPDATE	langsci_review_links
			SET submission_id = ?,
				publication_id = ?,
				reviewer = ?,
				money_quote = ?,
				date = ?,
				link = ?,
				link_name = ?
			WHERE	review_id = ?',
			array(
				(int) $review->getSubmissionId(),
				(int) $review->getPublicationId(),
				$review->getReviewer(),
				$review->getMoneyQuote(),
				$review->getDate(),
				$review->getLink(),
				$review->getLinkName(),
				(int) $review->getId()
			)
		);

	}

	function deleteById($reviewId) {
		$this->update(
			'DELETE FROM langsci_review_links WHERE review_id = ?',
			(int) $reviewId
		);
	}

	function deleteObject($review) {
		$this->deleteById($review->getId());
	}

	function newDataObject() {
		return new Review();
	}

	function _fromRow($row) {

		$review = $this->newDataObject();
		$review->setId($row['review_id']);
		$review->setSubmissionId($row['submission_id']);
		$review->setPublicationId($row['publication_id']);
		$review->setReviewer($row['reviewer']);
		$review->setMoneyQuote($row['money_quote']);
		$review->setDate($row['date']);
		$review->setLink($row['link']);
		$review->setLinkName($row['link_name']);

		return $review;
	}

	function getInsertId() {
		return $this->_getInsertId('langsci_reviews', 'review_id');
	}

}

?>
