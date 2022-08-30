<?php

namespace Sohel\TraverseCategory;

use Illuminate\Support\Facades\DB;

class TraverseCategory
{
    /**
     * Get all hierarchical child category ids.
     *
     * @param string $table_name (table_name)
     * @param string $primary_col (primary_key_column_name)
     * @param string $parent_id (parent_id_column_name)
     * @param int    $category_id (category_id)
     * @param string $db_connection (db connection name) optional
     * 
     * @return array
     */
    public function getAllChildCategoryIds($table_name, $primary_col, $parent_id, int $category_id, $db_connection = 'mysql')
    {
        $sql = "SELECT {$primary_col} FROM
                    (SELECT * FROM {$table_name} ORDER BY {$parent_id}, {$primary_col}) cat_sorted,
                    (SELECT @pv := {$category_id}) initialisation
                    WHERE find_in_set({$parent_id}, @pv)
                    AND LENGTH (@pv := concat(@pv, ',', {$primary_col}))";

        $cat_ids = DB::connection($db_connection)->select(DB::raw($sql));
        $cat_ids = collect($cat_ids)->pluck($primary_col)->all();
        array_unshift($cat_ids, $category_id);
        return $cat_ids;
    }

    /**
     * Get direct child category ids.
     *
     * @param string $table_name (table_name)
     * @param string $primary_col (primary_key_column_name)
     * @param string $parent_id (parent_id_column_name)
     * @param int    $category_id (category_id)
     * @param string $db_connection (db connection name) optional
     * 
     * @return array
     */
    public function getDirectChildCategoryIds($table_name, $primary_col, $parent_id, int $category_id, $db_connection = 'mysql')
    {
        $sql = "SELECT {$primary_col}
                    FROM {$table_name}
                    WHERE {$parent_id}={$category_id}";

        $cat_ids = DB::connection($db_connection)->select(DB::raw($sql));
        $cat_ids = collect($cat_ids)->pluck($primary_col)->all();

        return $cat_ids;
    }

    /**
     * Get all hierarchical parent category ids.
     *
     * @param string $table_name (table_name)
     * @param string $primary_col (primary_key_column_name)
     * @param string $parent_id (parent_id_column_name)
     * @param int    $category_id (category_id)
     * @param string $db_connection (db connection name) optional
     * 
     * @return array
     */
    public function getAllParentCategoryIds($table_name, $primary_col, $parent_id, int $category_id, $db_connection = 'mysql')
    {
        $cat_ids = [$category_id];
        while ($id = DB::connection($db_connection)->select(DB::raw(
            "SELECT {$parent_id}
            FROM {$table_name}
            WHERE {$primary_col}={$category_id}"))[0]->$parent_id) {

            array_push($cat_ids, $id);
            $category_id = $id;
        }
        
        return count($cat_ids) > 1 ? $cat_ids : [];
    }
}
