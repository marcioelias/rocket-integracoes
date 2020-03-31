@if (count($breadcrumbs))

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}" style="vertical-align: baseline">{{ $breadcrumb->title }}</a></li>
            @elseif (!$breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item"><a href="javascript:void(0);" style="vertical-align: baseline">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>

@endif
