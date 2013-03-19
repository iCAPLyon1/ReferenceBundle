jQuery(document).ready(function() {
    var modalDeleteForm = null;

    jQuery('a.delete-reference').each(function () {
        var deleteLink = jQuery(this);
        console.log('deleteLink: '+deleteLink);
        var deletePath = deleteLink.attr('href');
        console.log(deletePath);
        deleteLink.attr('href', '#deleteReferenceModal').attr('data-toggle', 'modal');
        deleteLink.on('click', function(event) {
            console.log('click delete reference');
            if(modalDeleteForm != null) {
                console.log('deleteModal remove');
                modalDeleteForm.remove();
            }
            event.preventDefault();
            console.log('deleteModal not exist');
            jQuery.get(deletePath)
                .done(function(data) { 
                    console.log('deleteModal create');
                    jQuery('body').append(data);
                    modalDeleteForm = jQuery('#deleteReferenceModal');
                    console.log(modalDeleteForm);
                    modalDeleteForm.modal('show');
                })
            ;
        });
    });

});