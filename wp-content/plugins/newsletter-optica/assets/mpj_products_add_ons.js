(function ($) {
    var a = 0;
    $(document).ready(function () {

       // alert('estoy cargando en producto');      
       $(".radio_receta").click(function(){
        var valor = $(this).val();
        console.log(valor)
          if(valor == 1)
          {
            $("#tablaReceta").css("display", "block");  
          }else{
            $("#tablaReceta").css("display", "none");  
          }
      
       });

       $('#anterior').click(function () {
        console.log(a)
        if(a==1){
         $("#tipoLent").css("display", "block");
         $("#tipoFilt").css("display", "none");
          a--;
         }else{
         if(a==2){
            $("#tipoFilt").css("display", "block");
           $("#receta").css("display", "none");
           a--;
           var receta = {
            "od_EST":$("#od_EST").val(),
            "od_CL":$("#od_CL").val(),
            "od_EJE":$("#od_EJE").val(),
            "od_ADICION":$("#od_ADICION").val(),
            "od_TIPO":$("#od_TIPO").val(),
            "os_EST":$("#os_EST").val(),
            "os_CL":$("#os_CL").val(),
            "os_EJE":$("#os_EJE").val(),
            "os_ADICION":$("#os_ADICION").val(),
            "os_TIPO":$("#os_TIPO").val(),
            }
          
            console.log(receta);
            }
        }
        });
       $('#siguiente').click(function () {
         
        if(a==0){
        $("#tipoLent").css("display", "none");
        $("#tipoFilt").css("display", "block");
        a++;
        
         }
       else{
         if(a==1){
            $("#tipoFilt").css("display", "none");
            $("#receta").css("display", "block");
            a++;
             }
            }          
          });
    
    });

    $(".tipoLentes").click(function(){
      var tipoLente = $(this).val();
      console.log("Tipo de lente "+ tipoLente);
    
     });
     $(".tipoFiltro").click(function(){
      var tipoFiltro = $(this).val();
      console.log("Tipo de filtro "+ tipoFiltro);     
    
     });
     
  
})(jQuery);
