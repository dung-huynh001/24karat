@section(section: 'breadcrumb')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center bg-white px-4 py-2 rounded">
            <h5 class="p-0 m-0 text-primary">{{end($breadcrumbs)['title']}}</h5>
            <div class="p-0 m-0 d-flex gap-2 align-items-center">
                @foreach ($breadcrumbs as $breadcrumb)
                    <a href="{{ !$breadcrumb['active'] ? url($breadcrumb['url']) : '#' }}"
                        class="{{ $breadcrumb['active'] ? 'text-muted' : '' }} {{$breadcrumb['active'] ? 'text-decoration-none' : ''}}">
                        {{ $breadcrumb['title'] }}
                    </a>
                    @if (!$loop->last)
                        <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem"></i>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection