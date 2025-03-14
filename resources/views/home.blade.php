<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Главная страница</title>
</head>
<body>
<h1>Список групп товаров</h1>
@each('groups.group', $groups, 'group')

<h1>Список товаров</h1>
<form method="GET">
    <label for="sort_by">Сортировать по:</label>
    <input type="hidden" name="group_id" value="{{ request('group_id') }}">
    <select name="sort_by" id="sort_by">
        <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>Цена</option>
        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Название</option>
    </select>
    <label for="sort_by">Порядок:</label>
    <select name="order" id="order">
        <option value="asc" {{ request('sort_by') === 'request' ? 'selected' : '' }}>Возрастанию</option>
        <option value="desc" {{ request('sort_by') === 'request' ? 'selected' : '' }}>Убыванию</option>
    </select>
    <button type="submit">Применить</button>
</form>

<ul>
{{--    {{dd($products)}}--}}
    @foreach($products as $product)
{{--        @dd($product)--}}
        <li>
            <a href="{{ route('product.show', ['id' => $product->id_product]) }}">
                <b>Название:</b> {{ $product->name }}
                <b>Цена:</b> {{ $product->price }}
                <b>Группа:</b> {{ $product->group->name }}
            </a>
        </li>
    @endforeach
</ul>

        <div class="pagination">
{{--            {{ $products->links('vendor.pagination.default') }}--}}
            {{ $products->appends(request()->except(['page']))->links('vendor.pagination.default') }}
        </div>

</body>
</html>
