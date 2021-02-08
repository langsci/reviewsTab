# ReviewsTab Plugin

OMP plugin to manage book reviews in journals. This plugin adds a new tab "Review" the OMP publication workflow stage that allows to add and manage review entries for each publication.

The information stored is displayed on the frontend article page by the BookPage Plugin.

**Attention:** This plugin modifies the file `lib/ui-library/src/components/Container/WorkflowContainerOMP.vue` to enbale loading of the ReviewsGridHandler from within the workflow context! This requires recompilation of the client side javascript code.

*Installation*

- git clone the repo into the generic plugin folder
- run the installPluginVersion script
- copy the file `components/Container/WorkflowContainerOMP.vue` into the folder `lib/ui-library/src/components/Container` (alternatively you can manually merge the contents of the file `ReviewsGridContainer_ToMerge.vue`)
- run `npm run build` from the OMP installation folder