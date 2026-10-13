<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryFood;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoryFoodController extends Controller
{
    public function index(): View
    {
        $categories = CategoryFood::latest()->paginate(10);

        return view('admin.category-food.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.category-food.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'اسم التصنيف مطلوب.',
            'name.string' => 'اسم التصنيف يجب أن يكون نصاً.',
            'name.max' => 'اسم التصنيف يجب ألا يتجاوز 255 حرفاً.',
            'description.string' => 'وصف التصنيف يجب أن يكون نصاً.',
            'photo.image' => 'يجب أن يكون الملف صورة.',
            'photo.mimes' => 'يجب أن تكون الصورة بصيغة JPG أو JPEG أو PNG أو WEBP.',
            'photo.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',
            'is_active.boolean' => 'حالة التصنيف غير صحيحة.',
        ]);

        try {
            $photo = null;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo')->store('category-foods', 'public');
            }

            CategoryFood::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'photo' => $photo,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()
                ->route('admin.category-food.index')
                ->with('success', 'تم إنشاء التصنيف بنجاح.');
        } catch (Throwable $e) {
            Log::error('CategoryFood store failed', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء التصنيف.');
        }
    }

    public function show(CategoryFood $categoryFood): View
    {
        return view('admin.category-food.show', [
            'category' => $categoryFood,
        ]);
    }

    public function edit(CategoryFood $categoryFood): View
    {
        return view('admin.category-food.edit', [
            'category' => $categoryFood,
        ]);
    }

    public function update(
        Request $request,
        CategoryFood $categoryFood
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'اسم التصنيف مطلوب.',
            'name.string' => 'اسم التصنيف يجب أن يكون نصاً.',
            'name.max' => 'اسم التصنيف يجب ألا يتجاوز 255 حرفاً.',
            'description.string' => 'وصف التصنيف يجب أن يكون نصاً.',
            'photo.image' => 'يجب أن يكون الملف صورة.',
            'photo.mimes' => 'يجب أن تكون الصورة بصيغة JPG أو JPEG أو PNG أو WEBP.',
            'photo.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',
            'is_active.boolean' => 'حالة التصنيف غير صحيحة.',
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('photo')) {
                if ($categoryFood->photo) {
                    Storage::disk('public')->delete($categoryFood->photo);
                }

                $data['photo'] = $request->file('photo')->store('category-foods', 'public');
            }

            $categoryFood->update($data);

            return redirect()
                ->route('admin.category-food.index')
                ->with('success', 'تم تحديث التصنيف بنجاح.');
        } catch (Throwable $e) {
            Log::error('CategoryFood update failed', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث التصنيف.');
        }
    }

    public function destroy(CategoryFood $categoryFood): RedirectResponse
    {
        try {
            $categoryFood->delete();

            return redirect()
                ->route('admin.category-food.index')
                ->with('success', 'تم حذف التصنيف بنجاح.');
        } catch (Throwable $e) {
            Log::error('CategoryFood delete failed', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'حدث خطأ أثناء حذف التصنيف.');
        }
    }

    public function toggleStatus(CategoryFood $categoryFood): RedirectResponse
    {
        $categoryFood->update([
            'is_active' => !$categoryFood->is_active,
        ]);

        return back()->with('success', 'تم تحديث حالة التصنيف بنجاح.');
    }
}