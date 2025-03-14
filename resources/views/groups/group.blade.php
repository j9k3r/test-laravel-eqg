{{--<li><a href="?group_id={{ $group->id }}">{{ $group->name }} ({{ $group->total_products_count }})</a></li>--}}
<li>
{{--    <a href="{{ url('?' . http_build_query(array_merge(request()->except(['page']), ['group_id' => $group->id])) }}">--}}
    <a href="{{ route('home', array_merge(request()->except(['page']), ['group_id' => $group->id])) }}">
        {{ $group->name }} ({{ $group->total_products_count }})
    </a>
</li>

@if(request()->get('group_id') or request()->get('group_id') != 0)
    @if(count($group['descendants']))
        <ul>
            @foreach($group['descendants'] as $descendant)
                @include('groups.group', ['group' => $descendant])
            @endforeach
        </ul>
    @endif
@endif

