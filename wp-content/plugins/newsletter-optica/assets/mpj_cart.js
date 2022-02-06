(function ($) {
    $(document).ready(function () {

        $('button[name="update_cart"]').hide();
        $('input[title="Cantidad"]').prop('readOnly', true);

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
      

        $(document.body).on('updated_cart_totals', function () {
            vcLentes.SyncServerAjax();
        });
        
    });//Finish OnReady


})(jQuery);