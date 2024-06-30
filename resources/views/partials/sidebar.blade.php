<div class="sidebar d-flex flex-column p-3 bg-light shadow-sm" style="width: 250px;">
    <h5 class="font-weight-bold text-dark text-left">Menu</h5>
    <ul class="nav nav-pills flex-column mb-auto" style="font-size: 14px;">
        <li class="nav-item">
            <a href="#dashboardSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">dashboard</i>
                <span class="ml-2">Dashboard</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="dashboardSubmenu">
                <li><a href="#" class="nav-link sub-menu-link text-muted pl-4">Overview</a></li>
                <li><a href="#" class="nav-link sub-menu-link text-muted pl-4">Settings</a></li>
                <li><a href="#" class="nav-link sub-menu-link text-muted pl-4">Users</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#channelsSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">tv</i>
                <span class="ml-2">Channels</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="channelsSubmenu">
                <li><a href="{{ route('channels.index') }}" class="nav-link sub-menu-link text-muted pl-4">Index</a></li>
                <li><a href="{{ route('channels.create') }}" class="nav-link sub-menu-link text-muted pl-4">Ajouter</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#musicManagementSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">music_note</i>
                <span class="ml-2">Music Management</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="musicManagementSubmenu">
                <li><a href="{{ route('musicVideos.index') }}" class="nav-link sub-menu-link text-muted pl-4">Music Videos</a></li>
                <li><a href="{{ route('artists.index') }}" class="nav-link sub-menu-link text-muted pl-4">Artists</a></li>
                <li><a href="{{ route('albums.index') }}" class="nav-link sub-menu-link text-muted pl-4">Albums</a></li>
                <li><a href="{{ route('labels.index') }}" class="nav-link sub-menu-link text-muted pl-4">Labels</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#mediaManagementSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">movie</i>
                <span class="ml-2">Media Management</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="mediaManagementSubmenu">
                <li><a href="{{ route('films.index') }}" class="nav-link sub-menu-link text-muted pl-4">Films</a></li>
                <li><a href="{{ route('tvShows.index') }}" class="nav-link sub-menu-link text-muted pl-4">TV Shows</a></li>
                <li><a href="{{ route('directors.index') }}" class="nav-link sub-menu-link text-muted pl-4">Directors</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#commercialsSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">attach_money</i>
                <span class="ml-2">Commercials</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="commercialsSubmenu">
                <li><a href="{{ route('pubs.index') }}" class="nav-link sub-menu-link text-muted pl-4">Index des pubs</a></li>
                <li><a href="{{ route('blocPubs.index') }}" class="nav-link sub-menu-link text-muted pl-4">Blocs Pubs</a></li>
                <li><a href="{{ route('brandsStores.index') }}" class="nav-link sub-menu-link text-muted pl-4">Brands & Stores</a></li>
                <li><a href="{{ route('bumpers.index') }}" class="nav-link sub-menu-link text-muted pl-4">Bumpers</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#programsSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">schedule</i>
                <span class="ml-2">Programs</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="programsSubmenu">
                <li><a href="{{ route('programs.index') }}" class="nav-link sub-menu-link text-muted pl-4">Index</a></li>
                <li><a href="{{ route('programSchedules.index') }}" class="nav-link sub-menu-link text-muted pl-4">Schedules</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#genresSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">category</i>
                <span class="ml-2">Genres</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="genresSubmenu">
                <li><a href="{{ route('genres.index') }}" class="nav-link sub-menu-link text-muted pl-4">Index</a></li>
                <li><a href="{{ route('genres.create') }}" class="nav-link sub-menu-link text-muted pl-4">Ajouter</a></li>
            </ul>
        </li>


        <li class="nav-item">
            <a href="#tagsSubmenu" data-toggle="collapse" class="nav-link link-dark d-flex align-items-center" aria-expanded="false">
                <i class="material-icons">tag</i>
                <span class="ml-2">Tags</span>
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul class="collapse list-unstyled sub-menu text-left" id="tagsSubmenu">
                <li><a href="{{ route('tags.index') }}" class="nav-link sub-menu-link text-muted pl-4">Index</a></li>
                <li><a href="{{ route('tags.create') }}" class="nav-link sub-menu-link text-muted pl-4">Ajouter</a></li>
            </ul>
        </li>


    </ul>
</div>

<!-- Styles -->
<style>
    .material-icons {
        font-weight: 300;
        color: #666; /* Gris foncé */
    }
    .nav-pills .nav-link {
        color: #333; /* Gris foncé */
        font-size: 14px; /* Police plus petite */
        text-align: left; /* Aligner à gauche */
    }
    .nav-pills .nav-link:hover {
        color: #000; /* Noircir au survol */
    }
    .sub-menu-link {
        font-size: 14px; /* Police plus petite */
        text-align: left; /* Aligner à gauche */
    }
</style>


