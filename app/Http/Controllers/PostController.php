<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * 显示文章列表
     * @return Response 
     */
    public function index(){
        $posts = Cache::get('posts',[]);
        print_r($posts);
        if(!$posts)
            exit('Nothing');
        $html = '<ul>';
        foreach ($posts as $key=>$post) {
            $html .= '<li><a href='.route('post.show',['post'=>$key]).'>'.$post['title'].'</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * 创建表单页面
     * @return Response 
     */
    public function create(){
        $postUrl = route('post.store');
        $csrf_field = csrf_field();
        $html = <<<CREATE
            <form action="$postUrl" method="POST">
                $csrf_field
                <input type="text" name="title"><br/><br/>
                <textarea name="content" cols="50" rows="5"></textarea><br/><br/>
                <input type="submit" value="提交"/>
            </form>
CREATE;
        return $html;
    }

    /**
     * 将新创建的表单存储到存储器
     * @param  Request $request 
     * @return Response
     */
    public function store(Request $request){
        $title = $request->input('title');
        $content = $request->input('content');
        $post = ['title'=>trim($title),'content'=>trim($content)];
        $posts = Cache::get('posts',[]);
        if(!Cache::get('post_id')){
            Cache::add('post_id',1,60);
        }else{
            Cache::increment('post_id',1);
        }
        $posts[Cache::get('post_id')] = $post;
        Cache::put('posts',$posts,60);
        return redirect()->route('post.show',['post'=>Cache::get('post_id')]);
    }

    /**
     * 显示指定文章
     * @param  int $id 文章ID号
     * @return Response     
     */
    public function show($id){
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id])
            exit('Nothing Found！');
            $post = $posts[$id];
            $editUrl = route('post.edit',['post'=>$id]);
            $deleteUrl = route('post.destroy',['post'=>$id]);
            $post_delete = method_field('DELETE');
            $csrf_field = csrf_field();
            $html = <<<DETAIL
            <h3>{$post['title']}</h3>
            <p>{$post['content']}</p>
            <p>
                <a href="{$editUrl}">编辑</a>&nbsp;
            <form action="{$deleteUrl}" method="post">
                $post_delete
                $csrf_field
                <button type="submit" class="btn btn-danger">删除</button>
            </form>
            </p>
DETAIL;
        return $html;
    }

    /**
     * 显示编辑指定ID号的文章
     * @param  int $id 指定文章的id
     * @return Response     
     */
    public function edit($id){
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id])
            exit('Nothing Found！');
        $post = $posts[$id];
        $postUrl = route('post.update',['post'=>$id]);
        $csrf_field = csrf_field();
        $html = <<<UPDATE
        <form action="$postUrl" method="POST">
        $csrf_field
        <input type="hidden" name="_method" value="PUT"/>
        <input type="text" name="title" value="{$post['title']}"><br/><br/>
        <textarea name="content" cols="50" rows="5">{$post['content']}</textarea><br/><br/>
        <input type="submit" value="提交"/>
        </form>
UPDATE;
        return $html;
    }

    /**
     * 更新指定ID文章的内容 
     * @param  Request $request
     * @param  int  $id      指定文章的id
     * @return Response      
     */
    public function update(Request $request, $id){
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id])
            exit('Nothing Found！');
        $title = $request->input('title');
        $content = $request->input('content');
        $posts[$id]['title'] = trim($title);
        $posts[$id]['content'] = trim($content);
        Cache::put('posts',$posts,60);
        return redirect()->route('post.show',['post'=>Cache::get('post_id')]);
    }

    /**
     * 从存储器中删除指定id的文章
     * @param  int $id 指定文章的id
     * @return Response     
     */
    public function destroy($id){
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id])
            exit('Nothing Deleted！');
        unset($posts[$id]);
        Cache::decrement('post_id',1);
        Cache::put('posts',$posts,60);  
        return redirect()->route('post.index');
    }
}
