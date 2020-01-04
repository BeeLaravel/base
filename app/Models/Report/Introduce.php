<?php
namespace App\Models\Report;

class Introduce extends Model { // 需求
    protected $table = 'report_introduces';
    protected $fillable = [
        'corporation_id', // 企业标识
        'created_by', // 创建人
        'description', // 描述

        'date', // 日期
        'ten_type', // 旬
        'bespeak', // 预约数
        'visit', // 到诊数
        'achievement', // 总业绩
        'achievement_first', // 初诊业绩
    ];
    protected $appends = [
        'visit_percent', // 到诊率
        'achievement_other', // 复诊业绩
        'first_visit_price', // 初诊客单价
    ];
    static public $ten_types = [ // 旬类型
        'first' => '上旬',
        'middle' => '中旬',
        'last' => '下旬',
    ];

    // ### 属性
    public function getVisitPercentAttribute() { // 到诊率 = 到诊数 / 预约数
        return $this->visit ? ($this->bespeak ? round($this->visit/$this->bespeak*100, 2)."%": "100%") : 0;
    }
    public function getAchievementOtherAttribute() { // 复诊业绩 = 总业绩 - 初诊业绩
        return round($this->achievement - $this->achievement_first, 2);
    }
    public function getFirstVisitPriceAttribute() { // 初诊客单价 = 初诊业绩 / 到诊数
        return $this->achievement_first ? ($this->visit ? round($this->achievement_first/$this->visit, 2) : '∞') : 0;
    }
    public function getTenTypeNameAttribute() { // 旬类型
        return $this->ten_type ? self::$ten_types[$this->ten_type] : '';
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