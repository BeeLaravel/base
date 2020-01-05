<?php
namespace App\Http\Controllers\Admin\Application;

use App\Models\Category\Category;
use App\Models\Category\Tag;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

class Controller extends \App\Http\Controllers\Admin\Controller {
	protected $model;

    public function __construct() {
        $this->slug = $this->baseInfo['slug'];
        $this->link = $this->baseInfo['link'];
        $this->view_path = $this->baseInfo['view_path'];
    }
	public function show(int $id) {}
	public function create($data=[]) {
        $types = get_types($this->slug);
        $categories = Category::getCategories($this->slug);
        $tags = Tag::getTags($this->slug);

        return view($this->view_path.'create', array_merge($this->baseInfo, compact('types', 'categories', 'tags'), $data));
    }
	public function store($data=[]) {
        $data = array_merge(request()->all(), $data);

        $this->model->fill(array_merge($data, [
            'created_by' => auth('admin')->user()->id,
        ]));
        $result = $this->model->save();

        if ( $result && $data['tags'] ) {
            $tags = Tag::setTags($this->slug, $data['tags']);
            $this->model->tags()->attach($tags);
        }

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']);
        } else {
            flash('操作失败', 'error');

            return back(); // 继续
        }
    }
    public function edit(int $id, $data=[]) {
        $types = get_types($this->slug);
        $categories = Category::getCategories($this->slug);
        $tags = Tag::getTags($this->slug);
        $item = $this->model->with('tags')->find($id);

        return view($this->view_path.'edit', array_merge($this->baseInfo, compact('types', 'categories', 'tags', 'item')), $data, [
            'title' => $item->title . " | " . $this->baseInfo['title'],
        ]);
    }
    public function update(int $id, $data=[]) {
        $item = $this->model->find($id);
        $data = array_merge(request()->all(), $data);
        $result = $item->update($data);

        if ( $result && ($data['tags']??[]) ) {
            $tags = Tag::setTags($this->slug, $data['tags']);
            $item->tags()->detach();
            $item->tags()->attach($tags);
        }

        if ( request()->ajax() ) {
            if ( $result ) {
                return [
                    'status' => 0,
                    'message' => 'success',
                ];
            } else {
                return [
                    'status' => 1,
                    'message' => 'error',
                ];
            }
        } else {
            if ( $result ) {
                flash('操作成功', 'success');

                return redirect($this->link); // 列表
            } else {
                flash('操作失败', 'error');
                return back()->withErrors();
            }
        }
    }
    public function destroy(Request $request, int $id) {
        $result = $this->model->destroy($id);

        if ( $result ) {
            flash('删除成功', 'success');
        } else {
            flash('删除失败', 'error');
        }

        return redirect($this->link);
    }
}
