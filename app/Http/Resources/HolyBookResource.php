<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Bookmark;
use Auth;

class HolyBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $id = Auth::id();
        $type = $request->type;
        $book = Bookmark::where(['user_id'=>$id,'book_id'=>$this->id])->first();
        if($type=='holy'){
            if($request->lang == 'ar'){
                return [
                    'id'            => $this->id,
                    'author'        => $this->author,
                    'title'         => $this->ar_title,
                    'description'   => $this->ar_description,
                    'image'         => ($this->image)?url('/').'/public/'.$this->image:null,
                    'url'           => ($this->url)?url('/').'/public/'.$this->url:null,
                    'bookmark'      => ($book)?'yes':'no',
                ];
            }elseif ($request->lang == 'pe') {
                return [
                    'id'            => $this->id,
                    'author'        => $this->author,
                    'title'         => $this->pe_title,
                    'description'   => $this->pe_description,
                    'image'         => ($this->image)?url('/').'/public/'.$this->image:null,
                    'url'           => ($this->url)?url('/').'/public/'.$this->url:null,
                    'bookmark'      => ($book)?'yes':'no',
                ];
            }else{
                return [
                    'id'            => $this->id,
                    'author'        => $this->author,
                    'title'         => $this->title,
                    'description'   => $this->description,
                    'image'         => ($this->image)?url('/').'/public/'.$this->image:null,
                    'url'           => ($this->url)?url('/').'/public/'.$this->url:null,
                    'bookmark'      => ($book)?'yes':'no',
                ];
            }
        }else{
            if($request->lang == 'ar'){
                return [
                    'id'            => $this->id,
                    'author'        => $this->author,
                    'title'         => $this->other_ar_title,
                    'description'   => $this->other_ar_description,
                    'image'         => ($this->other_image)?url('/').'/public/'.$this->other_image:null,
                    'url'           => ($this->other_url)?url('/').'/public/'.$this->other_url:null,
                    'bookmark'      => ($book)?'yes':'no',
                ];
            }elseif ($request->lang == 'pe') {
                return [
                    'id'            => $this->id,
                    'author'        => $this->author,
                    'title'         => $this->other_pe_title,
                    'description'   => $this->other_pe_description,
                    'image'         => ($this->other_image)?url('/').'/public/'.$this->other_image:null,
                    'url'           => ($this->other_url)?url('/').'/public/'.$this->other_url:null,
                    'bookmark'      => ($book)?'yes':'no',
                ];
            }else{
                return [
                    'id'            => $this->id,
                    'author'        => $this->author,
                    'title'         => $this->other_title,
                    'description'   => $this->other_description,
                    'image'         => ($this->other_image)?url('/').'/public/'.$this->other_image:null,
                    'url'           => ($this->other_url)?url('/').'/public/'.$this->other_url:null,
                    'bookmark'      => ($book)?'yes':'no',
                ];
            }
        }
    }
}
