(function ($) {


    $(document).ready(function () {

        $("#order_comments").prop("readonly",true);

        let lentes_stg = localStorage.getItem("mpj_lentes"); //siempre sera un arreglo pero debe tener solo un item 	 

        if (lentes_stg == null || lentes_stg.length == 0) {
          this.OnlyOneLentes = true; //OnlyOneLentes se deja como true dado que no hay nada en ls
          return false;
        };

        let LentesData = JSON.parse(lentes_stg);
        
        $.each(mpj_obj.products_in_cart, function (i_wo, item_wo) {
            let flag_exist = false;
            $.each(LentesData, function (i_ls, item_ls) {
                if(item_wo.id == item_ls.producto){

                    flag_exist = true;
                    return true;
                }
                
            });
            if(flag_exist == false){
                alert('Debe agregar los complementos a su aro, sera redireccionado al producto para que seleccione los complementos');
                
                setTimeout(function() {
                    window.location.replace(item_wo.uri);
                }, 1000);
            }

        });
        
        $string_notes = "";
        $.each(LentesData, function (i, item_ls) {
            //Nombre producto
            $string_notes += `PRODUCTO ${i + 1} : `;

            let result = mpj_obj.products_in_cart.filter(d => d.id == item_ls.producto);
            $string_notes += `${result[0]?.name}`;
            $string_notes += '\n';
            //Nombre tipo de Lente
            let result_tipo_lente = mpj_obj.all_add_ons.filter(d => d.id == item_ls.tipoLente);
            $string_notes += ` TIPO LENTE: \n ${result_tipo_lente[0]?.nombre} `;
            $string_notes += '\n';

            //Nombres de Filtros
            $string_notes += ` FILTROS: `;

            $.each(item_ls.filtros, function (i_filtro, item_filtro) {
                
                let result_tipo_filtro = mpj_obj.all_add_ons.filter(d => d.id == item_filtro);
                $string_notes += '\n';
                $string_notes += ` Filtro ${i_filtro + 1}: ${result_tipo_filtro[0]?.nombre},`;
            });

            $string_notes += '\n';
            $string_notes += ' ESPECIFICACIONES RECETA: ';
            for (let [key, value] of Object.entries(item_ls.receta)) {
                $string_notes += '\n';
                $string_notes += `${key} : ${value} `;
            }

            $string_notes += '\n\n';

            $("#order_comments").val($string_notes);
            
        });

    });


})(jQuery);