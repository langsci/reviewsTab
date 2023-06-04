# ReviewsTab Plugin

OMP plugin to manage book reviews in journals. This plugin adds a new tab "Review" the OMP publication workflow stage that allows to add and manage review entries for each publication.

The information stored is displayed on the frontend article page by the BookPage Plugin.

**Attention:** This plugin modifies the file `lib/ui-library/src/components/Container/WorkflowContainerOMP.vue` to enbale loading of the ReviewsGridHandler from within the workflow context! This requires recompilation of the client side javascript code.

*Installation*

- git clone the repo into the generic plugin folder
- run the installPluginVersion script
- copy the file `components/Container/WorkflowContainerOMP.vue` into the folder `../../../lib/ui-library/src/components/Container` (alternatively you can manually merge the contents of the file `ReviewsGridContainer_ToMerge.vue`)
- run `npm run build` from the OMP installation folder (don't forget to clear the browser cache to force javascript reload, alternatively just delete build.js from browser cache)
- when upgrading to OMP 3.2 the database table has to be modified:
  - add column `production_id` to table `langsci_review_links`: `ALTER TABLE langsci_review_links ADD publication_id bigint(2);`
  - run `update langsci_review_links lrl inner join publications p on lrl.submission_id = p.submission_id set lrl.publication_id = p.publication_id;` to sychronize IDs

To create a new empty langsci_review_links database table execute `php tools/dbXMLtoSQL.php -schema print plugins/generic/reviewsTab/schema.xml` and run the printed SQL statement in the database.
