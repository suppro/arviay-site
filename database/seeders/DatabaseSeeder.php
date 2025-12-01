<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем администратора
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@arviay.ru',
            'password' => Hash::make('admin123'),
            'phone' => '+79991234567',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Создаем тестового клиента
        User::create([
            'name' => 'Иван Иванов',
            'email' => 'client@arviay.ru',
            'password' => Hash::make('client123'),
            'phone' => '+79991234568',
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        // Создаем категории
        $category1 = Category::create([
            'name' => 'Двигатели и компоненты',
            'slug' => 'engines-components',
            'description' => 'Авиационные двигатели и их компоненты',
        ]);

        $category2 = Category::create([
            'name' => 'Шасси и тормозные системы',
            'slug' => 'landing-gear-brakes',
            'description' => 'Компоненты шасси и тормозных систем',
        ]);

        $category3 = Category::create([
            'name' => 'Электрооборудование',
            'slug' => 'electrical-equipment',
            'description' => 'Электронные компоненты и системы',
        ]);

        $category4 = Category::create([
            'name' => 'Гидравлика и пневматика',
            'slug' => 'hydraulics-pneumatics',
            'description' => 'Гидравлические и пневматические системы',
        ]);

        // Подкатегории для категории 1
        $subcategory1 = Category::create([
            'parent_id' => $category1->id,
            'name' => 'Турбины',
            'slug' => 'turbines',
            'description' => 'Авиационные турбины',
        ]);

        $subcategory2 = Category::create([
            'parent_id' => $category1->id,
            'name' => 'Компрессоры',
            'slug' => 'compressors',
            'description' => 'Компрессоры для авиационных двигателей',
        ]);

        // Создаем товары
        $products = [
            [
                'category_id' => $subcategory1->id,
                'name' => 'Турбина ТВ3-117',
                'slug' => 'turbina-tv3-117',
                'sku' => 'TV3-117-001',
                'description' => 'Авиационная турбина ТВ3-117 для вертолетов Ми-8, Ми-17. Полностью восстановленная, с гарантией.',
                'technical_specs' => [
                    'type' => 'Турбовальный двигатель',
                    'power' => '1500 л.с.',
                    'weight' => '330 кг',
                    'manufacturer' => 'Климов',
                ],
                'price' => 2500000.00,
                'stock_quantity' => 2,
                'is_active' => true,
            ],
            [
                'category_id' => $subcategory1->id,
                'name' => 'Турбина АИ-20',
                'slug' => 'turbina-ai-20',
                'sku' => 'AI-20-002',
                'description' => 'Турбовинтовой двигатель АИ-20 для самолетов Ан-12, Ан-24. Проверенное состояние.',
                'technical_specs' => [
                    'type' => 'Турбовинтовой двигатель',
                    'power' => '4000 л.с.',
                    'weight' => '1040 кг',
                    'manufacturer' => 'Ивченко-Прогресс',
                ],
                'price' => 4500000.00,
                'stock_quantity' => 1,
                'is_active' => true,
            ],
            [
                'category_id' => $category2->id,
                'name' => 'Стойка шасси передняя',
                'slug' => 'stoyka-shassi-perednyaya',
                'sku' => 'LG-FRONT-001',
                'description' => 'Передняя стойка шасси для самолетов Ан-2. Новое состояние, оригинальная комплектация.',
                'technical_specs' => [
                    'type' => 'Стойка шасси',
                    'load' => '3500 кг',
                    'material' => 'Сталь',
                    'compatibility' => 'Ан-2',
                ],
                'price' => 850000.00,
                'stock_quantity' => 4,
                'is_active' => true,
            ],
            [
                'category_id' => $category2->id,
                'name' => 'Тормозной диск',
                'slug' => 'tormoznoy-disk',
                'sku' => 'BRAKE-DISC-001',
                'description' => 'Тормозной диск для основных стоек шасси. Высококачественный материал, длительный срок службы.',
                'technical_specs' => [
                    'type' => 'Тормозной диск',
                    'diameter' => '450 мм',
                    'material' => 'Карбон-керамика',
                    'compatibility' => 'Универсальный',
                ],
                'price' => 125000.00,
                'stock_quantity' => 12,
                'is_active' => true,
            ],
            [
                'category_id' => $category3->id,
                'name' => 'Генератор ГС-24',
                'slug' => 'generator-gs-24',
                'sku' => 'GEN-GS-24-001',
                'description' => 'Авиационный генератор ГС-24. Обеспечивает стабильное питание бортовых систем.',
                'technical_specs' => [
                    'type' => 'Генератор',
                    'power' => '24 кВт',
                    'voltage' => '28.5 В',
                    'manufacturer' => 'Авиаагрегат',
                ],
                'price' => 350000.00,
                'stock_quantity' => 6,
                'is_active' => true,
            ],
            [
                'category_id' => $category3->id,
                'name' => 'Аккумуляторная батарея 20НКБН-25',
                'slug' => 'akkumulyatornaya-batareya-20nkbn-25',
                'sku' => 'BAT-20NKBN-25',
                'description' => 'Никель-кадмиевая аккумуляторная батарея для авиационной техники. Новое состояние.',
                'technical_specs' => [
                    'type' => 'Аккумуляторная батарея',
                    'capacity' => '25 Ач',
                    'voltage' => '24 В',
                    'weight' => '18 кг',
                ],
                'price' => 95000.00,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => $category4->id,
                'name' => 'Гидронасос НП-89',
                'slug' => 'gidronasos-np-89',
                'sku' => 'HYD-PUMP-NP-89',
                'description' => 'Гидравлический насос НП-89 для систем управления. Проверенное состояние.',
                'technical_specs' => [
                    'type' => 'Гидронасос',
                    'pressure' => '210 кгс/см²',
                    'flow' => '45 л/мин',
                    'manufacturer' => 'Авиаагрегат',
                ],
                'price' => 280000.00,
                'stock_quantity' => 8,
                'is_active' => true,
            ],
            [
                'category_id' => $category4->id,
                'name' => 'Гидроцилиндр',
                'slug' => 'gidrotsilindr',
                'sku' => 'HYD-CYL-001',
                'description' => 'Гидравлический цилиндр для систем управления. Универсальное применение.',
                'technical_specs' => [
                    'type' => 'Гидроцилиндр',
                    'stroke' => '250 мм',
                    'diameter' => '63 мм',
                    'pressure' => '210 кгс/см²',
                ],
                'price' => 145000.00,
                'stock_quantity' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('База данных успешно заполнена!');
        $this->command->info('Администратор: admin@arviay.ru / admin123');
        $this->command->info('Клиент: client@arviay.ru / client123');
    }
}
