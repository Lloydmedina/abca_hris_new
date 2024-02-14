$( document ).ready(function() {
   $('.selectpicker').selectpicker();
   $('.dropify').dropify();

   //CLICK BUTTON UPLOAD
   $('#inputGroupFile01').click(function(){
   	$('#picture_path').click();
   });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var filename = $("#inputGroupFile01").val();
        filename = filename.substring(filename.lastIndexOf('\\') + 1);
        reader.onload = function(e) {
            debugger;
            $('#blah').attr('src', e.target.result);
            $('#blah').hide();
            $('#blah').fadeIn(500);
            $('.custom-file-label').text(filename);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#picture_path").change(function(event) {
    readURL(this);
});
