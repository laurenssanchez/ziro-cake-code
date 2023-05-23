$('input[type="checkbox"]').on('change', function(e){
   if(e.target.checked){
     $('#autorizacion-modal').modal();
   }
});

FORMULARIO_VALIDO = false;
$("#CustomerRegisterStepOneForm").submit(function(event) {
    event.preventDefault();
});

if (!$("#CustomerCrediventasForm").length) {    

    $('#CustomerRegisterStepOneForm,#CustomerNormalRequestForm').parsley().on('form:validate', function (formInstance) {
        console.log(formInstance.isValid());
        if(formInstance.isValid() != false){
            FORMULARIO_VALIDO = true;
        }else{
            FORMULARIO_VALIDO = false;
        }
    });
}

$("#CustomerRegisterStepOneForm").submit(function(event) {
    event.preventDefault();

    if(!FORMULARIO_VALIDO){
        return false;
    }
    var document_file_up    = $("#CustomerDocumentFileUp2").val();
    var document_file_down  = $("#CustomerDocumentFileDown2").val();
    var image_file          = $("#CustomerImageFile2").val();

    var CustomerCode            = $("#CustomerCode").val();
    var CustomerEmail           = $("#CustomerEmail").val();
    var CustomerIdentification  = $("#CustomerIdentification").val();

    if( ( !VIDEO_DATA || document_file_up == "" || document_file_down == "" || image_file == "" ) && !$("#CustomerId").length ){
        showMessage("Todos los campos son requeridos y se deben tomar las fotos",true);
        return false;
    }else{
        $("#preloader").show();
        var form     = $('#CustomerRegisterStepOneForm')[0];
        var formData = new FormData(form);

        if (!$("#CustomerId").length) {
            formData.append('data[Customer][document_file_up]',b64toBlob(document_file_up));
            formData.append('data[Customer][document_file_down]',b64toBlob(document_file_down));
            formData.append('data[Customer][image_file]',b64toBlob(image_file));
        }

        $("#preloader").show();

        $.ajax({
            type: "POST",
            url: $('#CustomerRegisterStepOneForm').attr("action"),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response) {
                $("#preloader").hide();
                if($.trim(response) != "final"){
                    showMessage(response,true);
                }else{
                    location.reload();                    
                }
            },
            error: function (e) {
                console.log(e)
            }
        });

    }

});
