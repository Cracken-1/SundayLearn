@extends('layouts.app')

@section('title', $post['title'] . ' - SundayLearn Blog')

@section('content')
<div style="padding: 3rem 0; background: var(--background-light);">
    <div class="container">
        <a href="{{ route('blog.index') }}" style="color: var(--primary-color); text-decoration: none; margin-bottom: 2rem; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Back to Blog
        </a>

        <article style="max-width: 800px; margin: 0 auto; background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light);">
            <header style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid var(--border-color);">
                <h1 style="color: var(--primary-color); margin-bottom: 1rem;">{{ $post['title'] }}</h1>
                
                <div style="display: flex; gap: 2rem; color: var(--text-light);">
                    <span><i class="fas fa-user"></i> {{ $post['author'] }}</span>
                    <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($post['published_at'])->format('F j, Y') }}</span>
                </div>
            </header>

            <div style="line-height: 1.8; color: var(--text-dark);">
                {!! nl2br(e($post['content'])) !!}
            </div>
        </article>
    </div>
</div>
@endsection
