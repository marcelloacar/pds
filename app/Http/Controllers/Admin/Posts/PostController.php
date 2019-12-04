<?php

namespace App\Http\Controllers\Admin\Posts;

use App\Shop\Posts\Repositories\PostRepository;
use App\Shop\Posts\Repositories\Interfaces\PostRepositoryInterface;
use App\Shop\Posts\Requests\CreatePostRequest;
use App\Shop\Posts\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $postRepo;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepo = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->postRepo->listPosts('created_at', 'desc');

        return view('admin.posts.list', [
            'posts' => $this->postRepo->paginateArrayResults($list->all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create', [
            'posts' => $this->postRepo->listPosts('name', 'asc')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $this->postRepo->createPost($request->except('_token', '_method'));

        return redirect()->route('admin.posts.index')->with('message', 'Post criado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->postRepo->findPostById($id);
        return view('admin.posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.posts.edit', [
            'posts' => $this->postRepo->listPosts('name', 'asc', $id),
            'post' => $this->postRepo->findPostById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePostRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = $this->postRepo->findPostById($id);

        $update = new PostRepository($post);
        $update->updatePost($request->except('_token', '_method'));

        $request->session()->flash('message', 'Atualizado com sucesso');
        return redirect()->route('admin.posts.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $post = $this->postRepo->findPostById($id);
        $post->delete();

        request()->session()->flash('message', 'Removido com sucesso');
        return redirect()->route('admin.posts.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {

        $this->postRepo->deleteFile($request->only('post'));
        request()->session()->flash('message', 'Imagem removida com sucesso');
        return redirect()->route('admin.posts.edit', $request->input('post'));
    }
}
