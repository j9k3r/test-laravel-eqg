<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by') ?? 'price';
        $order = $request->input('order') ?? 'asc';

        $groupId = $request->input('group_id');

        if ($groupId || $groupId != 0) {
            $groups = Group::where('id', $groupId)->with('descendants')->get();

        } else {
            $groups = Group::where('id_parent', 0)->get();
        }

        $this->calculateTotalProductsCount($groups);

        $productsQuery = Product::with(['group', 'price']);

        if ($groupId) {
            $group = Group::findOrFail($groupId);
            $productIds = $group->getAllProducts()->pluck('id')->toArray();

            $productsQuery->whereIn('products.id', $productIds);
        }

        $productsQuery->when($sortBy == 'price', function ($query) use ($order) {
            $query->join('prices', 'products.id', '=', 'prices.id_product')
                ->select('prices.*', 'products.*')
                ->orderBy('prices.price', $order);
        })->when($sortBy == 'name', function ($query) use ($order) {
//            $query->orderBy('products.name', $order);
            $query->leftJoin('prices', 'products.id', '=', 'prices.id_product')
                ->select('prices.*', 'products.*')
                ->orderBy('products.name', $order);
        });

        $products = $productsQuery->paginate(6);

        return view('home', compact('groups', 'products'))
            ->withQueryString(request()->except(['page']));
    }

    private function calculateTotalProductsCount($groups)
    {
        foreach ($groups as $group) {
            $group->total_products_count = $group->totalProductsCount();
            if (!empty($group->descendants)) {
                $this->calculateTotalProductsCount($group->descendants);
            }
        }


    }

}
