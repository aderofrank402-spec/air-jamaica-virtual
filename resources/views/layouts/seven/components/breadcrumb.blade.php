{{-- Breadcrumb Component - Mission Control Theme --}}
@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav aria-label="breadcrumb" class="mc-breadcrumb-wrap">
    <ol class="mc-breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            @if($loop->last)
                <li class="mc-breadcrumb-item mc-breadcrumb-active">
                    @if(isset($breadcrumb['icon']))
                        <i class="{{ $breadcrumb['icon'] }}"></i>
                    @endif
                    {{ $breadcrumb['title'] }}
                </li>
            @else
                <li class="mc-breadcrumb-item">
                    @if(isset($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}">
                            @if(isset($breadcrumb['icon']))
                                <i class="{{ $breadcrumb['icon'] }}"></i>
                            @endif
                            {{ $breadcrumb['title'] }}
                        </a>
                    @else
                        <span>
                            @if(isset($breadcrumb['icon']))
                                <i class="{{ $breadcrumb['icon'] }}"></i>
                            @endif
                            {{ $breadcrumb['title'] }}
                        </span>
                    @endif
                </li>
                <li class="mc-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            @endif
        @endforeach
    </ol>
</nav>

<style>
.mc-breadcrumb-wrap {
    margin-bottom: 1.25rem;
}

.mc-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0;
    list-style: none;
    margin: 0;
    padding: .55rem 1rem;
    background: rgba(15,23,42,.7);
    border: 1px solid rgba(16,185,129,.12);
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.mc-breadcrumb-item {
    display: flex;
    align-items: center;
    gap: .35rem;
    font-family: 'JetBrains Mono', monospace;
    font-size: .75rem;
    font-weight: 500;
    color: #94a3b8;
}

.mc-breadcrumb-item i {
    font-size: .7rem;
    opacity: .8;
}

.mc-breadcrumb-item a {
    display: flex;
    align-items: center;
    gap: .35rem;
    color: #94a3b8;
    text-decoration: none;
    transition: color .15s;
}

.mc-breadcrumb-item a:hover {
    color: #10b981;
    text-decoration: none;
}

.mc-breadcrumb-sep {
    display: flex;
    align-items: center;
    margin: 0 .5rem;
    color: rgba(148,163,184,.3);
    font-size: .6rem;
    list-style: none;
}

.mc-breadcrumb-active {
    color: #e2e8f0;
    font-weight: 600;
}
</style>
@endif
