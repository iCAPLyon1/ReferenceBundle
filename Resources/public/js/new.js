jQuery(document).ready(function() {
    var modalNewForm = null;

    var newLink = jQuery('a.new-reference');
    var newPath = newLink.attr('href');
    newLink.attr('href', '#newReferenceModal').attr('data-toggle', 'modal');
    newLink.on('click', function(event) {
        console.log('click new reference');

        if(modalNewForm == null) {
            event.preventDefault();
            console.log('newModal not exist');
            jQuery.get(newPath)
                .always(function() { 
                    if(modalNewForm != null) {
                        console.log('newModal remove');
                        modalNewForm.remove();
                    }
                })
                .done(function(data) { 
                    console.log('newModal create');
                    jQuery('body').append(data);
                    modalNewForm = jQuery('#newReferenceModal');
                    console.log(modalNewForm);
                    modalNewForm.modal('show');
                })
            ;
        }
    });
});