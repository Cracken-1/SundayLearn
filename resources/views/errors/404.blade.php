@extends('layouts.app')

@section('title', 'Page Not Found - SundayLearn')

@section('content')
<div style="padding: 6rem 0; background: var(--background-light); min-height: 60vh; display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <div style="font-size: 8rem; color: var(--secondary-color); margin-bottom: 1rem;">
                <i class="fas fa-book-dead"></i>
            </div>
            <h1 style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;">404</h1>
            <h2 style="color: var(--text-dark); margin-bottom: 1.5rem;">Page Not Found</h2>
            <p style="font-size: 1.2rem; color: var(--text-light); margin-bottom: 2rem;">
                Oops! It looks like this page has wandered off like a lost sheep. Let's get you back on track.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go Home
                </a>
                <a href="{{ route('lessons.index') }}" class="btn btn-secondary">
                    <i class="fas fa-book-open"></i> Browse Lessons
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
