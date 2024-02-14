$( document ).ready(function() {

   
   
    $('.selectpicker').selectpicker();
    $('.dropify').dropify();
    function offBeforeunload() {
        $(window).off('beforeunload');
    }

    function onBeforeunload() {
        $(window).on('beforeunload', function () {
            return confirm('Data you have entered may not be saved!');
        });
    }

   $(document).on('change', ':input, select', function(){
        onBeforeunload();
   });

   $(document).on('submit', '#add_employee', function(){
        offBeforeunload();
   });

    
});