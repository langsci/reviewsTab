{**
 * plugins/generic/reviewsTab/reviews/templates/editReviewForm.tpl
 *
 * Created on Sun Jan 24 2021
 *
 * Copyright (c) 2021 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 **}

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#reviewForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

{capture assign=actionUrl}{url router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.reviewsTab.reviews.controllers.grid.ReviewsGridHandler" op="updateReview"}{/capture}

<form class="pkp_form" id="reviewForm" method="post" action="{$actionUrl}">

	<input type="hidden" name="reviewId" value="{$reviewId|escape}" />
	<input type="hidden" name="submissionId" id="submissionId" value="{$submissionId|escape}" />
	<input type="hidden" name="publicationId" value="{$publicationId|escape}" />

	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="chapterFormNotification"}

	{fbvFormArea id="reviewsFormArea" class="border"}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.reviewsTab.reviews.linkName" id="linkName" value=$linkName required="true" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.reviewsTab.reviews.link" id="link" value=$link required="true" maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.reviewsTab.reviews.reviewer" id="reviewer" value=$reviewer maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.reviewsTab.reviews.lable.date" id="date" value=$date maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="textarea" label="plugins.generic.reviewsTab.reviews.moneyQuote" id="moneyQuote" value=$moneyQuote inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection class="formButtons"}
			{fbvElement type="submit" class="submitFormButton" id="saveButton" label="common.save"}
		{/fbvFormSection}

	{/fbvFormArea}

</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
