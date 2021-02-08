<script type="text/javascript">
import WorkflowContainerOMP from './WorkflowContainerOMP.vue';

export default {
	name: 'ReviewsGridContainer',
	extends: WorkflowContainer,
	data() {
		return {
			reviewsGridUrl: '',
			isLoadingWorkType: false
		};
	},
	computed: {
	},
	methods: {
		/**
		 * Load/reload the reviews grid
		 *
		 * @param Object publication Load chapters for this publication
		 */
		loadReviewsGrid(publication) {
			if (!this.$refs.reviews) {
				return;
			}
			const $reviewsEl = $(this.$refs.reviews);
			const sourceUrl = this.reviewsGridUrl.replace(
				'__publicationId__',
				publication.id
			);
			if (!$.pkp.classes.Handler.hasHandler($reviewsEl)) {
				$reviewsEl.pkpHandler('$.pkp.controllers.UrlInDivHandler', {
					sourceUrl: sourceUrl,
					refreshOn: 'form-success'
				});
			} else {
				const reviewsHandler = $.pkp.classes.Handler.getHandler($reviewsEl);
				reviewsHandler.setSourceUrl(sourceUrl);
				reviewsHandler.reload();
			}
		},
	},
	watch: {
		workingPublication(newVal, oldVal) {
			if (newVal === oldVal) {
				return;
			}
			this.loadReviewGrid(newVal);
		}
	},
	mounted() {
		this.loadReviewsGrid(this.workingPublication);
	}
};
</script>

<style lang="less">
@import '../../styles/_import';

// Integrate the grids in the publication tab
#reviews-grid {
	padding-top: @double;
}
</style>
