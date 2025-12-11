@extends('layouts.app')

@section('title', 'About Us - SundayLearn')

@section('content')
<div style="padding: 3rem 0; background: var(--background-light);">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h1 style="color: var(--primary-color); font-size: 3rem; margin-bottom: 1rem;">About SundayLearn</h1>
                <p style="font-size: 1.3rem; color: var(--text-light);">Empowering Sunday school teachers with quality biblical education resources</p>
            </div>

            <div style="background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Our Mission</h2>
                <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                    SundayLearn was created with a simple yet powerful mission: to equip Sunday school teachers with the resources they need to effectively teach God's Word to the next generation. We believe that every child deserves to hear Bible stories in engaging, age-appropriate ways that help them understand God's love and plan for their lives.
                </p>
                <p style="line-height: 1.8;">
                    Our platform provides comprehensive lesson plans, multimedia resources, and teaching materials that make it easy for teachers to prepare meaningful lessons that capture children's hearts and minds.
                </p>
            </div>

            <div style="background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">What We Offer</h2>
                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book-open" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Comprehensive Lesson Plans</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                Each lesson includes objectives, full content, discussion questions, and activity suggestions tailored to specific age groups.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-video" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Multimedia Resources</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                Video lessons, audio stories, and visual aids that bring Bible stories to life and engage modern learners.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-download" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Downloadable Materials</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                Worksheets, coloring pages, craft templates, and activity sheets ready to print and use in your classroom.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Age-Appropriate Content</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                Lessons designed specifically for different age groups (3-5, 6-8, 9-12, and teens) with appropriate language and concepts.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Our Story</h2>
                <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                    SundayLearn began when a group of experienced Sunday school teachers recognized a common challenge: finding quality, engaging lesson materials that were both biblically sound and age-appropriate. Many teachers were spending hours each week searching for resources, creating materials from scratch, or adapting content that wasn't quite right for their students.
                </p>
                <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                    We decided to create a centralized platform where teachers could find everything they need in one place. Our team of educators, theologians, and designers work together to create lessons that are theologically accurate, pedagogically sound, and genuinely engaging for children.
                </p>
                <p style="line-height: 1.8;">
                    Today, SundayLearn serves teachers in churches of all sizes, helping them spend less time preparing and more time connecting with their students.
                </p>
            </div>

            <div style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); padding: 3rem; border-radius: 10px; text-align: center; color: white;">
                <h2 style="color: white; margin-bottom: 1rem;">Join Our Community</h2>
                <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.95;">
                    Become part of a growing community of Sunday school teachers dedicated to sharing God's Word with the next generation.
                </p>
                <a href="{{ route('lessons.index') }}" class="btn" style="background: white; color: var(--primary-color); padding: 1rem 2rem; font-size: 1.1rem;">
                    Start Teaching Today
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
