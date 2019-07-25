<?php

namespace App\Http\Controllers;

use App\Post;
use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebController extends Controller
{
    public function home()
    {
        $posts = Post::orderBy('created_at', 'DESC')->limit(3)->get();

       $head =  $this->seo->render(env('APP_NAME') . 'Upindide Treinamentos', 
            'Descrição',
            url('/'), 
            asset('images/img_bg+1.jpg'));

        return view('front.home', [
            'head' => $head,
            'posts' => $posts
        ]);
        
    }

    public function course()
    {
        $head =  $this->seo->render(env('APP_NAME') . 'Sobre o Curso', 
            'Descrição Sobre',
            route('course'), 
            asset('images/img_bg+1.jpg'));

        return view('front.course', [
            'head' => $head
        ]);
    }

    public function blog()
    {
        $posts = Post::orderBy('created_at', 'DESC')->get();
        return view('front.blog', [
            'posts' => $posts
        ]);
    }

    public function article($uri)
    {   
        $post = Post::where('uri', $uri)->first();

        $head =  $this->seo->render(env('APP_NAME') . ' - ' . $post->title, 
            $post->subtitle,
            route('article', $post->uri), 
            \Illuminate\Support\Facades\Storage::url(\App\Support\Cropper::thumb($post->cover, 1200, 628)));

        return view('front.article', [
            'head' => $head,
            'post' => $post
        ]);
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function sendMail(Request $request)
    {
        $data = [
            'reply_name' => $request->first_name . " " . $request->last_name,
            'reply_email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message
        ];
        
        Mail::send(new Contact($data));

        return redirect()->route('contact');
        
        //return new Contact($data);
        //var_dump($request->all());
    }

}
