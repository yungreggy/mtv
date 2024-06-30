document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM complètement chargé');

    var addGenreButton = document.getElementById('addGenreButton');
    if (addGenreButton) {
        console.log('Bouton + trouvé');
        addGenreButton.addEventListener('click', function() {
            console.log('Bouton + cliqué');
            var addGenreDiv = document.getElementById('addGenreDiv');
            console.log('État actuel de addGenreDiv:', addGenreDiv.style.display);
            if (addGenreDiv.style.display === 'none') {
                addGenreDiv.style.display = 'block';
                console.log('addGenreDiv affiché');
            } else {
                addGenreDiv.style.display = 'none';
                console.log('addGenreDiv caché');
            }
        });
    } else {
        console.error('Bouton + non trouvé');
    }

    var linkGenreForm = document.getElementById('linkGenreForm');
    if (linkGenreForm) {
        console.log('Formulaire de lien de genre trouvé');
        linkGenreForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulaire soumis');

            let form = e.target;
            let formData = new FormData(form);
            console.log('Données du formulaire:', formData);

            fetch('{{ route("musicVideos.linkGenre", $musicVideo->id) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                console.log('Réponse reçue:', response);
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                if (data.success) {
                    // Ajouter le nouveau genre à la liste des genres
                    console.log('Genre ajouté avec succès:', data.genre);
                    let genreBadge = document.createElement('a');
                    genreBadge.href = '/genres/' + data.genre.id;
                    genreBadge.className = 'badge badge-secondary';
                    genreBadge.textContent = data.genre.name;

                    document.querySelector('p strong').insertAdjacentElement('afterend', genreBadge);

                    // Cacher la div et réinitialiser le formulaire
                    var addGenreDiv = document.getElementById('addGenreDiv');
                    addGenreDiv.style.display = 'none';
                    form.reset();
                    console.log('Formulaire réinitialisé et div cachée');

                    // Rafraîchir la page
                    console.log('Rafraîchissement de la page');
                    location.reload();
                } else {
                    // Gérer les erreurs
                    console.log('Erreur lors de la liaison du genre');
                    alert('Erreur lors de la liaison du genre');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    } else {
        console.error('Formulaire de lien de genre non trouvé');
    }
});