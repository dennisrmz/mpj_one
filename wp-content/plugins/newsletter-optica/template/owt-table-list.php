<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class OWTTableList extends WP_List_Table
{

    // define data set for WP_List_Table => DATA
    /**
     * The current list of items_dog.
     *
     * @since 3.1.0
     * @var array
     */
    public $items_dog;

    // prepare_item
    public function prepare_items()
    {
        global $wpdb;
        $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
        $order = isset($_GET['order']) ? trim($_GET['order']) : "";

        $search_term = isset($_POST['s']) ? trim($_POST['s']) : "";

        //tomando accion
        $doaction = $this->current_action();

        // if(isset($doaction)):
        //     //acciones a realizar si accion el bulk action delete
        //     if($doaction == 'delete'):
        //         if ( isset( $_REQUEST['post'] ) ):
        //             $posts_ids = implode(",",$_REQUEST['post']);
        //                 $delete_owners = $wpdb->get_results(
        //                     "DELETE FROM " . $wpdb->prefix . "fres_owner WHERE id in ( ". $posts_ids . " )"
        //                 );
        //         endif;
        //     endif;
        // endif;

        $datas = $this->wp_list_table_data($orderby, $order, $search_term);

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($datas);

        $this->set_pagination_args(array(
            "total_items" => $total_items,
            "per_page" => $per_page,
        ));

        $this->items = array_slice($datas, (($current_page - 1) * $per_page), $per_page);

        $columns = $this->get_columns();

        $hidden = $this->get_hidden_columns();

        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    public function wp_list_table_data($orderby = '', $order = '', $search_term = '')
    {
        global $wpdb;
        if (!empty($search_term)) {
            //wp_posts
            $all_posts = $wpdb->get_results(
                "SELECT ow.id,ow.email,ow.phone_number from " . $wpdb->prefix . "mpj_news_contacts ow WHERE (ow.name LIKE '%$search_term%' OR ow.email LIKE '%$search_term%')"
            );
        } else {
            if ($orderby == "id" && $order == "desc") {
                // wp_posts
                $all_posts = $wpdb->get_results(
                    "SELECT ow.id,ow.email,ow.phone_number,ow.created_at from " . $wpdb->prefix . "mpj_news_contacts ow ORDER BY ow.created_at DESC"
                );
            } else if ($orderby == "id" && $order == "asc") {
                // wp_posts
                $all_posts = $wpdb->get_results(
                    "SELECT ow.id,ow.email,ow.phone_number,ow.created_at from " . $wpdb->prefix . "mpj_news_contacts ow ORDER BY ow.created_at ASC"
                );
            } else {
                $all_posts = $wpdb->get_results(
                    "SELECT ow.id,ow.email,ow.phone_number, ow.created_at from " . $wpdb->prefix . "mpj_news_contacts ow ORDER BY ow.created_at DESC"
                );
            }
        }
        // var_dump($all_posts);die;

        $posts_array = array();

        if (count($all_posts) > 0) {
            foreach ($all_posts as $index => $post) {
                $posts_array[] = array(
                    "id" => $post->id,
                    "email" => $post->email,
                    "phone" => $post->phone_number,
                );
            }
        }

        return $posts_array;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array(
            "id" => array("id", true),
            //"email" => array("email", false)
        );
    }

    public function get_bulk_actions()
    {
        $actions = array(
            "delete" => "Delete",
        );

        return $actions;
    }

    // get_columns
    public function get_columns()
    {
        $columns = array(
            "cb" => "<input type='checkbox'/>",
            "id" => "id",
            "email" => "Email",
            "phone" => "Telefono",
            // "action" => "Action",
        );

        return $columns;
    }

    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="post[]" value="%s"/>', $item['id']);
    }

    // columns_default
    public function column_default($item, $column_name)
    {
        switch ($column_name) {

            case 'id':
            case 'email':
            case 'phone':
                return $item[$column_name];
            // case 'action':
            //     return '<a href="?page=' . $_GET['page'] . '&action=owt-show&post_id=' . $item['id'] . '">Show</a> | <a href="?page=' . $_GET['page'] . '&action=owt-delete&post_id=' . $item['id'] . '">Delete</a> ';
            default:
                return "no value";
        }
    }

    public function column_title($item)
    {
        /*$action = array(
        "edit" => sprintf('<a href=?page=%s&action=%s&post_id=%s> Edit </a>', $_GET['page'], 'owt-show', $item['id']),
        "delete" => sprintf('<a href=?page=%s&action=%s&post_id=%s> Delete </a>', $_GET['page'], 'owt-delete', $item['id'])
        );*/

        $action = array(
            "edit" => "<a href=?page=" . $_GET['page'] . "&action=owt-show&post_id=" . $item['id'] . "> Show </a>",
            "delete" => "<a href=?page=" . $_GET['page'] . "&action=owt-delete&post_id=" . $item['id'] . "> Delete </a>",
        );
        return sprintf('%1$s %2$s', $item['name'], $this->row_actions($action));
    }
} //Fin
function owt_show_data_list_table()
{
    $owt_table = new OWTTableList();

    $owt_table->prepare_items();?>

<?php
// # build the URL and query string
    $get_vars_excel = $_GET;
    $get_vars_excel['excel'] = true;
    $get_vars_excel['download_excel'] = true;

    $excel_url = site_url() . '?' . http_build_query($get_vars_excel);
    $to_date = gmdate('Y-m-d');
    $from_date = $to_date;

    ?>
<div class="container">
    <div class="row">
        <!-- <div class="col-sm-12">
            <!- download options ->
                <div class="wcf-ca-right-report-field-group">

                    <input class="wcf-ca-filter-input" type="text" id="wcf_ca_custom_filter_from"
                        placeholder="YYYY-MM-DD" value="<?php echo esc_attr($from_date); ?>" />
                    <input class="wcf-ca-filter-input" type="text" id="wcf_ca_custom_filter_to" placeholder="YYYY-MM-DD"
                        value="<?php echo esc_attr($to_date); ?>" />
                    <a class="dropdown-item url-site-pdf" href="<?php echo $excel_url ?>">Download as Excel</a>

                </div>
        </div> -->
    </div>
</div>

<h3>Lista de Contactos Subscritos</h3>

<?php

    echo "<form method='post' name='frm_search_post' action='" . $_SERVER['PHP_SELF'] . "?page=owt-list-table'>";
    $owt_table->search_box("Buscar Contacto", "search_post_id");
    echo "</form>";
    echo "<form id='posts-filter' method='post'>";
    $owt_table->display();
    echo "</form>";
}

owt_show_data_list_table();