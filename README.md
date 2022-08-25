# Traverse Category
Get child from parent category and parent from child category

---
Every time I build another Laravel app where I need all hierarchical child category ids, I find myself re-using the same query over the years. This package allow you to accurately find child category ids of a parent category. This tool allow you to speed up the development progress.

## Installation

Installation is straightforward, setup is similar to every other Laravel Package.

####  Install via Composer

Begin by pulling in the package through Composer:

```
composer require sohel/traverse-category
```

## Usage

This package is easy to use. It provides a handful of helpful functions for navigation. This package is auto discovered. So you just need to install this package and use where you need.

#### [IMPORTANT] What this package does NOT do

This does NOT return all of the results in the model's table.

---
### Database Structure

Category table should be like following. <br/>
`category_id` and its `parent_id` column must be in the same table.

##### Example:

```
Schema::create('categories', function (Blueprint $table) {
	$table->id();
	$table->string('title');
	$table->bigInteger('parent_id')->nullable();
	.....
	.....
});
```
_Note: change table name and  column name as you wish._
### How This Tool Works

If you have more than 2 level of categories then you may need this.
##### Example:

```
Dress
  -->Men
	-->Shirt
	-->Pant
	-->Shoe
	.....
  -->Women
	-->T-Shirt
	-->Pant
	-->Shoe
	.....
  -->Kids
	-->Shirt
	-->Pant
	-->Shoe
	.....
```
Now if you need all hierarchical ids of `dress` category then ultimately you have to get all category ids under `dress`. 

We have a method called `getAllChildCategoryIds()` which provides your desired child ids along with that `parent_id`. You just need to call this method with some parameters. <br>
Parametes are: `table_name`, `primary_key_column_name`, `parent_id_column_name`, `parent_categroy_id`.
##### Example:

```
use Sohel\TraverseCategory\Facades\TraverseCategory;

class TestClass
{
	public function testFunction()
	{
		$categoy_ids = TraverseCategory::getAllChildCategoryIds('categories', 'id', 'parent_id', 3);

		return $category_ids; //sample output [3, 5, 7, 10, 21, 22, .....]
	}
}

```

This method will provide an array of ids. If nothing found then result will be an empty array.

---

We have another method `getDirectChildCategoryIds()` which will provide only the direct child ids. If you need direct child of `dress` category then this will return ids of `men`, `women` and `kids`. <br>
This method also receives parameter like above.
##### Example:

```
use Sohel\TraverseCategory\Facades\TraverseCategory;

class TestClass
{
	public function testFunction()
	{
		$categoy_ids = TraverseCategory::getDirectChildCategoryIds('categories', 'id', 'parent_id', 3);

		return $category_ids; //sample output [5, 7, 10]
	}
}

```

## Contact

I am always on Twitter, and it is a great way to communicate with me. [Check me out on Twitter](https://twitter.com/_sohel664).