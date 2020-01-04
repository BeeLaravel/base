<?php
namespace App\Models\Report;

class Contact extends Model { // 网电报表
    protected $table = 'report_contacts';
    protected $fillable = [
        'corporation_id', // 企业标识
        'created_by', // 用户标识
        'description', // 描述

        'date', // 日期
        'onduty', // 在班人数
        'callback', // 总回访量
        'callback_real', // 有效回访量
        'callback_old', // 老数据总回访量
        'callback_old_real', // 老数据有效回访量
        'visit', // 到诊数
        'previsit', // 预到诊数
        'money', // 到诊金额
    ];
    protected $appends = [
        'callback_avg', // 人均总访问量
        'callback_real_avg', // 人均有效访问量
        'callback_old_avg', // 老数据人均总访问量
        'callback_old_real_avg', // 老数据人均有效访问量
        'callback_old_percent', // 老数据总访问量占比
        'callback_old_real_percent', // 老数据有效访问量占比
    ];

    // ### 属性
    public function getCallbackAvgAttribute() { // 人均总访问量
        return $this->onduty ? round($this->callback/$this->onduty, 2) : 0;
    }
    public function getCallbackRealAvgAttribute() { // 人均有效访问量
        return $this->onduty ? round($this->callback_real/$this->onduty, 2) : 0;
    }
    public function getCallbackOldAvgAttribute() { // 老数据人均总访问量
        return $this->onduty ? round($this->callback_old/$this->onduty, 2) : 0;
    }
    public function getCallbackOldRealAvgAttribute() { // 老数据人均有效访问量
        return $this->onduty ? round($this->callback_old_real/$this->onduty, 2) : 0;
    }
    public function getCallbackOldPercentAttribute() { // 老数据总访问量占比
        return $this->callback_old ? ($this->callback ? round($this->callback_old/$this->callback*100, 2)."%" : "100%") : 0;
    }
    public function getCallbackOldRealPercentAttribute() { // 老数据有效访问量占比
        return $this->callback_old_real ? ($this->callback_real ? round($this->callback_old_real/$this->callback_real*100, 2)."%" : "100%") : 0;
    }
    // ### 关联
    // # 包含
    public function corporation() { // 企业
        return $this->belongsTo('App\Models\Architecture\Corporation');
    }
    public function creater() { // 提交人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
    // ### 按钮
    public function getActionButtons($actionModel, $id=null, $showText=true, $showView=false) { // 所有按钮
        $user = auth('admin')->user();

        if ( ($user->id == $this->user_id)|| hasRole($user, ['administrator']) ) {
            return $this->editButton($actionModel, $id, $showText, $showView)
                . $this->deleteButton($actionModel, $id, $showText, $showView);
        } else {
            return '';
        }
    }
}