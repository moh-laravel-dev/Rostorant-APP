<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\CategoryFood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    // عرض وجبات التصنيف المحدد فقط


    public function index(Request $request, CategoryFood $category)
    {
        $query = $category->foods();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        switch ($request->input('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;

            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        $foods = $query->paginate(12)->withQueryString();

        return view('admin.foods.index', compact('category', 'foods'));
    }
    public function create(CategoryFood $category)
    {
        return view('admin.foods.create', compact('category'));
    }

    public function store(Request $request, CategoryFood $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['category_food_id'] = $category->id;
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('foods', 'public');
        }

        Food::create($validated);

        return redirect()->route('admin.category-food.food.index', $category)
            ->with('success', 'تم إضافة الوجبة للتصنيف بنجاح');
    }

    public function show(CategoryFood $category, Food $food)
    {
        return view('admin.foods.show', compact('category', 'food'));
    }

    public function edit(CategoryFood $category, Food $food)
    {
        // Fetch all categories so the user can select a different one if needed
        $categories = CategoryFood::all();

        return view('admin.foods.edit', compact('category', 'food', 'categories'));
    }
    public function update(Request $request, CategoryFood $category, Food $food)
    {
        $validated = $request->validate([
            'category_food_id' => 'required|exists:category_foods,id', // تصحيح الإملاء ونوع التحقق
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // If a new image is uploaded, delete the old one and store the new one
        if ($request->hasFile('image')) {
            if ($food->image && Storage::disk('public')->exists($food->image)) {
                Storage::disk('public')->delete($food->image);
            }
            $validated['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($validated);

        // جلب الكاتجوري الجديد لضمان التوجيه الصحيح إذا تم نقله لقسم آخر
        $newCategory = CategoryFood::findOrFail($validated['category_food_id']);

        return redirect()->route('admin.category-food.food.index', $newCategory)
            ->with('success', 'تم تحديث الوجبة بنجاح');
    }

    public function destroy(CategoryFood $category, Food $food)
    {
        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();

        return redirect()->route('admin.category-food.food.index', $category)
            ->with('success', 'تم حذف الوجبة بنجاح');
    }

    public function toggleStatus(CategoryFood $category, Food $food)
    {
        $food->is_active = !$food->is_active;
        $food->save();

        return redirect()->back()->with('success', 'تم تحديث حالة الوجبة بنجاح');
    }
}
