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
     * @param int $category_id (category_id)
     * 
     * @return array|null
     */
    public function getAllChildCategoryIds($table_name, $primary_col, $parent_id, int $category_id)
    {
        $sql = "SELECT {$primary_col} FROM
                    (SELECT * FROM {$table_name} ORDER BY {$parent_id}, {$primary_col}) cat_sorted,
                    (SELECT @pv := {$category_id}) initialisation
                    WHERE find_in_set({$parent_id}, @pv)
                    AND LENGTH (@pv := concat(@pv, ',', {$primary_col}))";

        $cat_ids = DB::select(DB::raw($sql));
        $cat_ids = collect($cat_ids)->pluck('id')->all();
        array_unshift($cat_ids, $category_id);
        return $cat_ids;
    }

    /**
     * Get direct child category ids.
     *
     * @param string $table_name (table_name)
     * @param string $primary_col (primary_key_column_name)
     * @param string $parent_id (parent_id_column_name)
     * @param int $category_id (category_id)
     * 
     * @return array|null
     */
    public function getDirectChildCategoryIds($table_name, $primary_col, $parent_id, int $category_id)
    {
        $sql = "SELECT {$primary_col}
                    FROM {$table_name}
                    WHERE {$parent_id}={$category_id}";

        $cat_ids = DB::select(DB::raw($sql));
        $cat_ids = collect($cat_ids)->pluck('id')->all();

        return $cat_ids;
    }
}
