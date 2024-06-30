<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
    
    <a class="navbar-brand" href="#" data-toggle="modal" data-target="#channelModal">
            <img id="currentChannelLogo" src="{{ isset($currentChannel) ? Storage::url($currentChannel->logo) : asset('images/mtv-logo-png-this-image-rendered-as-png-in-other-widths-200px-500px-500-2244730186.png') }}" alt="Channel Logo" style="height: 40px; margin-right: 10px;">
        </a>
        <a class="nav-link" href="{{ route('home') }}">Home  </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('musicVideos.create') }}">
                        <i class="material-icons">videocam</i> Ajouter un vidéoclip
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('filmSchedules.create') }}">
                        <i class="material-icons">movie</i> Ajouter une plage horaire de films
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tvShowSchedules.create') }}">
                        <i class="material-icons">tv</i> Ajouter une plage horaire de TV Show
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tvPlayer.show') }}">
                        <i class="material-icons">tv</i> Téléviseur
                    </a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 ml-auto" action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input class="form-control form-control-sm rounded-pill" type="search" placeholder="Recherche" aria-label="Search" name="query" style="padding: 10px 20px; font-size: 14px;">
                    <div class="input-group-prepend">
                        <button type="submit" class="input-group-text bg-transparent border-0" style="font-size: 14px;">
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                </div>
            </form>
           
                 
            <ul class="navbar-nav ml-3">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="material-icons">language</i> FR
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="material-icons">account_circle</i> Compte
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="channelModal" tabindex="-1" role="dialog" aria-labelledby="channelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="channelModalLabel">Sélectionner un canal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach($channels as $channel)
                        <li class="list-group-item d-flex align-items-center">
                            <img src="{{ Storage::url($channel->logo) }}" alt="{{ $channel->name }}" class="img-thumbnail rounded-circle mr-2" style="height: 40px;">
                            <span>{{ $channel->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="selectChannelButton">Sélectionner</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Script AJAX pour changer de canal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.list-group-item').forEach(function(element) {
            element.addEventListener('click', function() {
                var channelId = this.getAttribute('data-id');
                var logoUrl = this.querySelector('img').getAttribute('src');

                fetch('{{ route('change.channel') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ channel_id: channelId })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('currentChannelLogo').src = logoUrl;
                    $('#channelModal').modal('hide');
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>




<!-- Styles -->
<style>

.modal-content {
        border-radius: 10px;
        overflow: hidden;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .close {
        font-size: 1.5rem;
    }

    .list-group-item {
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .img-thumbnail {
        width: 40px;
        height: 40px;
    }

    .modal-footer {
        border-top: none;
    }

    #selectChannelButton {
        display: none; /* Caché car on sélectionne directement dans la liste */
    }
    .material-icons {
        font-weight: 300;
        color: #666; /* Gris foncé */
    }
    .navbar-nav .nav-link {
        display: flex;
        align-items: center;
        font-size: 14px;
    }
    .navbar-nav .nav-link i {
        margin-right: 8px;
    }
    .form-control-radius-radius, .btn-outline-secondary {
        border-radius: 50px; /* Bordure complètement arrondie */
    }
    .form-inline .input-group {
        width: 300px;
    }
    .input-group-text {
        cursor: pointer;
    }
    .input-group-text .material-icons {
        margin: 0;
    }
    .navbar .container-fluid {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
