@extends('layouts.app')

@section('title', 'Resources - SundayLearn')

@section('content')
<div style="padding: 3rem 0; background: var(--background-light);">
    <div class="container">
        <div class="page-header" style="text-align: center; margin-bottom: 3rem;">
            <h1>Teaching Resources</h1>
            <p style="font-size: 1.2rem; color: var(--text-light);">Free downloadable materials to enhance your Sunday school lessons</p>
        </div>

        @foreach($resources as $category)
        <div style="margin-bottom: 3rem;">
            <h2 style="color: var(--primary-color); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 3px solid var(--secondary-color);">
                <i class="fas fa-folder-open"></i> {{ $category['category'] }}
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($category['items'] as $item)
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-light); display: flex; align-items: center; gap: 1rem; transition: transform 0.3s ease;">
                    <div style="flex-shrink: 0;">
                        <div style="width: 60px; height: 60px; background: #f5f5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-pdf" style="font-size: 2rem; color: #d32f2f;"></i>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $item['name'] }}</h3>
                        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 0.5rem;">
                            {{ $item['type'] }} • {{ $item['size'] }}
                        </p>
                        <a href="#" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.9rem; text-decoration: none;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div style="background: var(--background-white); padding: 2rem; border-radius: 10px; box-shadow: var(--shadow-light); text-align: center; margin-top: 3rem;">
            <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Need More Resources?</h3>
            <p style="margin-bottom: 1.5rem; color: var(--text-light);">
                Each lesson includes additional downloadable materials specific to that Bible story.
            </p>
            <a href="{{ route('lessons.index') }}" class="btn btn-secondary">
                Browse All Lessons
            </a>
        </div>
    </div>
</div>
@endsection
