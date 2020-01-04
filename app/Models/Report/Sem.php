<?php
namespace App\Models\Report;

class Sem extends Model { // 竞价报表
    protected $table = 'report_sems';
    protected $fillable = [
        'corporation_id', // 企业标识
        'created_by', // 创建人
        'description', // 描述

        'date', // 日期
        'consumption', // 消费
        'consumption_real', // 实际消费
        'dialog_useful', // 有效对话
        'dialog_useless', // 无效对话
        'bespeak', // 预约数
        'visit', // 到诊数
    ];
    protected $appends = [
        // 'dialag', // 对话
        'dialog_useful_cost', // 有效对话成本
        // 'dialog_useful_percent', // 有效对话率
        'dialog_useless_percent', // 无效对话率
        'bespeak_cost', // 预约成本
        'bespeak_percent', // 预约率
        'visit_cost', // 到诊成本
        'visit_percent', // 到诊率
    ];

    // ### 属性
    public function getDialogAttribute() { // 对话 = 有效对话 + 无效对话
        return ($this->dialog_useless + $this->dialog_useful);
    }
    public function getDialogUsefulCostAttribute() { // 对话成本 = 实际消费 / 有效对话
        return $this->dialog_useful ? round($this->consumption_real / $this->dialog_useful, 2) : 0;
    }
    public function getDialogUsefulPercentAttribute() { // 有效对话率 = 有效对话 / (有效对话 + 无效对话)
        return $this->dialog_useful ? round($this->dialog_useful/($this->dialog_useless+$this->dialog_useful)*100, 2)."%" : 0;
    }
    public function getDialogUselessPercentAttribute() { // 无效对话率 = 无效对话 / (有效对话 + 无效对话)
        return $this->dialog_useless ? round($this->dialog_useless/($this->dialog_useless+$this->dialog_useful)*100, 2)."%" : 0;
    }
    public function getBespeakCostAttribute() { // 预约成本 = 实际消费 / 预约数
        return $this->bespeak ? round($this->consumption_real / $this->bespeak, 2) : 0;
    }
    public function getBespeakPercentAttribute() { // 预约率 = 预约数 / 有效对话
        return $this->bespeak ? ($this->bespeak ? round($this->bespeak/$this->dialog_useful*100, 2)."%" : '100%') : 0;
    }
    public function getVisitCostAttribute() { // 到诊成本 = 实际消费 / 到诊数
        return $this->visit ? round($this->consumption_real / $this->visit, 2) : 0;
    }
    public function getVisitPercentAttribute() { // 到诊率 = 到诊数 / 预约数
        return $this->visit ? ($this->bespeak ? round($this->visit/$this->bespeak*100, 2)."%": "100%") : 0;
    }
    // ### 关联
    // # 包含
    public function corporation() { // 医院
        return $this->belongsTo('App\Models\Architecture\Corporation');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
    // ### 按钮
    public function getActionButtons($actionModel, $id=null, $showText=true, $showView=false) { // 所有按钮
        $user = auth('admin')->user();

        if ( ($user->id == $this->created_by)|| hasRole($user, ['administrator']) ) {
            return $this->editButton($actionModel, $id, $showText, $showView)
                . $this->deleteButton($actionModel, $id, $showText, $showView);
        } else {
            return '';
        }
    }
}
