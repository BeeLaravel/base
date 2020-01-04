<?php
namespace App\Models\Report;

class Platform extends Model { // 电商报表
    protected $table = 'report_platforms';
    protected $fillable = [
        'corporation_id',
        'created_by',
        'type',
        'description',

        'date',
        'consumption',
        'consumption_real',
        'dialog_useless',
        'dialog_useful',
        'bespeak',
        'visit',
        'achievement',
        'achievement_first',
    ];
    static public $types = [
        'xinyang' => '新氧',
        'gengmei' => '更美',
        'meituan' => '美团',
        'weibo' => '微博',
        'yuemei' => '悦美',
        'meidaila' => '美黛拉',
        'helijia' => '河狸家',
        'other' => '其它',
    ];
    // protected $attributes = [
    //     'useful_dialog_cost',
    //     'bespeak_cost',
    //     'visit_cost',
    // ];
    protected $appends = [
        'dialog_useful_cost',
        'bespeak_cost',
        'visit_cost',
        'type_name',
        'dialog_useless_percent',
        'achievement_other',
    ];

    // ### 属性
    public function getTypeNameAttribute() {
        return $this->type ? self::$types[$this->type] : "汇总";
    }
    public function getDialogUsefulCostAttribute() {
        return $this->dialog_useful ? round($this->consumption_real / $this->dialog_useful, 2) : 0;
    }
    public function getBespeakCostAttribute() {
        return $this->bespeak ? round($this->consumption_real / $this->bespeak, 2) : 0;
    }
    public function getVisitCostAttribute() {
        return $this->visit ? round($this->consumption_real / $this->visit, 2) : 0;
    }
    public function getAchievementOtherAttribute() {
        return round($this->achievement - $this->achievement_first, 2);
    }
    public function getDialogUselessPercentAttribute() {
        return $this->dialog_useless ? round($this->dialog_useless/($this->dialog_useless+$this->dialog_useful)*100, 2)."%" : 0;
    }
    // ### 关联
    // # 包含
    public function corporation() { // 公司
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