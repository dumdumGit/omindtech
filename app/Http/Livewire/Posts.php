<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Posts as PostModel;
use Auth;

class Posts extends Component
{
    public $title, $content, $published, $post_id, $posts;
    public $create = false;

    public function close()
    {
        $this->create = false;
    }

    public function open()
    {
        $this->create = true;
    }

    public function clear()
    {
        $this->post_id = '';
        $this->title = '';
        $this->content = '';
        $this->published = 0;
    }

    public function render()
    {
        $this->posts = optional(Auth::user())->post;
        return view('livewire.post.posts');
    }

    public function create()
    {   
        $this->clear();
        $this->open();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string',
        ]);

        $post = PostModel::updateOrCreate([
            'id' => $this->post_id
        ],[
            'title' => $this->title,
            'content' => $this->content,
            'published' => $this->published,
            'user_id' => Auth::id()
        ]);

        session()->flash('message', $this->post_id ? 'Post dengan judul : '. $this->title . ' Berhasil di update': $this->title .'Post dengan judul : '. $this->title .' Ditambahkan');
        $this->close();
        $this->clear();
    }

    public function edit($id)
    {
        $post = PostModel::findOrFail($id);

        $this->post_id = $id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->published    = $post->published;

        $this->open();
    }

    public function delete($id)
    {
        $post = PostModel::findOrFail($id);
        $post->delete();
        session()->flash('message', $post->title. ' sudah dihpaus');
    }
}
