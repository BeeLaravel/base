<?php
// ##  Structure
use App\Models\Structure\Category;
use App\Models\Structure\CategoryItem;

function category($category_id, $parent_id=0) {
	$category = Category::find($category_id);

	$data = $category->items
		->where('parent_id', $parent_id)
		->pluck('title', 'id');

	return $data;
}
function cate($category_id) {
	if ( !is_integer($category_id) ) $category_id = Category::where('slug', $category_id)->value('id');

	$data = CategoryItem::where('category_id', $category_id)
		->all();

	return $data;
}
function breadcrumbs($data) {
	$return_data = [];
	$flag = 0;

	while ( $data ) {
		if ( $flag ) {
			$link_html = '<a href="?parent_id=' . $data->id . '">' . $data->title . '</a>';
		} else {
			$flag = 1;

			$link_html = $data->title;
		}

		array_unshift($return_data, $link_html);

		$data = $data->parent;
	}

	if ( $flag ) {
		$link_html = '<a href="?parent_id=0">根</a>';
	} else {
		$link_html = '根';
	}

	array_unshift($return_data, $link_html);

	return implode(' > ', $return_data);
}