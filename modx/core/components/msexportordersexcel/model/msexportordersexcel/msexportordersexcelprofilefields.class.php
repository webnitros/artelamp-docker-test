<?php

class msExportOrdersExcelProfileFields extends xPDOObject
{
    public function save($cacheFlag = null)
    {
        $new = $this->isNew();
        if ($new and empty($rank)) {

            $rank = 0;
            $q = $this->xpdo->newQuery($this->_class);
            $q->select('rank');
            $q->sortby('rank', 'ASC');
            $q->where(array(
                'profile_id' => $this->profile_id,
            ));
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $rank = $row['rank'];
                }
            }
            $rank = $rank + 1;
            #$rank = $this->xpdo->getCount($this->_class, array('profile_id' => $this->profile_id));
            $this->set('rank', $rank);
        }
        return parent::save($cacheFlag);
    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        $width = $this->get('width');
        $v = empty($width) ? null : (int)$width;
        return $v;
    }

    /**
     * @return int|null
     */
    public function getAlignmentVertical()
    {
        $alignment = $this->get('alignment_vertical');
        $v = empty($alignment) ? null : (string)$alignment;
        return $v;
    }

    /**
     * @return int|null
     */
    public function getAlignmentHorizontal()
    {
        $alignment = $this->get('alignment_horizontal');
        $v = empty($alignment) ? null : (string)$alignment;
        return $v;
    }

    /**
     * Вернет класс обработчика
     * @return bool|mixed
     */
    public function getHandler()
    {
        $handler = $this->get('handler');
        return empty($handler) ? false : $handler;
    }


    public function remove(array $ancestors = array())
    {
        $result = false;
        $pk = $this->getPrimaryKey();
        $delete = $this->xpdo->newQuery($this->_class);
        $delete->command('DELETE');
        $delete->where($pk);
        $stmt = $delete->prepare();
        if (is_object($stmt)) {
            return $result = $stmt->execute();
        }
        return $result;
    }
}