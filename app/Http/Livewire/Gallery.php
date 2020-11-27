<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use App\Models\Gallery as Image;
use Livewire\WithFileUploads;

class Gallery extends Component
{
    use WithFileUploads;

    public $galleries, $title, $description, $image_url, $image_id;
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
        $this->image_id = '';
        $this->title = '';
        $this->description = '';
        $this->image_url = '';
    }

    public function render()
    {
        $this->galleries = optional(Auth::user())->galleries;

        return view('livewire.gallery.gallery');
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
            'description' => 'string',
            'image_url' => 'image|required|max:1024'
        ]);

        $upload = $this->image_url;
        $path = $upload->store('public/gallery');

        $image = Image::updateOrCreate([
            'id' => $this->image_id
        ],[
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $path,
            'user_id' => Auth::user()->id
        ]);

        session()->flash('message', $this->image_id ? 'Image dengan judul : '. $this->title . ' Berhasil di update': $this->title .'Image dengan judul : '. $this->title .' Ditambahkan');
        $this->close();
        $this->clear();
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);

        $this->image_id = $id;
        $this->title = $image->title;
        $this->description = $image->description;
        $this->image_url = $image->image_url;

        $this->open();
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);
        $image->delete();
        session()->flash('message', $image->title. ' sudah dihpaus');
    }
}
