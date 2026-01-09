document.addEventListener('DOMContentLoaded', function() {
    // Function to add "visible" class when in view
    const cards = document.querySelectorAll('.card');
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5  // Trigger when 50% of the card is in view
    };

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');  // Add visible class to animate
            }
        });
    }, options);

    // Observe each card
    cards.forEach(card => observer.observe(card));
});
document.addEventListener('DOMContentLoaded', function() {
    // Function to add "visible" class when in view
    const cards = document.querySelectorAll('.content-container'); // Adjust the selector to target the content container
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5  // Trigger when 50% of the card is in view
    };

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');  // Add visible class to animate
            }
        });
    }, options);

    // Observe each content container (card)
    cards.forEach(card => observer.observe(card));
});
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-item');
    const cards = document.querySelectorAll('.content-container');
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5  // Trigger when 50% of the card is in view
    };

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            const targetTab = document.querySelector(`.tab-item[data-target="${entry.target.id}"]`);
            if (entry.isIntersecting) {
                // Add the active class to the corresponding tab
                targetTab.classList.add('active');
            } else {
                // Remove the active class when the card is not in view
                targetTab.classList.remove('active');
            }
        });
    }, options);

    // Observe each content container (card)
    cards.forEach(card => observer.observe(card));

    // Add click functionality to the tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(tab => tab.classList.remove('active'));
            // Add active class to the clicked tab
            tab.classList.add('active');

            // Scroll to the corresponding content container
            const targetId = tab.getAttribute('data-target');
            const targetCard = document.getElementById(targetId);
            targetCard.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-item');
    const containers = document.querySelectorAll('.content-container');

    // Add event listeners to tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = tab.getAttribute('data-target');

            // Remove active class from all tabs and containers
            tabs.forEach(t => t.classList.remove('active'));
            containers.forEach(c => c.classList.remove('active'));

            // Add active class to the clicked tab
            tab.classList.add('active');

            // Show the corresponding container by adding the active class
            const targetContainer = document.getElementById(targetId);
            targetContainer.classList.add('active');
        });
    });
});
