<?php
class ControllerEICategory extends Controller {
	public function index() {
        $this->load->model('ie_cli/ie');

        $languages = $this->model_ie_cli_ie->compareLanguages();

        $tables = [
            'category',
            'category_description'
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

        $this->model_ie_cli_ie->clearCategoriesData();

        foreach ($tables as $key => $table) {
            $fields = $this->model_ie_cli_ie->getColumnsTable($table);

            $table_data = $this->model_ie_cli_ie->getTableData($table);

            $this->model_ie_cli_ie->importTable($table_data, $table, $fields, $languages);

            if (!$key) {
                $this->model_ie_cli_ie->createAdditionalTableData($table_data, 'category_to_layout',
                    [
                        'category_id'   => 'category_id',
                        'store_id'      => 0,
                        'layout_id'     => 0
                    ]
                );

                $this->model_ie_cli_ie->createAdditionalTableData($table_data, 'category_to_store',
                    [
                        'category_id'   => 'category_id',
                        'store_id'      => 0
                    ]
                );

                $this->model_ie_cli_ie->importSeoUrl($table_data, 'category_id', $languages);
            }
        }

        $this->load->model('catalog/category');

        $this->model_catalog_category->repairCategories();

        $this->cache->delete('category');
	}
}
