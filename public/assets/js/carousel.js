// Carousel functionality
class Carousel {
    constructor(element) {
        this.carousel = element;
        this.slides = this.carousel.querySelectorAll('.carousel-slide');
        this.dots = this.carousel.querySelectorAll('.carousel-dot');
        this.currentIndex = 0;
        this.autoPlayInterval = null;

        if (this.slides.length > 0) {
            this.init();
        }
    }

    init() {
        // Add click handlers to dots
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Add click handlers to arrow buttons
        const prevBtn = this.carousel.querySelector('.carousel-arrow.prev');
        const nextBtn = this.carousel.querySelector('.carousel-arrow.next');

        if (prevBtn) prevBtn.addEventListener('click', () => this.prevSlide());
        if (nextBtn) nextBtn.addEventListener('click', () => this.nextSlide());

        // Start auto-play
        this.startAutoPlay();

        // Pause on hover
        this.carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
        this.carousel.addEventListener('mouseleave', () => this.startAutoPlay());
    }

    showSlide(index) {
        if (index >= this.slides.length) this.currentIndex = 0;
        if (index < 0) this.currentIndex = this.slides.length - 1;

        this.slides.forEach(slide => slide.classList.remove('active'));
        this.dots.forEach(dot => dot.classList.remove('active'));

        this.slides[this.currentIndex].classList.add('active');
        if (this.dots[this.currentIndex]) {
            this.dots[this.currentIndex].classList.add('active');
        }
    }

    goToSlide(index) {
        this.currentIndex = index;
        this.showSlide(this.currentIndex);
        this.stopAutoPlay();
        this.startAutoPlay();
    }

    nextSlide() {
        this.currentIndex++;
        this.showSlide(this.currentIndex);
    }

    prevSlide() {
        this.currentIndex--;
        this.showSlide(this.currentIndex);
    }

    startAutoPlay() {
        if (this.slides.length <= 1) return;
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, 5000); // Change slide every 5 seconds
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
}

// Initialize carousel when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const carouselElement = document.querySelector('.hero-carousel');
    if (carouselElement) {
        new Carousel(carouselElement);
    }
});
