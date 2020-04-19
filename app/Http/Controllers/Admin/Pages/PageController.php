<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePage;
use App\Http\Requests\UpdatePage;
use App\Page;
use DB;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $page;

    /**
     * PageController constructor.
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pages.index', ['pages' => Page::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(CreatePage $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['show'] = isset($data['show']) ? 1 : 0;
            $this->page->create($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Страница успешно создана');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', ['page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', ['page' => $page]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(UpdatePage $request, Page $page)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['show'] = isset($data['show']) ? 1 : 0;
            /*
                Благодаря обнулению слага, он будет пересоздан при обновлении.
                Эта строка нужна, если заголовок страницы был изменени и мы
                при этом хотим изменить слаг. Изменение слага может негативно
                сказаться на SEO.
            */
            $page->slug = null;
            $page->update($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back()->with('status', 'Страница успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        try {
            DB::beginTransaction();

            $page->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return "false";
        }

        return "true";
    }

}
