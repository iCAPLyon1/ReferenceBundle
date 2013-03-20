$(document).ready(function () {
    var modalNewForm = null;

    var newLink = $('a.new-reference');
    var newPath = newLink.attr('href');
    newLink.attr('href', '#newReferenceModal').attr('data-toggle', 'modal');
    newLink.on('click', function (event) {
        console.log('click new reference');

        if(modalNewForm === null) {
            event.preventDefault();
            console.log('newModal not exist');
            $.get(newPath)
                .always(function () {
                    if (modalNewForm !== null) {
                        console.log('newModal remove');
                        modalNewForm.remove();
                    }
                })
                .done(function(data) {
                    console.log('newModal create');
                    $('body').append(data);
                    modalNewForm = $('#newReferenceModal');
                    console.log(modalNewForm);
                    modalNewForm.modal('show');
                })
            ;
        }
    });
});