@php
    use App\Page;

    $pages = Page::all();
@endphp
<footer class="footer p-4 mt-3">
    <div class="footer__inner container d-flex justify-content-end">
        <ul class="footer__list list-unstyled d-flex align-items-center flex-wrap">
            <li class="footer__item">
                <a class="footer__link p-2 text-white" href="/">Преподаватели</a>
            </li>
            @foreach($pages as $page)
                <li class="footer__item">
                    <a class="footer__link p-2 text-white" href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</footer>
