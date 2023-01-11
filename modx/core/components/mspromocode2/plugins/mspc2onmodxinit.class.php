<?php

/**
 *
 */
class mspc2OnMODXInit extends mspc2Plugin
{
    public function run()
    {
        //
        $this->map([
            'msCategory' => [
                'composites' => [
                    'Coupons' => [
                        'class' => 'mspc2Join',
                        'local' => 'id',
                        'foreign' => 'resource',
                        'cardinality' => 'many',
                        'owner' => 'local',
                        'criteria' => [
                            'foreign' => [
                                'type' => 'category',
                            ],
                        ],
                    ],
                ],
            ],
            'msProduct' => [
                'composites' => [
                    'Coupons' => [
                        'class' => 'mspc2Join',
                        'local' => 'id',
                        'foreign' => 'resource',
                        'cardinality' => 'many',
                        'owner' => 'local',
                        'criteria' => [
                            'foreign' => [
                                'type' => 'product',
                            ],
                        ],
                    ],
                ],
            ],
            'msOrder' => [
                'composites' => [
                    'Coupon' => [
                        'class' => 'mspc2CouponOrder',
                        'local' => 'id',
                        'foreign' => 'order',
                        'cardinality' => 'one',
                        'owner' => 'local',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Расширяет нативный MAP массив
     *
     * @param array $map
     *
     * @return bool
     */
    public function map(array $map = [])
    {
        foreach ($map as $class => $data) {
            $this->modx->loadClass($class);

            foreach ($data as $tmp => $fields) {
                if ($tmp == 'fields') {
                    foreach ($fields as $field => $value) {
                        foreach (['fields', 'fieldMeta', 'indexes'] as $key) {
                            if (isset($data[$key][$field])) {
                                $this->modx->map[$class][$key][$field] = $data[$key][$field];
                            }
                        }
                    }
                } elseif ($tmp == 'composites' || $tmp == 'aggregates') {
                    foreach ($fields as $alias => $relation) {
                        if (!isset($this->modx->map[$class][$tmp][$alias])) {
                            $this->modx->map[$class][$tmp][$alias] = $relation;
                        }
                    }
                }
            }
        }

        return true;
    }
}