<div>
    <div>
        <h4>Elegi tu tipo de lente</h4>
        <br>
        <?php       
            $args = array(
                'post_type' => 'opticampj_add_ons',
                'nopaging'  => true
            );

            $posts_type_tablas = new WP_Query($args);

            while( $posts_type_tablas->have_posts() ): $posts_type_tablas->the_post();

                if(get_field('tipo_add_on') == "tipo_lente"):

                    ?>
                        <div style="
                            display: flex;
                            justify-content: flex-start;
                            align-items: center;
                            flex-direction: row;"
                        >
                            <div class="image position-above">
                                <img style="width:50%;" src="<?php echo wp_get_attachment_image_src( get_field('imagen') , 'thumbnail' )[0]; ?>">
                            </div>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="<?php echo get_field('tipo_add_on'); ?>" id="<?php echo get_field('tipo_add_on') . "_" .  get_the_ID(); ?>" value="<?php the_ID(); ?>">
                                    <label class="form-check-label" for="<?php echo get_field('nombre'); ?>">
                                        <?php echo get_field('nombre'); ?>
                                    </label>
                                </div>
                                <div>
                                    Precio Extra: <?php echo get_field('precio_extra'); ?>
                                </div>
                            </div>

                            
                        </div>
                        <br>

                    <?php

                endif;

            endwhile;

            /******************* Obteniendo PostTypes Meriendas  ***********************/
            wp_reset_postdata();
                2509
        ?>
    </div>         

    <div>
        <h4>Dejanos Saber un poco mas</h4>
        <p>¿Posees Receta de Lentes?</p>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="mpjreceta" id="receta_si" value="1">
            <label class="form-check-label" for="si_receta">Si poseeo</label> 
        </div>

        <div style="display: flex; justify-content: flex-start; align-items: center; flex-direction: row;">

        <style type="text/css">
            .tg  {border-collapse:collapse;border-spacing:0;}
            .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
            overflow:hidden;padding:10px 5px;word-break:normal;}
            .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
            font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
            .tg .tg-zv4m{border-color:#000;text-align:left;vertical-align:top}
            .tg .tg-0lax{text-align:left;vertical-align:top;padding:0;max-width: 0.7rem;}
        </style>
            <table class="tg">
            <thead>
            <tr>
                <th class="tg-zv4m"></th>
                <th class="tg-zv4m">EST</th>
                <th class="tg-zv4m">CL</th>
                <th class="tg-zv4m">EJE</th>
                <th class="tg-zv4m">ADICION</th>
                <th class="tg-zv4m">TIPO</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="tg-0lax">O.D</td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
            </tr>
            <tr>
                <td class="tg-0lax">O.S</td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
                <td class="tg-0lax"><input type="number" name="OD_EST" style="width: 100%;">  </td>
            </tr>
            </tbody>
            </table>

        </div>


        <div class="form-check">
            <input class="form-check-input" type="radio" name="mpjreceta" id="receta_si" value="0">
            <label class="form-check-label" for="no_receta">No poseo</label> 
        </div>
            <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Reserva Cita' ) ) ); ?>" target="_blank"><?php esc_html_e( 'Reserva Cita', 'textdomain' ); ?></a>
            *Terminos: En la evalucion pueden haber cargo adicionales
    </div> 


    <div>
        <h4>Elegi tu tipo de Filtro</h4>
        <br>
        <?php       
            $args = array(
                'post_type' => 'opticampj_add_ons',
                'nopaging'  => true
            );

            $posts_type_tablas = new WP_Query($args);

            while( $posts_type_tablas->have_posts() ): $posts_type_tablas->the_post();

                if(get_field('tipo_add_on') == "tipo_filtro"):

                    ?>
                        <div style="
                            display: flex;
                            justify-content: flex-start;
                            align-items: center;
                            flex-direction: row;"
                        >
                            <div class="image position-above">
                                <img style="width:50%;" src="<?php echo wp_get_attachment_image_src( get_field('imagen') , 'thumbnail' )[0]; ?>">
                            </div>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="<?php echo get_field('tipo_add_on'); ?>" id="<?php echo get_field('tipo_add_on') . "_" .  get_the_ID(); ?>" value="<?php the_ID(); ?>">
                                    <label class="form-check-label" for="<?php echo get_field('nombre'); ?>">
                                        <?php echo get_field('nombre'); ?>
                                    </label>
                                </div>
                                <div>
                                    Precio Extra: <?php echo get_field('precio_extra'); ?>
                                </div>
                            </div>

                            
                        </div>
                        <br>

                    <?php

                endif;

            endwhile;

            /******************* Obteniendo PostTypes Meriendas  ***********************/
            wp_reset_postdata();
                
        ?>
    </div> 
</div>