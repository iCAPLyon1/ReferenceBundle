jQuery(document).ready(function() {
    var externalSearch = jQuery('#externalSearch');
    externalSearch.removeClass('hide');
    var path = externalSearch.attr('href');

    function onReferenceTitleChange() {
        var search = jQuery('#icap_referencebundle_editreferencetype_title').val();
        search = encodeURIComponent(search);
        var completedPath = path.replace('searchTags', search);

        externalSearch.attr('href', completedPath);
    }

    jQuery('#icap_referencebundle_editreferencetype_title').on('change', onReferenceTitleChange);

    onReferenceTitleChange();
});