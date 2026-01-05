@extends('layouts.app')

@section('title', 'Home - Friends of Children Ministries')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div style="display: inline-block; background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 20px; margin-bottom: 1rem; font-size: 0.9rem; font-weight: 600;">
                <i class="fas fa-star" style="color: var(--secondary-color);"></i> Supporting Children Worldwide
            </div>
            <h1>Welcome to Friends of Children Ministries</h1>
            <p>A non-profit organization dedicated to serving children in need, providing resources, and supporting families worldwide. </p>
            <div class="hero-buttons">
                <a href="{{ route('about') }}" class="btn btn-primary">
                    <i class="fas fa-info-circle"></i> Learn More About Us
                </a>
                <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i> Free Resources
                </a>
            </div>
            <div style="margin-top: 2rem; display: flex; align-items: center; gap: 2rem; opacity: 0.9; font-size: 0.9rem;">
                <div><i class="fas fa-check-circle" style="color: var(--secondary-color);"></i> 100% Free</div>
                <div><i class="fas fa-check-circle" style="color: var(--secondary-color);"></i> Get Involved</div>
                <div><i class="fas fa-check-circle" style="color: var(--secondary-color);"></i> Make a Difference</div>
            </div>
        </div>
        <div class="hero-image">
            <div style="position: relative;">
                <i class="fas fa-child" style="font-size: 8rem; opacity: 0.3;"></i>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.1); border-radius: 50%; padding: 1.5rem;">
                    <i class="fas fa-heart" style="font-size: 2rem; color: var(--secondary-color);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section style="padding: 3rem 0; background: var(--background-white); border-bottom: 3px solid var(--secondary-color);">
    <div class="container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 1rem;">Find Resources for Your Ministry</h2>
            <p style="color: var(--text-light); margin-bottom: 2rem;">Search through our collection of lessons, activities, and resources.</p>
            <div style="position: relative; max-width: 600px; margin: 0 auto;">
                <input type="text" placeholder="Search for activities, lessons, stories..." style="width: 100%; padding: 1.25rem 4rem 1.25rem 1.5rem; border: 3px solid var(--border-color); border-radius: 50px; font-size: 1.1rem; box-shadow: var(--shadow-medium);">
                <button style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: var(--primary-color); color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer;">
                    <i class="fas fa-search" style="font-size: 1.2rem;"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="quick-access">
    <div class="container">
        <h2>Get Involved Today</h2>
        <p style="text-align: center; color: var(--text-light); margin-bottom: 3rem; font-size: 1.1rem;">Discover ways you can contribute to our mission</p>
        <div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="access-card" style="border-left: 4px solid var(--secondary-color);">
                <div class="card-icon" style="background: linear-gradient(135deg, var(--secondary-color), #B8860B);">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h3>Volunteer</h3>
                <p>Join our team of dedicated volunteers and make a direct impact on the lives of children.</p>
                <a href="#" class="card-link">Become a Volunteer <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="access-card" style="border-left: 4px solid var(--primary-color);">
                <div class="card-icon" style="background: linear-gradient(135deg, var(--primary-color), #654321);">
                    <i class="fas fa-donate"></i>
                </div>
                <h3>Donate</h3>
                <p>Your financial support helps us provide essential resources and programs for children.</p>
                <a href="#" class="card-link">Make a Donation <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="access-card" style="border-left: 4px solid var(--accent-color);">
                <div class="card-icon" style="background: linear-gradient(135deg, var(--accent-color), #A0522D);">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h3>Spread the Word</h3>
                <p>Share our mission with your friends, family, and community to raise awareness.</p>
                <a href="#" class="card-link">Share Our Story <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="access-card" style="border-left: 4px solid #228B22;">
                <div class="card-icon" style="background: linear-gradient(135deg, #228B22, #006400);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Events</h3>
                <p>Participate in our upcoming events and fundraisers to support our cause.</p>
                <a href="#" class="card-link">View Upcoming Events <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<section style="padding: 4rem 0; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); color: white;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: white; margin-bottom: 1rem;">Our Impact</h2>
            <p style="opacity: 0.95; font-size: 1.1rem;">See how we are making a difference in the lives of children</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-child"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">10,000+ Children Served</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">We have provided support to thousands of children in various communities.</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-globe-americas"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">20+ Countries Reached</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">Our programs have reached children and families in over 20 countries.</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-school"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">50+ Schools & Orphanages</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">We partner with schools and orphanages to provide educational and material support.</p>
            </div>
        </div>
    </div>
</section>

<section style="padding: 4rem 0; background: var(--background-white);">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 1rem;">What People Are Saying</h2>
        <p style="text-align: center; color: var(--text-light); margin-bottom: 3rem; font-size: 1.1rem;">Real feedback from our partners and volunteers</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            <div style="background: var(--background-white); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); position: relative; border-top: 4px solid var(--secondary-color);">
                <div style="position: absolute; top: -12px; left: 2rem; background: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    VOLUNTEER
                </div>
                <div style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p style="font-style: italic; margin-bottom: 1.5rem; line-height: 1.7; font-size: 1.05rem;">
                    "Volunteering with Friends of Children Ministries has been a life-changing experience. Seeing the smiles on the children's faces is the greatest reward."
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--secondary-color), #B8860B); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        SM
                    </div>
                    <div>
                        <strong style="color: var(--primary-color);">Sarah Martinez</strong>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">Volunteer</div>
                    </div>
                </div>
            </div>

            <div style="background: var(--background-white); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); position: relative; border-top: 4px solid var(--primary-color);">
                <div style="position: absolute; top: -12px; left: 2rem; background: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    PARTNER
                </div>
                <div style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p style="font-style: italic; margin-bottom: 1.5rem; line-height: 1.7; font-size: 1.05rem;">
                    "The resources provided by Friends of Children Ministries have been invaluable to our school. We are so grateful for their support."
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), #654321); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        JT
                    </div>
                    <div>
                        <strong style="color: var(--primary-color);">James Thompson</strong>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">School Principal</div>
                    </div>
                </div>
            </div>

            <div style="background: var(--background-white); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); position: relative; border-top: 4px solid var(--accent-color);">
                <div style="position: absolute; top: -12px; left: 2rem; background: var(--accent-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    DONOR
                </div>
                <div style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p style="font-style: italic; margin-bottom: 1.5rem; line-height: 1.7; font-size: 1.05rem;">
                    "It's a blessing to be able to contribute to such a wonderful organization. I know that my donations are making a real difference."
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-color), #A0522D); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        LW
                    </div>
                    <div>
                        <strong style="color: var(--primary-color);">Lisa Williams</strong>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">Donor</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div style="text-align: center; margin-top: 3rem; padding: 3rem; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 20px; color: white;">
            <h3 style="color: white; margin-bottom: 1rem; font-size: 2rem;">Ready to Make a Difference?</h3>
            <p style="opacity: 0.95; margin-bottom: 2rem; font-size: 1.1rem;">Join us in our mission to serve children in need.</p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="#" class="btn" style="background: white; color: var(--primary-color); padding: 1rem 2rem; font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-hands-helping"></i> Get Involved
                </a>
                <a href="#" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; padding: 1rem 2rem; font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-donate"></i> Donate Now
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
