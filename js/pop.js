/*$(function(){
 $(document).on('click', '.showModalButton', function(){
 if ($('#modal').data('bs.modal').isShown) {
 $('#modal').find('#modalContent')
 .load($(this).attr('value'));

 document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
 } else {
 $('#modal').modal('show')
 .find('#modalContent')
 .load($(this).attr('value'));
 document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
 }
 });
 });*/
$(document).on('ready pjax:success', function () {
    $('.view').click(function (e) {
        e.preventDefault();
        $('#pModal').modal('show')
            .find('.modal-content')
            .load($(this).attr('href'));
    });
});
