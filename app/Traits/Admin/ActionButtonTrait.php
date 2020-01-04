<?php
namespace App\Traits\Admin;

trait ActionButtonTrait { // 列表记录操作按钮
    public function viewButton($actionModel, $id=null, $showText=true) { // 删除
        if ( !empty($id) ) $this->id = $id;

        // if ( auth('admin')->user()->can("{$actionModel}.show") ) {
            $button = "";

            $button .= "<a href='" . url('admin') . '/' . $actionModel . '/' . $this->id . "' title='详情'>";
            $button .= "<button type='button' class='btn btn-success btn-xs'>";
            $button .= "<i class='fa fa-eye'>" . ($showText ? " 详情" : " ") . "</i>";
            $button .= "</button>";
            $button .= "</a> ";

            return $button;
        // } else {
            // return '';
        // }
    }
    public function editButton($actionModel, $id=null, $showText=true) { // 编辑
        if ( !empty($id) ) $this->id = $id;

        // if ( auth('admin')->user()->can("{$actionModel}.edit") ) {
            $button = '';

            $button .= "<a href='".url('admin').'/'.$actionModel.'/'.$this->id."/edit' title='编辑'>";
            $button .= "<button type='button' class='btn btn-success btn-xs'>";
            $button .= "<i class='fa fa-edit'>" . ($showText ? " 编辑" : " ") . "</i>";
            $button .= "</button>";
            $button .= "</a> ";

            return $button;
        // } else {
            // return '';
        // }
    }
    public function deleteButton($actionModel, $id=null, $showText=true) { // 删除
        if ( !empty($id) ) $this->id = $id;

        // if ( auth('admin')->user()->can("{$actionModel}.delete") ) {
            $button = "";

            $button .= "<a href='javascript: void(0);' data-id='" . $this->id . "' class='destroy' title='删除'>";
            $button .= "<button type='button' class='btn btn-success btn-xs'>";
            $button .= "<i class='fa fa-trash'>" . ($showText ? " 删除" : "") . "</i>";
            $button .= "<form action='".url('admin/'.$actionModel.'/'.$this->id)."' method='POST' name='delete_item_" . $this->id . "' style='display: none;'>";
            $button .= method_field('DELETE').csrf_field();
            $button .= "</form>";
            $button .= "</button>";
            $button .= "</a> ";

            return $button;
        // } else {
            // return '';
        // }
    }

    public function getActionButtons($actionModel, $id=null, $showText=true, $showView=false) { // 所有按钮
        return ($showView ? $this->viewButton($actionModel, $id, $showText) : "")
            . $this->editButton($actionModel, $id, $showText)
            . $this->deleteButton($actionModel, $id, $showText);
    }
}