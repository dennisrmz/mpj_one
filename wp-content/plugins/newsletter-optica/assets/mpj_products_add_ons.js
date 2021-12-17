(function ($) {
 
  var tipoLente   = "";
  var tipoFiltro  = "";
  var precioExtra = 0;
  var valExtra  = 0;
  var a = 0;
  var datos = {
    'producto'    : "",
    'precioExtra' : 0,
    'tipoLente'   : "",
    'tipoFiltro'  : "",
    'receta'      : {
              "od_EST"    : "",
              "od_CL"     : "",
              "od_EJE"    : "",
              "od_ADICION": "",
              "od_TIPO"   : "",
              "os_EST"    : "",
              "os_CL"     : "",
              "os_EJE"    : "",
              "os_ADICION": "",
              "os_TIPO"   : "",
    }
  }
  var pivote = 0;
  $(document).ready(function () {

    var vcLentes = {
      lentes: [],
      CurrentLentes: null,
      OnlyOneLentes: false, //Notifica que si hay una unica caja en Ls o en el server 
      Init: function () {
        let lentes_stg = localStorage.getItem("mpj_lentes"); //siempre sera un arreglo pero debe tener solo un item 	 
        if (lentes_stg == null || lentes_stg.length == 0) {
          this.OnlyOneLentes = true; //OnlyOneLentes se deja como true dado que no hay nada en ls
          return false;
        };

          let LentesData = JSON.parse(lentes_stg);
          this.OnlyOneLentes = false;
          this.lentes = LentesData;
          this.CurrentLentes = LentesData[0];
      },
      SyncServerAjax: function () {
        /** Si hay un remove del carrito */
        //1. Consultare lo que hay en el carrito de woocomerce atraves de Ajax

        var form = {
          action: 'mpj_get_cart'
        };

        $.post(mpj_obj.ajax_url, form).done(function (itemsCartWo) {

          //Hare init para actualizar mi variable global
          vcLentes.Init();

          //2. Filtrare para reconocer cual es el producto extra que tengo en el localStorage
          let itemsCartLS = [];
          let in_delete = null;
          itemsCartLS = vcLentes.lentes;

          $.each(itemsCartLS, function (i, item_ls) {

            let result = itemsCartWo.filter(d => d.id == item_ls.producto);
            if(result.length == 0){
              //este elemento ya no existe en LS
              console.log('este elemento no existe' + i);
              in_delete = i;
              return false;
            }
           
          });
          // elimino el elemento i que no retorno respuesta
          vcLentes.lentes.splice(in_delete,1);
          
          //3. Actualizare Local Storage
          vcLentes.Save();

          //4. Enviare el nuevo valor de Fee al backend
          vcLentes.sendFeeWoo();

        }).fail(function (data) {

          console.log('fail');
          console.log(data);

        });


        


      },
      Insert: function (data) {
        let NewLente = data
        this.lentes.push(NewLente); //Se agrego la caja 
        console.log(this.lentes);

        return true;
      },
      Save: function () {
        //this.Boxes[0] = this.CurrentBox;
        //localStorage.setItem("vclobi-boxes", JSON.stringify(this.Boxes));
        localStorage.setItem("mpj_lentes", JSON.stringify(this.lentes));

      },
      Delete: function (data) {
        
       
       console.log( this.lentes.findIndex(lente => lente.producto === data));
       let indice = this.lentes.findIndex(lente => lente.producto === data)
        this.lentes.splice(indice,1); 
      },
      sendFeeWoo() {
        // let total_cur_price = VCBoxes.CalcCurrentFob();  //Calcular monto total de precio total_fob_user 
        
        vcLentes.Init();

          //2. Filtrare para reconocer cual es el producto extra que tengo en el localStorage
          let itemsCartLS = [];
          let extra = 0;
          itemsCartLS = vcLentes.lentes;

          $.each(itemsCartLS, function (i, item_ls) {
            let extra_item = parseFloat(item_ls.precioExtra);
            extra = extra + extra_item;
          });
        extra = parseFloat(extra).toFixed(2);

        var form = {
          action: 'add_checkout_fee',
          valor: extra
        };
    
        $.post(mpj_obj.ajax_url, form).done(function (data) {
          if (data.codigo == 1) {
            //$('#btn-save-chg-box').addClass('d-none');
          } else {
    
          }
        }).fail(function () {
          console.log('fail');
          // closeLoading();
          // showAlertMsg("Error al guardar los totales, recargue el sitio", operacion.DEFAULT, operacionStatus.FAIL);
        });
      }


    }


    $(document.body).on('removed_from_cart', function (response) {
      console.log("elimino del carrito");
      /*** Este evento captura que algo se elimino del carrito por lo que  */
      vcLentes.SyncServerAjax();
    });

    /******* */
    vcLentes.Init();
    console.log(vcLentes);
    vcLentes.sendFeeWoo();
    console.log('producto_actual');
    console.log(mpj_obj.mpj_current_prod);
    console.log('Productos en carrito');
    console.log(mpj_obj.products_in_cart);
    console.log('Rangos');
    console.log(mpj_obj.limites_rango);
    // alert('estoy cargando en producto');      
    $(".radio_receta").click(function () {
      var valor = $(this).val();
      console.log(valor)
      if (valor == 1) {
        $("#tablaReceta").css("display", "block");
      } else {
        $("#tablaReceta").css("display", "none");
      }

    });

    $('#anterior').click(function () {
      console.log(a)
      if (a == 1) {
        $("#tipoLent").css("display", "block");
        $("#tipoFilt").css("display", "none");
        a--;
      } else {
        if (a == 2) {
          $("#tipoFilt").css("display", "block");
          $("#receta").css("display", "none");
          $("#siguiente").css("display", "block");
          $("#guardar").css("display", "none");
          a--;
          precioExtra=0;
          var receta = {
            "od_EST"    : $("#od_EST").val(),
            "od_CL"     : $("#od_CL").val(),
            "od_EJE"    : $("#od_EJE").val(),
            "od_ADICION": $("#od_ADICION").val(),
            "od_TIPO"   : $("#od_TIPO").val(),
            "os_EST"    : $("#os_EST").val(),
            "os_CL"     : $("#os_CL").val(),
            "os_EJE"    : $("#os_EJE").val(),
            "os_ADICION": $("#os_ADICION").val(),
            "os_TIPO"   : $("#os_TIPO").val(),
          }

          
        }
      }
    });
    $('#siguiente').click(function () {

      if (a == 0) {
        $("#tipoLent").css("display", "none");
        $("#tipoFilt").css("display", "block");
        a++;
       
      } else {
        if (a == 1) {
          $("#tipoFilt").css("display", "none");
          $("#receta").css("display", "block");
          $("#siguiente").css("display", "none");
          $("#guardar").css("display", "block");
          a++;
        
        }
      }
    });


    $('#guardar').click(function () {
     
      precioExtra = parseFloat(precioExtra)+parseFloat($("#"+tipoLente+"").val())
      precioExtra = parseFloat(precioExtra)+parseFloat($("#"+tipoFiltro+"").val())
      console.log(precioExtra)
      $("#siguiente").css("display", "none");
      $("#guardar").css("display", "none");
      for (var i = 0; i < mpj_obj.limites_rango.length; i++) {

        if ((parseFloat($("#od_ADICION").val()) > parseFloat(mpj_obj.limites_rango[i].min_limit) && parseFloat($("#od_ADICION").val()) < parseFloat(mpj_obj.limites_rango[i].max_limit)) ||
          (parseFloat(($("#os_ADICION").val()) > parseFloat(mpj_obj.limites_rango[i].min_limit) && parseFloat($("#os_ADICION").val() < mpj_obj.limites_rango[i].max_limit)))) {
          valExtra = parseFloat(mpj_obj.limites_rango[i].precio_extra)
          console.log(mpj_obj.limites_rango[i].precio_extra);
        }
        console.log(mpj_obj.limites_rango[i].min_limit);
      }
      precioExtra = parseFloat(precioExtra)+parseFloat(valExtra)
     
      datos.producto = mpj_obj.mpj_current_prod,
        datos.tipoLente         = tipoLente,
        datos.tipoFiltro        = tipoFiltro,
        datos.precioExtra       = precioExtra,
        datos.receta.od_EST     = $("#od_EST").val()
        datos.receta.od_CL      = $("#od_CL").val()
        datos.receta.od_EJE     = $("#od_EJE").val()
        datos.receta.od_ADICION = $("#od_ADICION").val()
        datos.receta.od_TIPO    = $("#od_TIPO").val()
        datos.receta.os_EST     = $("#os_EST").val()
        datos.receta.os_CL      = $("#os_CL").val()
        datos.receta.os_EJE     = $("#os_EJE").val()
        datos.receta.os_ADICION = $("#os_ADICION").val()
        datos.receta.os_TIPO    = $("#os_TIPO").val()
      if (pivote == 0) {
        vcLentes.Delete(datos.producto);
        vcLentes.Insert(datos);
        vcLentes.Save();

        pivote++;
      } else {

        // delete 
        vcLentes.Delete(datos.producto);
        vcLentes.Insert(datos);
        vcLentes.Save();
        console.log("datos vcLentes:" )
        console.log(vcLentes.lentes)
      }
      console.log(precioExtra);
      console.log(datos);
    });

  });

  $(".tipoLentes").click(function () {
    tipoLente = $(this).val();
    console.log("Tipo de lente " + tipoLente);   
  
  });
  $(".tipoFiltro").click(function () {
    tipoFiltro = $(this).val();
    console.log("Tipo de filtro " + tipoFiltro);
    
   
   
  });


})(jQuery);