<?php
function mpj_addons_post_type()
{
    $labels = array(
        'name' => _x('Add Ons Actuales', 'Post Type General Name', 'opticampj'),
        'singular_name' => _x('Add On', 'Post Type Singular Name', 'opticampj'),
        'menu_name' => __('Add Ons Actuales', 'opticampj'),
        'name_admin_bar' => __('Add On', 'opticampj'),
        'archives' => __('Archivo', 'opticampj'),
        'attributes' => __('Atributos', 'opticampj'),
        'parent_item_colon' => __('Add On Padre', 'opticampj'),
        'all_items' => __('Todas Las Add Ons', 'opticampj'),
        'add_new_item' => __('Agregar Add On', 'opticampj'),
        'add_new' => __('Agregar Add On', 'opticampj'),
        'new_item' => __('Nueva Add On', 'opticampj'),
        'edit_item' => __('Editar Add On', 'opticampj'),
        'update_item' => __('Actualizar Add On', 'opticampj'),
        'view_item' => __('Ver Add On', 'opticampj'),
        'view_items' => __('Ver Add Ons', 'opticampj'),
        'search_items' => __('Buscar Add On', 'opticampj'),
        'not_found' => __('No Encontrado', 'opticampj'),
        'not_found_in_trash' => __('No Encontrado en Papelera', 'opticampj'),
        'featured_image' => __('Imagen Destacada', 'opticampj'),
        'set_featured_image' => __('Guardar Imagen destacada', 'opticampj'),
        'remove_featured_image' => __('Eliminar Imagen destacada', 'opticampj'),
        'use_featured_image' => __('Utilizar como Imagen Destacada', 'opticampj'),
        'insert_into_item' => __('Insertar en Add On', 'opticampj'),
        'uploaded_to_this_item' => __('Agregado en Add On', 'opticampj'),
        'items_list' => __('Lista de Add Ons', 'opticampj'),
        'items_list_navigation' => __('Navegación de Add Ons', 'opticampj'),
        'filter_items_list' => __('Filtrar Add Ons', 'opticampj'),
    );
    $args = array(
        'label' => __('Add On', 'opticampj'),
        'description' => __('Add Ons para el Sitio Web', 'opticampj'),
        'labels' => $labels,
        'supports' => array('title', 'thumbnail'),
        'hierarchical' => true, // true = posts, false = paginas
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-visibility',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    );
    register_post_type('opticampj_add_ons', $args);
}

add_action('init', 'mpj_addons_post_type', 0);
