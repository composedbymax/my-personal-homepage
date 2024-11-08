document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const projectCards = document.querySelectorAll('.project-card');
    
    ['input', 'keyup'].forEach(eventType => {
        searchInput.addEventListener(eventType, () => updateSearch());
    });

    function updateSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // Show all cards if search is empty
        if (!searchTerm) {
            projectCards.forEach(card => {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            });
            return;
        }

        projectCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const description = card.querySelector('.card-description').textContent.toLowerCase();
            const tags = Array.from(card.querySelectorAll('.tag'))
                .map(tag => tag.textContent.toLowerCase());
            
            const isMatch = title.includes(searchTerm) || 
                          description.includes(searchTerm) ||
                          tags.some(tag => tag.includes(searchTerm));
            
            if (isMatch) {
                card.style.display = 'block';
                requestAnimationFrame(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    if (!searchInput.value.toLowerCase().trim().length || 
                        !title.includes(searchInput.value.toLowerCase().trim())) {
                        card.style.display = 'none';
                    }
                }, 200);
            }
        });
    }

    projectCards.forEach(card => {
        card.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        card.style.opacity = '1';
    });
});
