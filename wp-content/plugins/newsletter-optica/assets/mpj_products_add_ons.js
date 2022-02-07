(function ($) {
  var Posee_receta = "si";
  var tipoLente = "nada";
  var precioExtra = 0;
  var valExtra = 0;
  var a = 0;
  var datos = {
    'producto': "",
    'precioExtra': 0,
    'tipoLente': "",
    'Posee_receta': "",
    'receta': {
      "od_EST": "",
      "od_CL": "",
      "od_EJE": "",
      "od_ADICION": "",
      "od_TIPO": "",
      "os_EST": "",
      "os_CL": "",
      "os_EJE": "",
      "os_ADICION": "",
      "os_TIPO": "",
    },
    'filtros': [],
  }

  function vcShowAlertValidatePlans(target_msg) {
    alertify.defaults.autoReset = true;
    alertify.defaults.closable = false;
    let content = "<p style='text-align: center;'>" + target_msg + "</p>";
    alertify.alert('¡Reserve su cita para continuar!', content).set('onok', function (closeEvent) {
      alertify.success('Ok');
      window.location.replace(mpj_obj.home_url + "reserva-cita/");
    });
  }

  function vcShowAlertValidatePlansSinRedireccion(target_msg) {
    alertify.defaults.autoReset = true;
    alertify.defaults.closable = false;
    let content = "<p style='text-align: center;'>" + target_msg + "</p>";
    alertify.alert('¡Reserve su cita para continuar!', content).set('onok', function (closeEvent) {
      alertify.success('Ok');
    });
  }

  $(document).ready(function () {
    console.log(mpj_obj.all_add_ons);
    var vcLentes = {
      lentes: [],
      CurrentLentes: null,
      OnlyOneLentes: false, //Notifica que si hay una unica caja en Ls o en el server 
      Init: function () {

        if (mpj_obj.products_in_cart.length == 0) {
          localStorage.removeItem('mpj_lentes');
          var form = {
            action: 'add_checkout_fee',
            valor: 0
          };

          $.post(mpj_obj.ajax_url, form).done(function (data) {
            if (data.codigo == 1) {
              //$('#btn-save-chg-box').addClass('d-none');
            } else {

            }
          }).fail(function () {
            // closeLoading();
            // showAlertMsg("Error al guardar los totales, recargue el sitio", operacion.DEFAULT, operacionStatus.FAIL);
          });
        }

        $.each(mpj_obj.products_in_cart, function (i, item_wo) {

          if (item_wo.id == mpj_obj.mpj_current_prod) {
            $("#contenedores-filtros").css("pointer-events", "all");
            $("#contenedores-filtros").css("opacity", "1");
            $("#contenedores-filtros").css("display", "block");
          }


        });
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
      updateBeforeSendFee() {
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
            if (result.length == 0) {
              //este elemento ya no existe en LS
              in_delete = i;
              return false;
            }

          });
          // elimino el elemento i que no retorno respuesta
          vcLentes.lentes.splice(in_delete, 1);

          //3. Actualizare Local Storage
          vcLentes.Save();

          //4. Enviare el nuevo valor de Fee al backend
          vcLentes.sendFeeWoo();

        }).fail(function (data) {

        });





      },
      Insert: function (data) {
        let NewLente = data
        this.lentes.push(NewLente); //Se agrego la caja

        return true;
      },
      Save: function () {
        //this.Boxes[0] = this.CurrentBox;
        //localStorage.setItem("vclobi-boxes", JSON.stringify(this.Boxes));
        localStorage.setItem("mpj_lentes", JSON.stringify(this.lentes));

      },
      Delete: function (data) {
        let indice = this.lentes.findIndex(lente => lente.producto === data)
        if (indice != -1) {
          this.lentes.splice(indice, 1);
        }
      },
      sendFeeWoo() {
        // let total_cur_price = VCBoxes.CalcCurrentFob();  //Calcular monto total de precio total_fob_user 
        vcLentes.updateBeforeSendFee();
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
          // closeLoading();
          // showAlertMsg("Error al guardar los totales, recargue el sitio", operacion.DEFAULT, operacionStatus.FAIL);
        });
      }


    }


    $(document.body).on('removed_from_cart', function (response) {
      /*** Este evento captura que algo se elimino del carrito por lo que  */
      vcLentes.SyncServerAjax();
    });

    /******* */
    vcLentes.Init();
    vcLentes.sendFeeWoo();
    // alert('estoy cargando en producto');      
    $(".radio_receta").click(function () {

      var valor = $(this).val();
      if (valor == 1) {
        $("#tablaReceta").css("display", "block");
        Posee_receta = "si";
        if (a == 1) {

          a--;
        }
        $("#siguiente").css("display", "block");
        $("#guardar").css("display", "none");
      } else {
        $("#tablaReceta").css("display", "none");
        $("#siguiente").css("display", "none");
        $("#guardar").css("display", "block");
        Posee_receta = "no";
        $(".receta").css("display", "none");
        a++;

      }

    });

    $('#anterior').click(function () {
      if (a == 1) {
        $("#receta").css("display", "block");
        $("#tipoLent").css("display", "none");
        $("#anterior").css("display", "none");
        a--;
      } else {
        if (a == 2) {
          $("#tipoLent").css("display", "block");
          $("#tipoFilt").css("display", "none");
          $("#siguiente").css("display", "block");
          $("#guardar").css("display", "none");
          a--;
          precioExtra = 0;
          var receta = {
            "od_EST": $("#od_EST").val(),
            "od_CL": $("#od_CL").val(),
            "od_EJE": $("#od_EJE").val(),
            "od_ADICION": $("#od_ADICION").val(),
            "od_TIPO": $("#od_TIPO").val(),
            "os_EST": $("#os_EST").val(),
            "os_CL": $("#os_CL").val(),
            "os_EJE": $("#os_EJE").val(),
            "os_ADICION": $("#os_ADICION").val(),
            "os_TIPO": $("#os_TIPO").val(),
          }
          $("#seleciones").css("display", "none");

        }
      }
    });

    $('#siguiente').click(function () {

      if (a == 0) {
        if ($("#od_EST").val() === "" || $("#od_CL").val() === "" || $("#od_EJE").val() === "" || $("#od_ADICION").val() === "" ||
          $("#od_TIPO").val() === "" || $("#os_EST").val() === "" || $("#os_CL").val() === "" || $("#os_EJE").val() === "" ||
          $("#os_ADICION").val() === "" || $("#os_TIPO").val() === "") {
          let message = "Debe completar todos los datos de la receta oftalmologica, si no posee los datos de click en no posee receta y reserve su cita.";
          vcShowAlertValidatePlansSinRedireccion(message);
          return false;
        }

        $("#receta").css("display", "none");
        $("#tipoLent").css("display", "block");
        $("#anterior").css("display", "block");

        a++;

      } else {
        if (a == 1) {
          $("#tipoLent").css("display", "none");
          $("#tipoFilt").css("display", "block");
          $("#siguiente").css("display", "none");
          $("#guardar").css("display", "block");
          a++;

        }
      }
    });


    $('#guardar').click(function () {

      $('input:radio.tipoLentes').each(function () {

        if ($(this).prop('checked')) {
          tipoLente = $(this).val();
        }
      });

      //Obteniendo precio extra de tipo de lente 
      if (tipoLente != "nada") {
        precioExtra = parseFloat(precioExtra) + parseFloat($("#" + tipoLente + "").val())
      }
      //Obteniendo check box seleccionado y precio extra
      $("input:checkbox.tipoFiltro:checked").each(function () {
        valor = $("#" + `${$(this).val()}`).val();
        precioExtra = parseFloat(precioExtra) + parseFloat(valor);
        datos.filtros.push($(this).val());

      });

      $("#siguiente").css("display", "none");
      $("#guardar").css("display", "none");
      //Obtener precio extra

      //Obteniendo el valor mayor de adicion

      let valor_definitivo = 0;

      let od_est = Math.abs(parseFloat($("#od_EST").val()));
      let os_est = Math.abs(parseFloat($("#os_EST").val()));
      let od_cl = Math.abs(parseFloat($("#od_CL").val()));
      let os_cl = Math.abs(parseFloat($("#os_CL").val()));
      var myArray = [od_est, os_est, od_cl, os_cl];
      valor_definitivo = Math.max(...myArray);


      for (var i = 0; i < mpj_obj.limites_rango.length; i++) {

        if (valor_definitivo >= parseFloat(mpj_obj.limites_rango[i].min_limit) && valor_definitivo <= parseFloat(mpj_obj.limites_rango[i].max_limit)) {
          valExtra = parseFloat(mpj_obj.limites_rango[i].precio_extra)
        }
      }
      precioExtra = parseFloat(precioExtra) + parseFloat(valExtra)

      datos.producto = mpj_obj.mpj_current_prod,
      datos.tipoLente = tipoLente,
      datos.precioExtra = precioExtra,
      datos.Posee_receta = Posee_receta,
      datos.receta.od_EST = $("#od_EST").val()
      datos.receta.od_CL = $("#od_CL").val()
      datos.receta.od_EJE = $("#od_EJE").val()
      datos.receta.od_ADICION = $("#od_ADICION").val()
      datos.receta.od_TIPO = $("#od_TIPO").val()
      datos.receta.os_EST = $("#os_EST").val()
      datos.receta.os_CL = $("#os_CL").val()
      datos.receta.os_EJE = $("#os_EJE").val()
      datos.receta.os_ADICION = $("#os_ADICION").val()
      datos.receta.os_TIPO = $("#os_TIPO").val()

      vcLentes.Delete(datos.producto);
      vcLentes.Insert(datos);
      vcLentes.Save();
      console.log('val extra: ' +  valExtra);
      //Reinicando variables por si estan guardadas en cache
      $("#tipoFilt").css("display", "none");
      $("#seleciones").css("display", "block");
      $("#seleciones table#selecciones_receta_table tbody").html(`
                <tr>
                    <td class="tg-0lax">O.D</td>
                    <td class="tg-0lax tbl_od_EST">${datos.receta.od_EST}</td>
                    <td class="tg-0lax tbl_od_CL">${datos.receta.od_CL}</td>
                    <td class="tg-0lax tbl_od_EJE">${datos.receta.od_EJE}</td>
                    <td class="tg-0lax tbl_od_ADICION">${datos.receta.od_ADICION}</td>
                    <td class="tg-0lax tbl_od_TIPO">${datos.receta.od_TIPO}</td>
                </tr>
                <tr>
                    <td class="tg-0lax">O.S</td>
                    <td class="tg-0lax tbl_os_EST">${datos.receta.os_EST}</td>
                    <td class="tg-0lax tbl_os_CL">${datos.receta.os_CL}</td>
                    <td class="tg-0lax tbl_os_EJE">${datos.receta.os_EJE}</td>
                    <td class="tg-0lax tbl_os_ADICION">${datos.receta.os_ADICION}</td>
                    <td class="tg-0lax tbl_os_TIPO">${datos.receta.os_TIPO}</td>
                </tr>
                <tr>
                    <td class="tg-0lax">Costo total
                         por aumento: </td>
                    <td class="tg-0lax"> </td>
                    <td class="tg-0lax"> </td>
                    <td class="tg-0lax"> </td>
                    <td class="tg-0lax"> </td>
                    <td class="tg-0lax">$<span>${parseFloat(valExtra)}</span></td>
                </tr>`);

      $.each(mpj_obj.all_add_ons, function (i, item) {
        if (item.id == datos.tipoLente) {
          
          let final_price = 0;
          final_price = item.precio_extra_rebajado !== null ? item.precio_extra_rebajado : item.precio_extra;
          $("table#tbl_tipo_lente tbody").html(`
          <tr>
              <td class="tbl_name_tipo_lente">${item.nombre}</td>
              <td>Precio: $ <span>${final_price}</span></td>
          </tr>`);
        }
      });

      $("table#tbl_tipo_filtro tbody").empty();
      for (i = 0; i < datos.filtros.length; i++) {
        $.each(mpj_obj.all_add_ons, function (iaddons, item) {

          if (item.id == datos.filtros[i]) {
            let final_price = 0;
            final_price = item.precio_extra_rebajado !== null ? item.precio_extra_rebajado : item.precio_extra;
          
            $("table#tbl_tipo_filtro tbody").append(`
            <tr>
                <td class="tbl_name_tipo_lente">${item.nombre}</td>
                <td>Precio: $ <span>${final_price}</span></td>
            </tr>`);
          }
        });
      }

      $("#precio_total").html(datos.precioExtra);


      tipoLente = "nada";
      precioExtra = 0;
      valExtra = 0;
      datos = {
        'producto': "",
        'precioExtra': 0,
        'tipoLente': "",
        'Posee_receta': "",
        'receta': {
          "od_EST": "",
          "od_CL": "",
          "od_EJE": "",
          "od_ADICION": "",
          "od_TIPO": "",
          "os_EST": "",
          "os_CL": "",
          "os_EJE": "",
          "os_ADICION": "",
          "os_TIPO": "",
        },
        'filtros': [],
      }
      vcLentes.sendFeeWoo();
      if (Posee_receta == "no") {
        let message = "Para poder finalizar su compra, Sera redireccionado a la pagina de reservas de citas, en la cual debe de agendar una cita oftalmologica para poder finalizar su proceso de compra, al momento de realizar la evalución oftalmologica podrian haber cargos adicinales a su compra";
        vcShowAlertValidatePlans(message);
        setTimeout(function () {
          window.location.replace(mpj_obj.home_url + "reserva-cita/");
        }, 6000);
      }

    });


  });



})(jQuery);