<?php
require_once ('adminAbstract.php');
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 20:14
 */
class Settings extends AdminAbstract
{
    protected $itemType = 'settings';
    protected $itemName = 'Настройки';

    function index()
    {
        $this->load->model('category_mdl', 'category');
        $this->load->model('items_mdl', 'items');
        $this->load->model('user_mdl', 'user');
        $this->load->model('currency_mdl', 'currency');
        $this->load->helper('bk');

        $categories   = get_categories_tree(-1, array(), -1);
        $items        = $this->get_item(null, 'products');
        $cat = $this->category->get_category(null, null, 'Ad');
        if ($cat && is_array($cat)) {
            $cat = $cat[0];
        }

        $cat_str = '';
        foreach ($categories as $category) {
            $indention = str_repeat("&nbsp;&nbsp;", $category->level);
            $cat_str .= '<option value="' . $category->category_id . '">' . $indention . $category->category_title . '</option>';
        }

        $val = [
            'items'        => $items,
            'ann_items'    => $this->get_item(null, null, null, $cat->category_id),
            'categories'   => $cat_str,
            'users'        => $this->user->get_users(),
            'currency'     => $this->currency->get_currency(),
            'item_type'    => $this->itemType
        ];

        $this->load->view('admin/settings', $val);
    }

    function get_ann_items()
    {
        $this->load->model('category_mdl', 'category');

        $cat = $this->category->get_category(null, null, 'Ad');
        if ($cat && is_array($cat)) {
            $cat = $cat[0];
        }

        return $this->load->view(
            'admin/ann_items',
                [
                    'items' => $this->get_item(null, null, null, $cat->category_id)
                ],
            true
        );
    }

    public function getItemByCategory()
    {
        $categoryId = (int) $this->input->post('categoryId');

        $this->load->model('category_mdl', 'category');

        echo json_encode([
            'items' => $this->category->get_category_item($categoryId)
        ]);
    }

    public function getItemByCoordinates()
    {
        $hOrder = $this->input->post('hOrder');
        $vOrder = $this->input->post('vOrder');

        $this->load->model('items_mdl', 'item');

        $item = $this->item->getItem(
            [
                'hOrder' => $hOrder,
                'vOrder' => $vOrder
            ],
            true
        );

        echo json_encode(
            [
                'item' => $item
            ],
            true
        );
    }

    public function setItemByCoordinate()
    {
        $categoryId = (int) $this->input->post('categoryId');
        $itemId = (int) $this->input->post('itemId');
        $hOrder = (int) $this->input->post('hOrder');
        $vOrder = (int) $this->input->post('vOrder');

        $this->load->model('items_mdl', 'item');

        $this->item->saveItemCoordinates(
            $itemId,
            $categoryId,
            [
                'hOrder'     => $hOrder,
                'vOrder'     => $vOrder
            ]
        );
        echo json_encode(
            [
                'success' => true
            ],
            true
        );
    }
}
