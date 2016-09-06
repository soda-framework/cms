<?php

namespace Soda\Cms\Models\Traits;

trait HasDynamicModelTrait {
    protected $dynamicModel;

    public function model() {
        if (!$this->dynamicModel) {
            $this->loadDynamicModel();
        }

        return $this->dynamicModel;
    }

    public function setModel($model) {
        $this->dynamicModel = $model;

        return $this;
    }

    public function getRelatedField() {
        return str_singular($this->getTable()) . '_id';
    }
}
