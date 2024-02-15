<?php

namespace Botble\Hotel\Repositories\Eloquent;

use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC')
            ->get();

        $this->resetModel();

        return $data;
    }
}
