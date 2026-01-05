@extends('layouts.app')

@section('title', 'About Us - Friends of Children Ministries')

@section('content')
<div style="padding: 3rem 0; background: var(--background-light);">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h1 style="color: var(--primary-color); font-size: 3rem; margin-bottom: 1rem;">About Friends of Children Ministries</h1>
                <p style="font-size: 1.3rem; color: var(--text-light);">A non-profit organization dedicated to serving children in need and supporting families worldwide.</p>
            </div>

            <div style="background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Our Mission</h2>
                <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                    Friends of Children Ministries is a faith-based non-profit organization with a mission to bring hope, help, and healing to children and families in need around the world. We are committed to demonstrating God's love through practical acts of service and support.
                </p>
                <p style="line-height: 1.8;">
                    We believe that every child is precious and deserves the opportunity to thrive. Our work focuses on providing essential resources, educational support, and a nurturing environment to help children reach their full potential.
                </p>
            </div>

            <div style="background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">What We Do</h2>
                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-hands-helping" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Humanitarian Aid</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                We provide food, clean water, medical supplies, and other essential aid to children and families in crisis.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book-reader" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Educational Support</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                We partner with schools and learning centers to provide educational resources, scholarships, and mentorship programs.
                            </p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 50px; height: 50px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-home" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.5rem;">Orphanage Support</h3>
                            <p style="color: var(--text-light); line-height: 1.6;">
                                We provide support to orphanages, ensuring that children have a safe and loving home.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: var(--background-white); padding: 3rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Our Story</h2>
                <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                    Friends of Children Ministries was founded by a group of individuals who felt a strong calling to serve children in need. What started as a small outreach project has grown into a global ministry, impacting the lives of thousands of children and families.
                </p>
                <p style="line-height: 1.8;">
                    Our journey is one of faith, compassion, and a relentless commitment to making a positive difference in the world, one child at a time. We are grateful for the support of our partners, donors, and volunteers who make our work possible.
                </p>
            </div>

            <div style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); padding: 3rem; border-radius: 10px; text-align: center; color: white;">
                <h2 style="color: white; margin-bottom: 1rem;">Join Our Mission</h2>
                <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.95;">
                    Become a part of our mission to bring hope and help to children in need.
                </p>
                <a href="#" class="btn" style="background: white; color: var(--primary-color); padding: 1rem 2rem; font-size: 1.1rem;">
                    Get Involved
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
