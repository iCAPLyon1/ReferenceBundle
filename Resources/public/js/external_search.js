$(document).ready(function () {
    "use strict";

    var externalSearch = $('#externalSearch');
    externalSearch.removeClass('hide');
    var path = externalSearch.attr('href');

    function onReferenceTitleChange() {
        var search = $('#icap_referencebundle_editreferencetype_title').val();
        search = encodeURIComponent(search);
        var completedPath = path.replace('searchTags', search);

        externalSearch.attr('href', completedPath);
    }

    $('#icap_referencebundle_editreferencetype_title').on('change', onReferenceTitleChange);

    onReferenceTitleChange();
});