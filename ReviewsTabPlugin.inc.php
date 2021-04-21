<?php

 /*
 *
 * @file plugins/generic/reviewsTab/ReviewsTabPlugin.inc.php
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @brief reviewsTabPlugin class definition
 *
 */


import('lib.pkp.classes.plugins.GenericPlugin');

define('REVIEWS_GRID_HANDLER','plugins.generic.reviewsTab.reviews.controllers.grid.ReviewsGridHandler');

class ReviewsTabPlugin extends GenericPlugin {

    function register($category, $path, $mainContextId = NULL) {

		$success = parent::register($category, $path, $mainContextId);
		
		// If the system isn't installed, or is performing an upgrade, don't
		// register hooks. This will prevent DB access attempts before the
		// schema is installed.
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
		
        if ($success) {
            if ($this->getEnabled($mainContextId)) {   			
    			if ($this->getEnabled()) {
					$this->addLocaleData();

					// register locale files for reviews grid controller classes
					$locale = AppLocale::getLocale();
					AppLocale::registerLocaleFile($locale,'plugins/generic/reviewsTab/reviews/locale/'.$locale.'/locale.po');

					// register hooks
					HookRegistry::register('LoadComponentHandler', array($this, 'loadReviewsFormHandler')); // hook for loading the form grid handler inside the Review workflow tab
					HookRegistry::register('Template::Workflow::Publication', array($this, 'addWorkflowTab'));/// hook for registering Review workflow tab
    			}
            }
			return $success;
		}
		return $success;
	}

	function addWorkflowTab($hookName, $args) {
		if ($hookName == 'Template::Workflow::Publication') {
			$templateMgr = $args[1];
			$output =& $args[2];

			$request = Application::get()->getRequest();

			// extract submission ID from current request object
			// we require this information to generate the request URL for our grid handler
			// previously this was provided as request variable
			// with OMP 3.2 this information is part of the URL path
			// we need to extract it ourselves (at least I didn't find a fuction to do it from the plugin scope)
			$request = Application::get()->getRequest();
			$matches = [];
			preg_match('#index/(\d+)/(\d+)#',$request->getRequestPath(),$matches);
			if (count($matches) == 3) {
				$submissionId = (int)$matches[1];
				//$stageId = (int)$matches[2];
			}
			
			// generate the request URL for our grid handler
			$url = $request->getDispatcher()->url(
				$request,
				ROUTE_COMPONENT,
				null,
				REVIEWS_GRID_HANDLER,
				'fetchGrid',
				null,
				[
					'submissionId' => $submissionId,
					'publicationId' => '__publicationId__',
				]
			);

			$templateMgr->assign('submissionId', $submissionId);
			$workflowData = $templateMgr->getTemplateVars('workflowData');
			$workflowData['reviewsGridUrl'] = $url;
			$templateMgr->assign('workflowData', $workflowData);
			
			$output .= $templateMgr->fetch($this->getTemplateResource('workflowTab.tpl'));

			// Permit other plugins to continue interacting with this hook
			return false;
		}
	}

	function loadReviewsFormHandler($hookName, $args) {
		$component =& $args[0];

		if ($component == REVIEWS_GRID_HANDLER) {
			// Allow the users grid handler to get the plugin object
			import($component);
			return true;
		}
		return false;
	}

	function getDisplayName() {
		return __('plugins.generic.reviewsTab.displayName');
	}

	function getDescription() {
		return __('plugins.generic.reviewsTab.description');
	}

	/**
	 * Get the name of the settings file to be installed on new context
	 * creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
	    return $this->getPluginPath() . '/settings.xml';
	}
}

?>
