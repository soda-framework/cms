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

    public function setModel(ModelBuilder $model) {
        $this->dynamicModel = $model;

        return $this;
    }

    protected function loadDynamicModel() {
        if (!$this->type) {
            $this->load('type');
        }

        $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->where($this->getRelatedField(), $this->id)->first();

        if (!$model) {
            $model = ModelBuilder::fromTable('soda_' . $this->type->identifier)->newInstance();
        }

        return $this->setModel($model);
    }

    public function getRelatedField() {
        return str_singular($this->getTable()) . '_id';
    }
}
