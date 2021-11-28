<?php

namespace App\Action;

use App\Repositories\ProductsRepository;
use App\Traits\GeneralTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty as View;

class Prices
{

    use GeneralTrait;

    protected $products;
    protected $view;

    public function __construct(
        ProductsRepository $products,
        View $view
    ) {
        $this->products = $products;
        $this->view = $view;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $prices = $this->products->readAll([
            'select' => ['description', 'price']
        ]);

        $prices = $this->toArray($prices);

        $this->view->assign('prices', $prices);
        $this->view->display('public/pages/prices.tpl');

        return $response;
    }
}
