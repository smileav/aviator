<?php
class ControllerEIProduct extends Controller {

    public function index() {
        $this->load->model('ie_cli/ie');

        $languages = $this->model_ie_cli_ie->compareLanguages();

        $tables = [
            'product',
            'product_description'
        ];

        // Debug
        /*
        foreach ($tables as $key => $table) {
            echo 'Table: ' . $table . PHP_EOL;

            $columns_table      = $this->model_ie_cli_ie->getColumnsTable($table, 'db');
            $columns_table_don  = $this->model_ie_cli_ie->getColumnsTable($table, 'db_don');

            foreach ($columns_table as $column_key => $column_table) {
                if (!isset($columns_table_don[$column_key])) {
                    echo ': ' . $column_key . PHP_EOL;
                }
            }

            echo PHP_EOL;
            echo 'Table_don: ' . $table . PHP_EOL;

            foreach ($columns_table_don as $column_key => $column_table) {
                if (!isset($columns_table[$column_key])) {
                    echo ': ' . $column_key . PHP_EOL;
                }
            }

            echo '====================' . PHP_EOL;
        }

        die();
        */



        // oc_address
        $this->db->query("TRUNCATE `" . DB_PREFIX . "address`");

        $filter_data = $this->model_ie_cli_ie->get_address();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'address', 'address_id',
            [
                'address_id'   => 'address_id',
                'customer_id'              => 'customer_id',
                'firstname'             => 'firstname',
                'lastname'        => 'lastname',
                'company'        => 'company',
                'address_1'        => 'address_1',
                'address_2'        => 'address_2',
                'city'        => 'city',
                'postcode'        => 'postcode',
                'country_id'        => 'country_id',
                'zone_id'        => 'zone_id',
                'custom_field'        => 'custom_field'
            ]
        );

        // oc_customer
        $this->db->query("TRUNCATE `" . DB_PREFIX . "customer`");

        $filter_data = $this->model_ie_cli_ie->get_customer();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'customer', 'customer_id',
            [
                'customer_id'   => 'customer_id',
                'customer_group_id'              => 'customer_group_id',
                'store_id'             => 'store_id',
                'language_id'             => 0,
                'firstname'        => 'firstname',
                'lastname'        => 'lastname',
                'email'        => 'email',
                'telephone'        => 'telephone',
                'fax'        => 'fax',
                'password'        => 'password',
                'salt'        => 'salt',
                'cart'        => 'cart',
                'wishlist'        => 'wishlist',
                'newsletter'        => 'newsletter',
                'address_id'        => 'address_id',
                'custom_field'        => 'custom_field',
                'ip'        => 'ip',
                'status'        => 'status',
                'approved'        => 'approved',
                'safe'        => 'safe',
                'token'        => 'token',
                'date_added'        => 'date_added'
            ]
        );

        // oc_order
        $this->db->query("TRUNCATE `" . DB_PREFIX . "order`");

        $filter_data = $this->model_ie_cli_ie->get_order();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'order', 'order_id',
            [
                'order_id'   => 'order_id',
                'invoice_no'              => 'invoice_no',
                'invoice_prefix'             => 'invoice_prefix',
                'novaposhta_cn_number'             => 'novaposhta_cn_number',
                'novaposhta_cn_ref'        => 'novaposhta_cn_ref',
                'store_id'        => 'store_id',
                'store_name'        => 'store_name',
                'store_url'        => 'store_url',
                'customer_id'        => 'customer_id',
                'customer_group_id'        => 'customer_group_id',
                'firstname'        => 'firstname',
                'lastname'        => 'lastname',
                'email'        => 'email',
                'telephone'        => 'telephone',
                'fax'        => 'fax',
                'custom_field'        => 'custom_field',
                'payment_firstname'        => 'payment_firstname',
                'payment_lastname'        => 'payment_lastname',
                'payment_company'        => 'payment_company',
                'payment_address_1'        => 'payment_address_1',
                'payment_address_2'        => 'payment_address_2',
                'payment_city'        => 'payment_city',
                'payment_postcode'        => 'payment_postcode',
                'payment_country'        => 'payment_country',
                'payment_country_id'        => 'payment_country_id',
                'payment_zone'        => 'payment_zone',
                'payment_zone_id'        => 'payment_zone_id',
                'payment_address_format'        => 'payment_address_format',
                'payment_custom_field'        => 'payment_custom_field',
                'payment_method'        => 'payment_method',
                'payment_code'        => 'payment_code',
                'shipping_firstname'        => 'shipping_firstname',
                'shipping_lastname'        => 'shipping_lastname',
                'shipping_company'        => 'shipping_company',
                'shipping_address_1'        => 'shipping_address_1',
                'shipping_address_2'        => 'shipping_address_2',
                'shipping_city'        => 'shipping_city',
                'shipping_postcode'        => 'shipping_postcode',
                'shipping_country'        => 'shipping_country',
                'shipping_country_id'        => 'shipping_country_id',
                'shipping_zone'        => 'shipping_zone',
                'shipping_zone_id'        => 'shipping_zone_id',
                'shipping_address_format'        => 'shipping_address_format',
                'shipping_custom_field'        => 'shipping_custom_field',
                'shipping_method'        => 'shipping_method',
                'shipping_code'        => 'shipping_code',
                'comment'        => 'comment',
                'total'        => 'total',
                'order_status_id'        => 'order_status_id',
                'affiliate_id'        => 'affiliate_id',
                'commission'        => 'commission',
                'marketing_id'        => 'marketing_id',
                'tracking'        => 'payment_address_2',
                'language_id'        => 'language_id',
                'currency_id'        => 'currency_id',
                'currency_code'        => 'currency_code',
                'currency_value'        => 'currency_value',
                'ip'        => 'ip',
                'forwarded_ip'        => 'forwarded_ip',
                'user_agent'        => 'user_agent',
                'accept_language'        => 'accept_language',
                'date_added'        => 'date_added',
                'date_modified'        => 'date_modified'

            ]
        );

        // oc_order_history
        $this->db->query("TRUNCATE `" . DB_PREFIX . "order_history`");

        $filter_data = $this->model_ie_cli_ie->get_order_history();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'order_history', 'order_history_id',
            [
                'order_history_id'   => 'order_history_id',
                'order_id'   => 'order_id',
                'order_status_id'              => 'order_status_id',
                'notify'             => 'notify',
                'comment'        => 'comment',
                'date_added'        => 'date_added'
            ]
        );

        // oc_order_option
        $this->db->query("TRUNCATE `" . DB_PREFIX . "order_option`");

        $filter_data = $this->model_ie_cli_ie->get_order_option();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'order_option', 'order_option_id',
            [
                'order_option_id'   => 'order_option_id',
                'order_id'   => 'order_id',
                'order_product_id'              => 'order_product_id',
                'product_option_id'             => 'product_option_id',
                'product_option_value_id'             => 'product_option_value_id',
                'name'             => 'name',
                'value'        => 'value',
                'type'        => 'type'
            ]
        );

        // oc_order_product
        $this->db->query("TRUNCATE `" . DB_PREFIX . "order_product`");

        $filter_data = $this->model_ie_cli_ie->get_order_product();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'order_product', 'order_product_id',
            [
                'order_product_id'   => 'order_product_id',
                'order_id'   => 'order_id',
                'product_id'              => 'product_id',
                'name'             => 'name',
                'model'             => 'model',
                'quantity'             => 'quantity',
                'price'        => 'price',
                'total'        => 'total',
                'tax'        => 'tax',
                'reward'        => 'reward'
            ]
        );

        // oc_order_status
        $this->db->query("TRUNCATE `" . DB_PREFIX . "order_status`");

        $filter_data = $this->model_ie_cli_ie->get_order_status();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'order_status', 'order_status_id',
            [
                'order_status_id'   => 'order_status_id',
                'language_id'             => 'language_id',
                'name'             => 'name'
            ]
        );

        // oc_order_total
        $this->db->query("TRUNCATE `" . DB_PREFIX . "order_total`");

        $filter_data = $this->model_ie_cli_ie->get_order_total();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'order_total', 'order_total_id',
            [
                'order_total_id'   => 'order_total_id',
                'order_id'             => 'order_id',
                'code'             => 'code',
                'title'             => 'title',
                'value'             => 'value',
                'sort_order'             => 'sort_order'
            ]
        );

        $this->model_ie_cli_ie->clearProductsData();

        /*
        $filter_data = $this->model_ie_cli_ie->get_manufacturer();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'manufacturer', 'manufacturer_id',
            [
                'manufacturer_id'   => 'manufacturer_id',
                'name'              => 'name',
                'image'             => 'image',
                'sort_order'        => 'text'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'manufacturer_description', 'manufacturer_id',
            [
                'manufacturer_id'   => 'manufacturer_id',
                'language_id'       => 'language_id',
                'name'              => 'name',
                'description'       => 'description',
                'meta_description'  => 'meta_description',
                'meta_keyword'      => 'meta_keyword',
                'meta_title'        => 'meta_title',
                'meta_h1'           => 'meta_h1'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'manufacturer_to_store', 'manufacturer_id',
            [
                'manufacturer_id'    => 'manufacturer_id',
                'store_id'      => 0
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'manufacturer_to_layout', 'manufacturer_id',
            [
                'manufacturer_id'    => 'manufacturer_id',
                'store_id'      => 0,
                'layout_id'     => 0
            ]
        );

        $this->model_ie_cli_ie->importManufacturerSeoUrl($filter_data, 'manufacturer_id', $languages);
        */

        foreach ($tables as $key => $table) {
            $fields = $this->model_ie_cli_ie->getColumnsTable($table);

            $table_data = $this->model_ie_cli_ie->getTableData($table, 'product_id');

            $this->model_ie_cli_ie->importTable($table_data, $table, $fields, $languages);
        }

        $products_data = $this->model_ie_cli_ie->getProducts();

        $this->model_ie_cli_ie->importSeoUrl($products_data, 'product_id', $languages);

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_attribute', 'product_id',
            [
                'product_id'        => 'product_id',
                'attribute_id'      => 'attribute_id',
                'language_id'       => 'language_id',
                'text'              => 'text'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_complementary', 'product_id',
            [
                'product_id'        => 'product_id',
                'complementary_id'  => 'complementary_id'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_image', 'product_id',
            [
                'product_image_id'  => 'product_image_id',
                'product_id'        => 'product_id',
                'image'             => 'image',
                'sort_order'        => 'sort_order'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_option', 'product_id',
            [
                'product_option_id'     => 'product_option_id',
                'product_id'            => 'product_id',
                'option_id'             => 'option_id',
                'value'                 => 'value',
                'required'              => 'required'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_option_value', 'product_id',
            [
                'product_option_value_id'   => 'product_option_value_id',
                'product_option_id'         => 'product_option_id',
                'product_id'                => 'product_id',
                'option_id'                 => 'option_id',
                'option_value_id'           => 'option_value_id',
                'quantity'                  => 'quantity',
                'subtract'                  => 'subtract',
                'price'                     => 'price',
                'price_prefix'              => 'price_prefix',
                'points'                    => 'points',
                'points_prefix'             => 'points_prefix',
                'weight'                    => 'weight',
                'weight_prefix'             => 'weight_prefix'
            ]
        );

        $product_option_data = $this->model_ie_cli_ie->get_product_option();

        $this->model_ie_cli_ie->addAdditionalTableData($product_option_data, 'option', 'option_id',
            [
                'option_id'     => 'option_id',
                'type'          => 'type',
                'sort_order'    => 'sort_order'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($product_option_data, 'option_description', 'option_id',
            [
                'option_id'     => 'option_id',
                'language_id'   => 'language_id',
                'name'          => 'name'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($product_option_data, 'option_value', 'option_id',
            [
                'option_value_id'   => 'option_value_id',
                'option_id'         => 'option_id',
                'image'             => 'image',
                'sort_order'        => 'sort_order'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($product_option_data, 'option_value_description', 'option_id',
            [
                'option_value_id'   => 'option_value_id',
                'language_id'       => 'language_id',
                'option_id'         => 'option_id',
                'name'              => 'name'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_related', 'product_id',
            [
                'product_id'    => 'product_id',
                'related_id'    => 'related_id'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_special', 'product_id',
            [
                'product_special_id'    => 'product_special_id',
                'product_id'            => 'product_id',
                'customer_group_id'     => 'customer_group_id',
                'priority'              => 'priority',
                'price'                 => 'price',
                'date_start'            => 'date_start',
                'date_end'              => 'date_end'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_to_category', 'product_id',
            [
                'product_id'    => 'product_id',
                'category_id'   => 'category_id',
                'main_category' => 'main_category'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_to_layout', 'product_id',
            [
                'product_id'    => 'product_id',
                'store_id'      => 0,
                'layout_id'     => 0
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_to_lookbook', 'product_id',
            [
                'lookbook_id'   => 'lookbook_id',
                'product_id'    => 'product_id'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($products_data, 'product_to_store', 'product_id',
            [
                'product_id'    => 'product_id',
                'store_id'      => 0
            ]
        );


        // Attribute Group
        $filter_data = $this->model_ie_cli_ie->get_attribute_group();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'attribute_group', 'attribute_group_id',
            [
                'attribute_group_id'    => 'attribute_group_id',
                'sort_order'            => 'sort_order'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'attribute_group_description', 'attribute_group_id',
            [
                'attribute_group_id'    => 'attribute_group_id',
                'language_id'           => 'language_id',
                'name'                  => 'name'
            ]
        );

        // Attribute
        $filter_data = $this->model_ie_cli_ie->get_product_attribute();

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'attribute', 'attribute_id',
            [

                'attribute_id'          => 'attribute_id',
                'attribute_group_id'    => 'attribute_group_id',
                'sort_order'            => 'sort_order'
            ]
        );

        $this->model_ie_cli_ie->addAdditionalTableData($filter_data, 'attribute_description', 'attribute_id',
            [

                'attribute_id'          => 'attribute_id',
                'language_id'           => 'language_id',
                'name'                  => 'name'
            ]
        );
    }
}
