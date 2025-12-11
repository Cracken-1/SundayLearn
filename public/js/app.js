// SundayLearn - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile Navigation Toggle
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            const icon = this.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                const icon = navToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close menu when clicking a link
        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                const icon = navToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    }

    // Lesson Panel Functionality
    const lessonPanel = document.getElementById('lessonPanel');
    const closePanelBtn = document.getElementById('closePanel');
    const openLessonBtns = document.querySelectorAll('.open-lesson');

    // Open lesson panel
    openLessonBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lessonId = this.getAttribute('data-lesson-id');
            openLessonPanel(lessonId);
        });
    });

    // Close lesson panel
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', function() {
            closeLessonPanel();
        });
    }

    // Close panel when clicking outside
    document.addEventListener('click', function(e) {
        if (lessonPanel && lessonPanel.classList.contains('active')) {
            if (!lessonPanel.contains(e.target) && !e.target.closest('.open-lesson')) {
                closeLessonPanel();
            }
        }
    });

    // ESC key to close panel
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lessonPanel && lessonPanel.classList.contains('active')) {
            closeLessonPanel();
        }
    });

    // Filter functionality
    const filterLinks = document.querySelectorAll('.filter-list a[data-age], .filter-list a[data-media]');
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filterType = this.getAttribute('data-age') || this.getAttribute('data-media');
            filterLessons(filterType, this.getAttribute('data-age') ? 'age' : 'media');
        });
    });

    // Search functionality
    const searchInput = document.getElementById('lessonSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchLessons(this.value);
        });
    }

    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Functions
    function openLessonPanel(lessonId) {
        if (!lessonPanel) return;

        // Show loading state
        const panelContent = document.getElementById('panelContent');
        const panelTitle = document.getElementById('panelTitle');
        
        panelTitle.textContent = 'Loading...';
        panelContent.innerHTML = '<div class="loading">Loading lesson content...</div>';
        
        lessonPanel.classList.add('active');

        // Fetch lesson content (simulated for now)
        setTimeout(() => {
            loadLessonContent(lessonId);
        }, 500);
    }

    function closeLessonPanel() {
        if (lessonPanel) {
            lessonPanel.classList.remove('active');
        }
    }

    function loadLessonContent(lessonId) {
        // Navigate to the lesson detail page instead of loading in panel
        window.location.href = `/lessons/${lessonId}`;
    }

    function filterLessons(filterValue, filterType) {
        const lessonCards = document.querySelectorAll('.lesson-card');
        
        lessonCards.forEach(card => {
            let shouldShow = true;
            
            if (filterType === 'age') {
                const ageGroupElement = card.querySelector('.age-group');
                if (ageGroupElement) {
                    const ageGroup = ageGroupElement.textContent;
                    shouldShow = ageGroup.toLowerCase().includes(filterValue.toLowerCase());
                }
            } else if (filterType === 'media') {
                const hasVideo = card.querySelector('.media-icon.video');
                const hasAudio = card.querySelector('.media-icon.audio');
                
                if (filterValue === 'video') {
                    shouldShow = hasVideo !== null;
                } else if (filterValue === 'audio') {
                    shouldShow = hasAudio !== null;
                } else if (filterValue === 'text') {
                    shouldShow = !hasVideo && !hasAudio;
                }
            }
            
            card.style.display = shouldShow ? 'block' : 'none';
        });
    }

    function searchLessons(searchTerm) {
        const lessonCards = document.querySelectorAll('.lesson-card');
        const term = searchTerm.toLowerCase();
        
        lessonCards.forEach(card => {
            const title = card.querySelector('h3') ? card.querySelector('h3').textContent.toLowerCase() : '';
            const scriptureElement = card.querySelector('.scripture');
            const themeElement = card.querySelector('.theme');
            
            const scripture = scriptureElement ? scriptureElement.textContent.toLowerCase() : '';
            const theme = themeElement ? themeElement.textContent.toLowerCase() : '';
            
            const matches = title.includes(term) || scripture.includes(term) || theme.includes(term);
            card.style.display = matches ? 'block' : 'none';
        });
    }

    function initializeMediaPlayers() {
        // Initialize custom video/audio player controls if needed
        const videoPlayers = document.querySelectorAll('video');
        const audioPlayers = document.querySelectorAll('audio');
        
        videoPlayers.forEach(video => {
            video.addEventListener('loadstart', function() {
                console.log('Video loading started');
            });
        });
        
        audioPlayers.forEach(audio => {
            audio.addEventListener('loadstart', function() {
                console.log('Audio loading started');
            });
        });
    }

    // Download functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('download-btn') || e.target.closest('.download-btn')) {
            const downloadBtn = e.target.classList.contains('download-btn') ? e.target : e.target.closest('.download-btn');
            
            // Add download tracking or analytics here
            console.log('Download initiated:', downloadBtn.getAttribute('href'));
        }
    });

    // Bulk download functionality
    document.addEventListener('click', function(e) {
        if (e.target.textContent.includes('Download All Resources')) {
            e.preventDefault();
            
            // Collect all download links in the current lesson
            const downloadLinks = document.querySelectorAll('.download-btn');
            
            if (downloadLinks.length > 0) {
                // Create a temporary form or use a service to zip files
                alert('Preparing bulk download... This feature will be implemented with backend support.');
                
                // In a real implementation, you would:
                // 1. Send a request to the server to create a zip file
                // 2. Download the zip file
                // 3. Show progress indicator
            }
        }
    });

    // Add loading states for better UX
    function showLoading(element) {
        element.innerHTML = '<div class="loading-spinner"></div><p>Loading...</p>';
    }

    function hideLoading() {
        const loadingElements = document.querySelectorAll('.loading-spinner');
        loadingElements.forEach(el => el.remove());
    }

    // Initialize tooltips or help text
    const helpButtons = document.querySelectorAll('[data-help]');
    helpButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const helpText = this.getAttribute('data-help');
            showTooltip(this, helpText);
        });
    });

    function showTooltip(element, text) {
        // Simple tooltip implementation
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = text;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + 'px';
        tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
        
        setTimeout(() => {
            tooltip.remove();
        }, 3000);
    }
});

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export functions for use in other scripts if needed
window.SundayLearn = {
    openLessonPanel: function(lessonId) {
        // Public API for opening lesson panels
        const event = new CustomEvent('openLesson', { detail: { lessonId } });
        document.dispatchEvent(event);
    }
};