@extends('layouts.app')

@section('title', 'Teaching Blog - SundayLearn')

@section('content')
<div style="padding: 3rem 0; background: var(--background-light);">
    <div class="container">
        <div class="page-header">
            <h1>Teaching Tips & Resources</h1>
            <p>Practical advice and inspiration for Sunday school teachers</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 3rem 0;">
            @foreach($categories as $category)
            <div style="background: var(--background-white); padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-light); text-align: center; cursor: pointer; transition: transform 0.3s ease;">
                <div style="font-size: 2rem; color: var(--secondary-color); margin-bottom: 0.5rem;">
                    <i class="fas fa-{{ $category['icon'] }}"></i>
                </div>
                <h3 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $category['name'] }}</h3>
                <p style="color: var(--text-light); font-size: 0.9rem;">{{ $category['count'] }} articles</p>
            </div>
            @endforeach
        </div>

        <h2 style="margin-bottom: 2rem; color: var(--primary-color);">Latest Articles</h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
            @foreach($posts as $post)
            <article style="background: var(--background-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow-light); transition: transform 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; color: var(--text-light);">
                    <span><i class="fas fa-user"></i> {{ $post['author'] }}</span>
                    <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($post['published_at'])->format('M j, Y') }}</span>
                </div>
                
                <h2 style="margin-bottom: 1rem;">
                    <a href="{{ route('blog.show', $post['id']) }}" style="color: var(--primary-color); text-decoration: none;">
                        {{ $post['title'] }}
                    </a>
                </h2>
                
                <p style="color: var(--text-light); margin-bottom: 1.5rem;">{{ $post['excerpt'] }}</p>
                
                <a href="{{ route('blog.show', $post['id']) }}" class="btn btn-outline">
                    Read More <i class="fas fa-arrow-right"></i>
                </a>
            </article>
            @endforeach
        </div>
    </div>
</div>
@endsection
