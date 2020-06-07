@php
    use App\Page;

    $pages = Page::all();
    $user = Auth::user();
    if (isset($user)) {
        if ($user->user_type_id == 1 and $user->student()->first()->group()->first()) {
            $pages = Page::where('show', '=', 1)->get();
        }

        if ($user->user_type_id == 2 ) {
            $pages = Page::where('show', '=', 1)->get();
        }
    }
@endphp
<footer class="footer p-4 mt-3">
    <div class="footer__inner container d-flex justify-content-end">
        <ul class="footer__list list-unstyled d-flex align-items-center flex-wrap">
            @if ($user)
                @if ($user->user_type_id == 1 and $user->student()->first()->group()->first())
                    <li class="footer__item">
                        <a class="footer__link p-2 text-white" href="/">Преподаватели</a>
                    </li>
                @endif
            @endif
            @foreach($pages as $page)
                <li class="footer__item">
                    <a class="footer__link p-2 text-white" href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</footer>
