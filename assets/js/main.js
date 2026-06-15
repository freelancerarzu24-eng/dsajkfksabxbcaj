document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('liveSearch');
    const searchSuggestions = document.getElementById('searchSuggestions');

    if (liveSearch) {
        liveSearch.addEventListener('input', function() {
            const q = this.value;
            if (q.length > 2) {
                fetch(`${SITE_URL}/api/search.php?q=${q}`)
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() !== '') {
                            searchSuggestions.innerHTML = data;
                            searchSuggestions.classList.remove('d-none');
                        } else {
                            searchSuggestions.classList.add('d-none');
                        }
                    });
            } else {
                searchSuggestions.classList.add('d-none');
            }
        });

        document.addEventListener('click', function(e) {
            if (!liveSearch.contains(e.target)) {
                searchSuggestions.classList.add('d-none');
            }
        });
    }
});
