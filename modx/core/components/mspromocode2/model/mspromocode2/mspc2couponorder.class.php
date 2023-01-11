<?php
class mspc2CouponOrder extends xPDOSimpleObject
{
    /**
     * @param null|bool|int $cacheFlag
     *
     * @return bool
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            // Get coupon object and minus count
            if ($coupon = $this->getOne('Coupon')) {
                $count = $coupon->get('count');
                if (is_numeric($count)) {
                    $coupon->set('count', --$count);
                    $coupon->save();
                }
            }
        }

        return parent::save($cacheFlag);
    }

    /**
     * @param array $ancestors
     *
     * @return bool
     */
    public function remove(array $ancestors = [])
    {
        // Get coupon object and plus count
        if ($coupon = $this->getOne('Coupon')) {
            $count = $coupon->get('count');
            if (is_numeric($count)) {
                $coupon->set('count', ++$count);
                $coupon->save();
            }
        }

        return parent::remove($ancestors);
    }
}