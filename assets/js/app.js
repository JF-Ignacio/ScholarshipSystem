document.addEventListener("DOMContentLoaded", () => {
    // Create an observer instances to watch our cards cross into view
    const observerOptions = {
        root: null, // Tracks target visibility relative to browser viewport
        threshold: 0.15 // Triggers the animation callback when 15% of the card is visible
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            // When an element hits our visual target frame threshold
            if (entry.isIntersecting) {
                entry.target.classList.add("appear-visible");
                observer.unobserve(entry.target); // Optional: Stops watching once animated to save memory
            }
        });
    }, observerOptions);

    // Grab all cards inside your scholarship section and attach the listener
    const animationTargets = document.querySelectorAll(".scholarships .card");
    animationTargets.forEach(card => scrollObserver.observe(card));
});

// FOR COLLEGES 

document.addEventListener("DOMContentLoaded", () => {
    const ob = {
        root: null,
        threshold: 0.15
    };

    const collegeObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add("appear-visible-college");
                observer.unobserve(entry.target);
            }
        });
    }, ob);

    const animationCollege = document.querySelectorAll(".colleges .card");
    animationCollege.forEach(card => collegeObserver.observe(card));
});

document.addEventListener("DOMContentLoaded", () => {
    const ob = {
        root: null,
        threshold: 0.20
    };

    const aboutUs = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add("appear-visible-aboutUs");
                observer.unobserve(entry.target);
            }
        });
    }, ob);

    const animationAbout = document.querySelectorAll(".about-carousel, .about-us .card, .about-us .card-body, .about-us .v-m-container .card-body");
    animationAbout.forEach(card => aboutUs.observe(card));
});


document.addEventListener("DOMContentLoaded", () => {
    const ob = {
        root: null,
        threshold: 0.20
    };

    const index = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add("appear-visible-index");
                observer.unobserve(entry.target);
            }
        });
    }, ob);

    const animationIndex = document.querySelectorAll(".index-head .info-box");
    animationIndex.forEach(card => index.observe(card));
});


document.addEventListener("DOMContentLoaded", () => {
    const ob = {
        root: null,
        threshold: 0.20
    };

    const hero = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add("appear-visible-hero");
                observer.unobserve(entry.target);
            }
        });
    }, ob);

    const animationHero = document.querySelectorAll(".index-hero .row");
    animationHero.forEach(card => hero.observe(card));
});

document.addEventListener("DOMContentLoaded", () => {
    const ob = {
        root: null,
        threshold: 0.15
    };

    const faqsObersver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                entry.target.classList.add("appear-visible-faqs");
                observer.unobserve(entry.target);
            }
        });
    }, ob);

    const animateFAQs = document.querySelectorAll(".FAQs .accordion-container");
    animateFAQs.forEach(card => faqsObersver.observe(card));
})