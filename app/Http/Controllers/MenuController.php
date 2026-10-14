<?php

namespace App\Http\Controllers;

use App\Models\CategoryFood;
use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = CategoryFood::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->with([
                'foods' => function ($query) {
                    $query->where('is_active', 1);
                },
            ])
            ->get();

        return view('Menu.menu', compact('categories'));
    }
}
