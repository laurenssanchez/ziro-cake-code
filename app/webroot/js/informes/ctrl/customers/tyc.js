$('input[type="checkbox"]').on('change', function(e){
   if(e.target.checked){
     $('#tyc-modal').modal();
   }
});