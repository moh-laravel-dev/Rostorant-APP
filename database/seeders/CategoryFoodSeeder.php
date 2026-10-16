<?php

namespace Database\Seeders;

use App\Models\CategoryFood;
use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryFoodSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $categories = [
                [
                    'name' => 'المقبلات',
                    'description' => 'أشهى المقبلات والأطباق الجانبية',
                    'photo' => null,
                    'foods' => [
                        ['name' => 'بطاطا مقلية', 'description' => 'بطاطا ذهبية مقرمشة', 'price' => 250],
                        ['name' => 'حمص', 'description' => 'حمص بالطحينة وزيت الزيتون', 'price' => 300],
                        ['name' => 'متبل باذنجان', 'description' => 'باذنجان مشوي مع الطحينة', 'price' => 350],
                        ['name' => 'سلطة خضراء', 'description' => 'خضار طازجة مع الليمون', 'price' => 300],
                        ['name' => 'فتوش', 'description' => 'سلطة فتوش مع الخبز المحمص', 'price' => 400],
                    ],
                ],
                [
                    'name' => 'الوجبات الرئيسية',
                    'description' => 'وجبات رئيسية شهية ومميزة',
                    'photo' => null,
                    'foods' => [
                        ['name' => 'شاورما دجاج', 'description' => 'شاورما دجاج مع الثوم والمخلل', 'price' => 600],
                        ['name' => 'شاورما لحم', 'description' => 'شاورما لحم مع الصلصة الخاصة', 'price' => 750],
                        ['name' => 'برغر لحم', 'description' => 'برغر لحم مع الجبن والخضار', 'price' => 800],
                        ['name' => 'برغر دجاج', 'description' => 'برغر دجاج مقرمش مع الصلصة', 'price' => 700],
                        ['name' => 'دجاج مشوي', 'description' => 'دجاج مشوي مع البطاطا والأرز', 'price' => 1000],
                        ['name' => 'بيتزا خضار', 'description' => 'بيتزا بالخضار والجبن', 'price' => 700],
                        ['name' => 'بيتزا بيبروني', 'description' => 'بيتزا بالجبن والبيبروني', 'price' => 900],
                    ],
                ],
                [
                    'name' => 'المشروبات الباردة',
                    'description' => 'مجموعة من المشروبات المنعشة',
                    'photo' => null,
                    'foods' => [
                        ['name' => 'مياه معدنية', 'description' => 'مياه معدنية باردة', 'price' => 100],
                        ['name' => 'كولا', 'description' => 'مشروب غازي بارد', 'price' => 150],
                        ['name' => 'عصير برتقال', 'description' => 'عصير برتقال منعش', 'price' => 250],
                        ['name' => 'ليمون ونعناع', 'description' => 'ليمون طازج مع النعناع', 'price' => 300],
                    ],
                ],
                [
                    'name' => 'المشروبات الساخنة',
                    'description' => 'مشروبات ساخنة لتحسين تجربتك',
                    'photo' => null,
                    'foods' => [
                        ['name' => 'قهوة تركية', 'description' => 'قهوة تركية محضرة بعناية', 'price' => 200],
                        ['name' => 'إسبريسو', 'description' => 'قهوة إسبريسو مركزة', 'price' => 250],
                        ['name' => 'كابتشينو', 'description' => 'قهوة بالحليب ورغوة ناعمة', 'price' => 300],
                        ['name' => 'شاي', 'description' => 'شاي ساخن', 'price' => 150],
                    ],
                ],
                [
                    'name' => 'الحلويات',
                    'description' => 'حلويات لذيذة بعد الوجبة',
                    'photo' => null,
                    'foods' => [
                        ['name' => 'تشيز كيك', 'description' => 'تشيز كيك كريمي', 'price' => 400],
                        ['name' => 'براونيز', 'description' => 'كيك شوكولاتة غني', 'price' => 350],
                        ['name' => 'آيس كريم', 'description' => 'آيس كريم بنكهات متنوعة', 'price' => 250],
                    ],
                ],
            ];

            foreach ($categories as $categoryData) {
                $foods = $categoryData['foods'];

                $category = CategoryFood::updateOrCreate(
                    ['name' => $categoryData['name']],
                    [
                        'description' => $categoryData['description'],
                        'photo' => $categoryData['photo'],
                        'is_active' => true,
                    ]
                );

                foreach ($foods as $foodData) {
                    Food::updateOrCreate(
                        [
                            'category_food_id' => $category->id,
                            'name' => $foodData['name'],
                        ],
                        [
                            'description' => $foodData['description'],
                            'price' => $foodData['price'],
                            'image' => null,
                            'is_active' => true,
                        ]
                    );
                }
            }
        });
    }
}
