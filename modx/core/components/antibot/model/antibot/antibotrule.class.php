<?php

class antiBotRule extends xPDOSimpleObject
{

    /**
     * @param xPDOQuery $q
     * @return xPDOQuery
     */
    public function getCriteria($q = null)
    {
        $method = explode(',', $this->get('hit_method'));
        $core_response = explode(',', $this->get('core_response'));

        $hour = $this->get('hour');
        $hits_per_minute = $this->get('hits_per_minute');


        $q = ($q instanceof xPDOQuery) ? $q : $this->xpdo->newQuery('antiBotHits');

        $q->select('GROUP_CONCAT(DISTINCT antiBotHits.code_response) as codes_response, GROUP_CONCAT(DISTINCT antiBotHits.method) as methods, antiBotHits.guest_id as guest,antiBotHits.ip,antiBotHits.user_agent,COUNT(antiBotHits.id) as total,Guest.id,Guest.user_id');

        $criteria = [
            'Guest.search_system' => 0, // Автоматически определяет что это поисковая система
            'Guest.happy' => 0, // не проверять благополучных
        ];


        if (!empty($hour)) {
            $today = date('Y-m-d H:i:s', strtotime('-' . $hour . ' hour', time()));
            #$today = strtotime(date('Y-m-d H:i:s', strtotime('-' . $hour . ' hour', time())));
            $criteria['antiBotHits.createdon:>'] = $today;
        }


        if (!empty($method)) {
            $criteria['antiBotHits.method:IN'] = $method;
        }

        if (!empty($core_response)) {
            $criteria['antiBotHits.code_response:IN'] = $core_response;
        }

        $q->where($criteria);
        $q->groupby('antiBotHits.guest_id');
        $q->having('total >= ' . $hits_per_minute);
        $q->leftJoin('antiBotGuest', 'Guest', 'Guest.id = antiBotHits.guest_id');
        return $q;
    }

    /**
     * @return array
     */
    public function getCollectionIp()
    {
        $q = $this->getCriteria();
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
}
