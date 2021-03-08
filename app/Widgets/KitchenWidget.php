<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Sale;
use App\SaleDetail;

class KitchenWidget extends AbstractWidget
{
    public $reloadTimeout = 3;
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $sale = Sale::where('sale_status', 'unpaid')->where(function ($sale) {
            $sale->whereHas('saleDetails', function ($sale) {
                return $sale->where('status', 'confirm');
            });
        })->get();

        $saleDetail = SaleDetail::where(function ($query) {
            $query->where('status', 'confirm')
                ->orWhere('status', 'waiting');
        })->where(function ($saleDetail) {
            $saleDetail->whereHas('sale', function ($saleDetail) {
                return $saleDetail->where('sale_status', 'unpaid');
            });
        })->get();

        // return view('widgets.kitchen_widget', [
        //     'config' => $this->config,
        // ]);
        return view('widgets.kitchen_widget',  ['sale' => $sale, 'saleDetails' => $saleDetail,  'config' => $this->config,]);
    }
}
