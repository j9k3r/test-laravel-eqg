<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach($product->group->breadcrumbs() as $group)
                <li class="breadcrumb-item"><a href="{{ route('home', ['group_id' => $group->id]) }}">{{ $group->name }}</a></li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <h1>{{ $product->name }}</h1>
    <p><strong>Цена:</strong> {{ $product->price }}</p>
    <p><strong>Группа:</strong> {{ $product->group->name }}</p>
</div>
