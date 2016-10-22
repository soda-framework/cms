<?php

namespace Soda\Cms\Http\Controllers\Api;

class SortController extends ApiController
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function sort(Request $request)
    {
        $sortableEntities = app('config')->get('soda.sortable.entities', []);
        $validator = $this->getValidator($sortableEntities, $request);

        if (!$validator->passes()) {
            return $this->respondInvalid(null, [
                'errors' => $validator->errors(),
            ]);
        }

        /** @var Model|bool $entityClass */
        list($entityClass, $relation) = $this->getEntityInfo($sortableEntities, (string)$request->input('entityName'));
        $method = $request->input('type');

        if (!$relation) {
            /** @var SortableTrait $entity */
            $entity = app($entityClass)->find($request->input('id'));
            $postionEntity = app($entityClass)->find($request->input('positionEntityId'));
            $entity->$method($postionEntity);
        } else {
            $parentEntity = app($entityClass)->find($request->input('parentId'));
            $entity = $parentEntity->$relation()->find($request->input('id'));
            $postionEntity = $parentEntity->$relation()->find($request->input('positionEntityId'));
            $parentEntity->$relation()->$method($entity, $postionEntity);
        }

        return ['success' => true];
    }

    /**
     * @param array   $sortableEntities
     * @param Request $request
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidator($sortableEntities, $request)
    {
        /** @var \Illuminate\Validation\Factory $validator */
        $validator = app('validator');

        $rules = [
            'type'             => ['required', 'in:moveAfter,moveBefore'],
            'entityName'       => ['required', 'in:'.implode(',', array_keys($sortableEntities))],
            'id'               => 'required',
            'positionEntityId' => 'required',
        ];

        /** @var Model|bool $entityClass */
        list($entityClass, $relation) = $this->getEntityInfo($sortableEntities, (string)$request->input('entityName'));

        if (!class_exists($entityClass)) {
            $rules['entityClass'] = 'required'; // fake rule for not exist field
            return $validator->make($request->all(), $rules);
        }

        $tableName = with(new $entityClass())->getTable();
        $primaryKey = with(new $entityClass())->getKeyName();

        if (!$relation) {
            $rules['id'] .= '|exists:'.$tableName.','.$primaryKey;
            $rules['positionEntityId'] .= '|exists:'.$tableName.','.$primaryKey;
        } else {
            /** @var BelongsToSortedMany $relationObject */
            $relationObject = with(new $entityClass())->$relation();
            $pivotTable = $relationObject->getTable();

            $rules['parentId'] = 'required|exists:'.$tableName.','.$primaryKey;
            $rules['id'] .= '|exists:'.$pivotTable.','.$relationObject->getOtherKey().','.$relationObject->getForeignKey().','.$request->input('parentId');
            $rules['positionEntityId'] .= '|exists:'.$pivotTable.','.$relationObject->getOtherKey().','.$relationObject->getForeignKey().','.$request->input('parentId');
        }

        return $validator->make($request->all(), $rules);
    }

    /**
     * @param array  $sortableEntities
     * @param string $entityName
     *
     * @return array
     */
    protected function getEntityInfo($sortableEntities, $entityName)
    {
        $relation = false;

        $entityConfig = $entityName ? array_get($sortableEntities, $entityName, false) : false;

        if (is_array($entityConfig)) {
            $entityClass = $entityConfig['entity'];
            $relation = !empty($entityConfig['relation']) ? $entityConfig['relation'] : false;
        } else {
            $entityClass = $entityConfig;
        }

        return [$entityClass, $relation];
    }
}
