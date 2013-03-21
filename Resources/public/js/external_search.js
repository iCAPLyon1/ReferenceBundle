$(document).ready(function () {
    'use strict';

    if ($('a#externalSearch').length) {
        var externalSearch = $('a#externalSearch');
        externalSearch.removeClass('hide');
        var path = externalSearch.attr('href');
    }

    function onReferenceTitleChange() {
        var search = $('#icap_referencebundle_editreferencetype_title').val();
        search = encodeURIComponent(search);
        var completedPath = path.replace('searchTags', search);
        externalSearch.attr('href', completedPath);
    }

    if ($('a#externalSearch').length) {
        $('#icap_referencebundle_editreferencetype_title').on('change', onReferenceTitleChange);
        onReferenceTitleChange();
    }
});