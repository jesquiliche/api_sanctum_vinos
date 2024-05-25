<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\MoonShine\Pages\Producto\ProductoIndexPage;
use App\MoonShine\Pages\Producto\ProductoFormPage;
use App\MoonShine\Pages\Producto\ProductoDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Producto>
 */
class ProductoResource extends ModelResource
{
    protected string $model = Producto::class;

    protected string $title = 'Productos';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            ProductoIndexPage::make($this->title()),
            ProductoFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            ProductoDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Producto $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
