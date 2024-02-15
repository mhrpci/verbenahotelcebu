<?php

namespace Botble\Hotel\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface BookingInterface extends RepositoryInterface
{
    /**
     * @param string[] $select
     * @param array $with
     * @return mixed
     */
    public function getPendingBookings($select = ['*'], array $with = []);

    /**
     * @return mixed
     */
    public function countPendingBookings();
}
