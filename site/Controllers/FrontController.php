<?php

namespace Controllers;

use Data\Session;
use Models\CategoriesModel;
use Models\MenuModel;
use Models\PageModel;
use Models\ProductsModel;
use Tools\Functions;
use Views\FooterView;
use Views\HeaderView;
use Views\MenuView;
use Views\PageView;
use Views\ProductsView;
use Views\SidebarView;

class FrontController extends BaseController {
  protected $templateParser;
  protected $data;

  public function __construct($templateParser) {
    global $session;
//    $this->data['session'] = &$session;
    $this->data['session'] = $_SESSION;
//    $this->data['session']->products = [];
    $this->templateParser = $templateParser;
    $this->data['home'] = HOME;
    $this->data['data'] = DATA;
    $this->data['menu_links'] = MenuModel::getAll();
    $this->data['categories'] = CategoriesModel::getAll();
  }

  public function actPage($page) {
    if ($page > 0) $this->data['menu_links'][$page - 1]['active'] = true;
    $this->data['page'] = PageModel::get($page);
    echo $this->templateParser->render('page.php.twig', $this->data);
  }

  public function actProduct($category) {
    $this->data['categories'][$_GET['i']]['active'] = true;
//    $this->data['categories'][$_POST['i']]['active'] = true;
    $this->data['products'] = ProductsModel::getWithCategory($category);
    echo $this->templateParser->render('products.php.twig', $this->data);
  }

  public function actAddToCart($product_id) {
//    $products = Session::getReference('products');
    if ($_GET['x'] < 0) $_GET['x'] = 0;
    $products = Session::get('products');
    if (isset($products[$product_id])) {
      $products[$product_id] += $_GET['x'];
    } else {
      $products[$product_id] = $_GET['x'];
    }
    Session::put('products', $products);
    Session::put('productsCount', Session::countValues('products'));
    Session::add('totalCost', $_GET['x'] * ProductsModel::getPrice($product_id));
    $response['productsCount'] = Functions::correctEnd(Session::get('productsCount'), ' товаров', ' товар', ' товара');
    $response['totalCost'] = Session::get('totalCost') . '  грн.';
    echo json_encode($response);
  }

  public function actSearch($code) {
    $this->data['products'] = ProductsModel::getWithCode(Functions::leaveLettersAndNumbers($code));
    echo $this->templateParser->render('search.php.twig', $this->data);
  }
  
  public function actCart() {
    $products = [];
    $i = 0;
    foreach (Session::get('products') as $productId => $quantity) {
      $products[$i] = ProductsModel::get($productId);
      $products[$i]['quantity'] = $quantity;
      $products[$i]['summary'] = $products[$i]['price'] * $quantity;
      ++$i;
    }
    if (isset($_GET['part'])) {
      if ($_GET['part']) {
        echo $this->templateParser->render('cart.php.twig', ['cart' => $products, 'session' => Session::getAll()]);
      }
    } else {
      echo 'FULL';
    }
  }
}

//$view = new \Views\BaseView(TEMPLATE);
//$viewPage = [
//  'header' => [
//    'brand' => null,
//    'callback' => null,
//    'phones' => 'phone_link',
//    'cart' => null,
//    'user' => null,
//    'menu' => 'menu_link'
//  ],
//  'sidebar' => [
////    'search' => null,
//    'categories' => 'category_link'
//  ],
//  'content' => null,
//];
//$view->startRender($viewPage);
