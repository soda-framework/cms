<?php

namespace Soda\Cms\Models\Traits;

use Soda\Cms\Models\ModelBuilder;

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

    public function getRelatedField() {
        return str_singular($this->getTable()) . '_id';
    }
}
