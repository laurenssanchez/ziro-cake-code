

<script type="text/javascript">

 var data = <?php echo $data ?>;


 var opciones = "";
 var valor = 0;
 var initial = 0;
 var final = 0;



const moneyRange = document.querySelector("#moneyRange");

moneyRange.addEventListener('blur', (event) => {
	updateQuotes();
});

const solicitado = document.querySelector("#totalSolicita2");

solicitado.addEventListener('onchage', (event) => {
	updateQuotes();
});


solicitado.addEventListener('blur', (event) => {
	updateQuotes();
});


const valueprice = document.querySelector("#valueNumberPrice");

valueprice.addEventListener('onchage', (event) => {
	updateQuotes();
});



function  updateQuotes(){
  data = "";
  var data = <?php echo $data ?>;
	//document.getElementById('coutas-number').innerHTML="";
	opciones = "";
	document.getElementById('coutas-number').innerHTML="";
	//coutas-number
	//document.getElementById("totalSolicita2").value;
	valor = Number(document.getElementById("totalSolicita2").value)
 // alert(typeof(valor));
    if (valor==""){
	    valor =	<?php echo $valorMini ?>
	  }

	initial = 0;

	final = 0;

	for (x of data) {
		//console.log(x.credits_lines_details.min_value);



   if ((valor>=x.credits_lines_details.min_value) && (valor <= x.credits_lines_details.max_value   )) {


			if (initial==0){
				  initial=x.credits_lines_details.month;

			}else if(initial>x.credits_lines_details.month){
				  initial=x.credits_lines_details.month;

			}




   }
	}


  for (x of data) {
		//console.log(x.credits_lines_details.min_value);



   if ((valor>=x.credits_lines_details.min_value) && (valor <= x.credits_lines_details.max_value   )) {


			if (final==0){
        final=x.credits_lines_details.month;

			}else if(final<x.credits_lines_details.month){
        final=x.credits_lines_details.month;

			}




   }
	}





  if(initial == 0 || final == 0){
      console.log("raro");
  }




	for (var i = initial; i <= final;i++){
    // console.log(i);
		opciones += "<option data-mes="+ i +" data-quince="+i*2 +" value="+ i +">"+ i +"</option>" + "\n";

	}





	document.getElementById('coutas-number').innerHTML=opciones
	document.getElementById('coutas-number').value = initial
}

document.onload = updateQuotes();



</script>
