<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    // 当前场景名
    protected string $scene = '';

    // 场景数组
    protected array $scenes = [];

    // 全量规则（各子类必须定义）
    abstract public function rules(): array;

    // 场景设置（控制器调用）
    public function scene(string $scene): self
    {
        $this->scene = $scene;
        return $this;
    }

    // 返回当前使用的规则
    public function getRules(): array
    {
        // 如果没设置场景：使用默认全量规则
        if (!$this->scene || !isset($this->scenes[$this->scene])) {
            return $this->rules();
        }

        // 根据场景选择部分字段
        $allowFields = $this->scenes[$this->scene];

        return array_filter(
            $this->rules(),
            fn($key) => in_array($key, $allowFields),
            ARRAY_FILTER_USE_KEY
        );
    }

    // 重写 FormRequest 默认的 rules 方法
    public function validationData(): array
    {
        return $this->all();
    }

    public function validator($factory)
    {
        return $factory->make(
            $this->validationData(),
            $this->getRules(),
            $this->messages(),
            $this->attributes()
        );
    }

    // 统一错误提示
    public function messages(): array
    {
        return [
            'required' => ':attribute '.__('params.required'),
            'max'      => ':attribute '.__('params.max'),
            'min'      => ':attribute '.__('params.min'),
            'integer'  => ':attribute '.__('params.integer'),
        ];
    }

    // 字段中文名
    public function attributes(): array
    {
        return [];
    }
}
