<?php
namespace App\Http\Controllers\Admin\Application;

use App\Models\Category\Category;
use App\Models\Category\Tag;

use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Admin\Controller {
	protected $model;

	public function show(int $id) {}
	public function create(Request $request) {
        $categories = Category::where('created_by', auth('admin')->user()->id)
        	->whereIn('type', ['commons', 'pictures'])
        	->get();
        $categories = level_array($categories);
        $categories = plain_array($categories, 0, '==');

        $tags = Tag::where('created_by', auth('admin')->user()->id)
        	->whereIn('type', ['commons', 'pictures'])
        	->pluck('title');
        $tags = json_encode($tags);

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('categories', 'tags')));
    }
	public function store() {
        $this->model->fill(array_merge($this->request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]));
        $result = $this->model->save();

        if ( $result ) {
            $tags = $this->request->input('tags', []);
            $exist_tags = Tag::where('created_by', auth('admin')->user()->id)
                ->whereIn('type', ['commons', 'pictures'])
                ->whereIn('title', $tags)
                ->pluck('title', 'id')->toArray();
            $not_exist_tags = array_diff($tags, $exist_tags);

            if ( $not_exist_tags ) {
                $temp = [];
                foreach ( $not_exist_tags as $tag ) {
                    $temp[] = [
                        'title' => $tag,
                        'slug' => str_slug($tag),
                        'type' => 'commons',
                        'description' => $tag,
                        'created_by' => auth('admin')->user()->id,
                    ];
                }
                $create_result = Tag::insert($temp);
            }

            if ( $tags ) {
                $tags = Tag::where('created_by', auth('admin')->user()->id)
                    ->whereIn('type', ['commons', 'pictures'])
                    ->whereIn('title', $tags)
                    ->pluck('id')->toArray();
                $tags = array_combine($tags, array_fill(0, count($tags), ['table' => 'pictures']));
                $result->tags()->attach($tags);
            }
        }

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']);
        } else {
            flash('操作失败', 'error');

            return back(); // 继续
        }
    }
    public function edit(int $id) {
        $categories = Category::where('created_by', auth('admin')->user()->id)
        	->where('type', 'commons')
        	->get();
        $categories = level_array($categories);
        $categories = plain_array($categories, 0, '==');

        $tags = Tag::where('created_by', auth('admin')->user()->id)
        	->whereIn('type', ['commons', 'pictures'])
        	->pluck('title');
        $tags = json_encode($tags);

        $item = $this->model->with('tags')->find($id);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('categories', 'tags', 'item')), [
            'title' => $item->title . " | " . $this->baseInfo['title'],
        ]);
    }
    public function update(int $id) {
        $item = $this->model->find($id);

        $result = $item->update($this->request->all());

        if ( $result ) {
            $tags = $this->request->input('tags', []);
            $exist_tags = Tag::where('created_by', auth('admin')->user()->id)
                ->whereIn('type', ['commons', 'pictures'])
                ->whereIn('title', $tags)
                ->pluck('title', 'id')->toArray();
            $not_exist_tags = array_diff($tags, $exist_tags);

            if ( $not_exist_tags ) {
                $temp = [];
                foreach ( $not_exist_tags as $tag ) {
                    $temp[] = [
                        'title' => $tag,
                        'slug' => str_slug($tag),
                        'type' => 'commons',
                        'description' => $tag,
                        'created_by' => auth('admin')->user()->id,
                    ];
                }
                $create_result = Tag::insert($temp);
            }

            if ( $tags ) {
                $tags = Tag::where('created_by', auth('admin')->user()->id)
                    ->whereIn('type', ['commons', 'pictures'])
                    ->whereIn('title', $tags)
                    ->pluck('id')->toArray();
                $tags = array_combine($tags, array_fill(0, count($tags), ['table' => 'pictures']));
                $item->tags()->detach();
                $item->tags()->attach($tags);
            }
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

                return redirect($this->baseInfo['link']); // 列表
            } else {
                flash('操作失败', 'error');
                return back()->withErrors();
            }
        }
    }
    public function destroy(Request $request, int $id) {
        $result = ThisModel::destroy($id);

        if ( $result ) {
            flash('删除成功', 'success');
        } else {
            flash('删除失败', 'error');
        }

        return redirect($this->baseInfo['link']);
    }
}
