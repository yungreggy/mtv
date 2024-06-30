<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicVideoController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\PubController;
use App\Http\Controllers\BlocPubController;
use App\Http\Controllers\BrandStoreController;
use App\Http\Controllers\BumperController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramDateController;
use App\Http\Controllers\ProgramScheduleController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\TvShowEpisodeController;
use App\Http\Controllers\FilmController;  
use App\Http\Controllers\GenreController; 
use App\Http\Controllers\TvShowSeasonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TvPlayerController;
use App\Http\Controllers\FilmScheduleController;
use App\Http\Controllers\MusicVideoScheduleController;
use App\Http\Controllers\TvShowScheduleController;
use App\Http\Controllers\ReplayScheduleController;
use App\Http\Controllers\BlocPubScheduleController;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Http\Controllers\VideoController;

Route::get('videos/stream/{type}/{filePath}', [VideoController::class, 'stream'])->where('filePath', '.*')->name('videos.stream');








use Illuminate\Http\Request;
Route::resource('blocPubs', BlocPubController::class);

// Route de la page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route pour la barre de recherche globale
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

// Routes pour les Music Videos
Route::resource('musicVideos', MusicVideoController::class);

// Routes pour les Artistes
Route::resource('artists', ArtistController::class);

// Routes pour les Albums
Route::resource('albums', AlbumController::class);

// Routes pour les Labels
Route::resource('labels', LabelController::class);

// Routes pour les Genres
Route::resource('genres', GenreController::class);

// Routes pour les Réalisateurs
Route::resource('directors', DirectorController::class);

// Routes pour les Chaînes
Route::resource('channels', ChannelController::class);

Route::resource('tvShowSchedules', TvShowScheduleController::class);


Route::resource('programSchedules', ProgramScheduleController::class);

// Routes pour les Pubs
Route::resource('pubs', PubController::class);

// Routes pour les BlocPubs
Route::resource('blocPubs', BlocPubController::class);

// Routes pour les Brands Stores
Route::resource('brandsStores', BrandStoreController::class);

// Routes pour les Bumpers
Route::resource('bumpers', BumperController::class);

// Routes pour les Programmes
Route::resource('programs', ProgramController::class);

// Routes pour les Films
Route::resource('films', FilmController::class);

// Routes pour les Émissions de Télévision
Route::resource('tvShows', TvShowController::class);

// Routes pour les Saisons des Émissions de Télévision
Route::resource('tvShows.seasons', TvShowSeasonController::class)->shallow();

// Routes pour les Épisodes des Émissions de Télévision
Route::resource('tvShows.episodes', TvShowEpisodeController::class)->shallow();






// Routes pour les Vidéos Musicales dans les Program Schedules
Route::post('programSchedules/{schedule}/musicVideos', [MusicVideoScheduleController::class, 'addMusicVideo'])->name('programSchedules.musicVideos.store');
Route::delete('programSchedules/{schedule}/musicVideos/{musicVideo}', [MusicVideoScheduleController::class, 'removeMusicVideo'])->name('programSchedules.musicVideos.destroy');
Route::post('programSchedules/{schedule}/musicVideos/{musicVideo}/{direction}', [MusicVideoScheduleController::class, 'moveMusicVideo'])->name('programSchedules.musicVideos.move');

// Routes pour les Émissions de Télévision dans les Program Schedules
Route::post('programSchedules/{schedule}/tvShows', [TvShowScheduleController::class, 'store'])->name('programSchedules.tvShows.store');
Route::delete('programSchedules/{schedule}/tvShows/{tvShow}', [TvShowScheduleController::class, 'destroy'])->name('programSchedules.tvShows.destroy');

// Routes pour les Rediffusions dans les Program Schedules
Route::post('programSchedules/{schedule}/replays', [ReplayScheduleController::class, 'addReplay'])->name('programSchedules.replays.store');
Route::delete('programSchedules/{schedule}/replays/{replay}', [ReplayScheduleController::class, 'deleteReplay'])->name('programSchedules.replays.destroy');


// Routes pour les Program Dates
Route::resource('programDates', ProgramDateController::class);

// Routes pour associer des Program Schedules à des Program Dates
Route::get('/programs/{program}/dates/{date}/schedules/create', [ProgramScheduleController::class, 'createWithDate'])->name('programSchedules.createWithDate');
Route::post('/programs/{program}/dates/{date}/schedules/store', [ProgramScheduleController::class, 'storeWithDate'])->name('programSchedules.storeWithDate');
Route::get('/programs/{program}/dates/{date}', [ProgramDateController::class, 'show'])->name('programDates.show');


// Routes pour gérer les genres et les miniatures
Route::post('films/{film}/linkGenre', [FilmController::class, 'linkGenre'])->name('films.linkGenre');
Route::post('/artists/{id}/upload-thumbnail', [ArtistController::class, 'uploadThumbnail'])->name('artists.uploadThumbnail');
Route::patch('/artists/{artist}/updateMainGenre', [ArtistController::class, 'updateMainGenre'])->name('artists.updateMainGenre');

// Routes pour les recherches spécifiques
Route::get('/directors/search', [DirectorController::class, 'search'])->name('directors.search');
Route::post('/albums/{album}/link-genre', [AlbumController::class, 'linkGenre'])->name('albums.linkGenre');
Route::post('/albums/{album}/uploadThumbnail', [AlbumController::class, 'uploadThumbnail'])->name('albums.uploadThumbnail');
Route::post('/musicVideos/{id}/linkGenre', [MusicVideoController::class, 'linkGenre'])->name('musicVideos.linkGenre');

// Routes pour créer des albums et vidéos musicales avec des relations
Route::get('albums/create/{artist_id}', [AlbumController::class, 'createWithArtist'])->name('albums.createWithArtist');
Route::get('musicVideos/create/{artist_id}', [MusicVideoController::class, 'createWithArtist'])->name('musicVideos.createWithArtist');
Route::get('musicVideos/createWithAlbum/{album_id}', [MusicVideoController::class, 'createWithAlbum'])->name('musicVideos.createWithAlbum');
Route::post('albums/{album}/musicVideos', [MusicVideoController::class, 'storeWithAlbum'])->name('musicVideos.storeWithAlbum');
Route::get('albums/{album}/musicVideos/create', [MusicVideoController::class, 'createWithAlbum'])->name('musicVideos.createWithAlbum');


// Routes pour les films dans les horaires de programmes
Route::get('filmSchedules/create', [FilmScheduleController::class, 'create'])->name('filmSchedules.create');

Route::post('filmSchedules', [FilmScheduleController::class, 'store'])->name('filmSchedules.store');

Route::get('filmSchedules/{schedule}', [FilmScheduleController::class, 'show'])->name('filmSchedules.show');

Route::post('programSchedules/{schedule}/deleteAllClips', [ProgramScheduleController::class, 'deleteAllClips'])->name('programSchedules.deleteAllClips');


Route::get('filmSchedules/{schedule}/editFilm/{film}', [FilmScheduleController::class, 'editFilm'])->name('filmSchedules.editFilm');

Route::put('filmSchedules/{schedule}/updateFilm/{film}', [FilmScheduleController::class, 'updateFilm'])->name('filmSchedules.updateFilm');

Route::get('film-list', [FilmController::class, 'list'])->name('films.list');


Route::get('/programs/{program}/dates/{date}', [ProgramDateController::class, 'show'])->name('programDates.show');

Route::delete('filmSchedules/{schedule}/detachAllFilms', [FilmScheduleController::class, 'detachAllFilms'])->name('filmSchedules.detachAllFilms');
Route::get('directors', [DirectorController::class, 'index'])->name('directors.index');


Route::post('filmSchedules/{schedule}/addFilm', [FilmScheduleController::class, 'addFilm']);


Route::resource('tags', TagController::class);
Route::post('/films/{film}/tags', [TagController::class, 'addFilmTag'])->name('films.addTag');
Route::delete('/films/{film}/tags/{tag}', [TagController::class, 'destroyFilmTag'])->name('films.removeTag');


Route::delete('filmSchedules/{filmSchedule}', [FilmScheduleController::class, 'destroy'])->name('filmSchedules.destroy');


Route::get('/search/year/{year}', [SearchController::class, 'searchByYear'])->name('search.year');

Route::resource('tags', TagController::class);
Route::get('filmSchedules/{schedule}/edit', [FilmScheduleController::class, 'edit'])->name('filmSchedules.edit');
Route::resource('filmSchedules', FilmScheduleController::class);



Route::resource('tvShows', TVShowController::class);
Route::resource('tvShows.seasons', TvShowSeasonController::class);
Route::resource('tvShows.episodes', TvShowEpisodeController::class);

// Route pour modifier une saison
Route::get('tvShows/{tvShow}/seasons/{season}/edit', [TvShowSeasonController::class, 'edit'])->name('tvShows.seasons.edit');

// Route pour créer un nouvel épisode
Route::get('tvShows/{tvShow}/seasons/{season}/episodes/create', [TvShowEpisodeController::class, 'create'])->name('tvShows.episodes.create');

// Route pour créer plusieurs épisodes
Route::get('tvShows/{tvShow}/seasons/{season}/episodes/createMultiple', [TvShowEpisodeController::class, 'createMultiple'])->name('tvShows.episodes.createMultiple');

// Route pour stocker un nouvel épisode
Route::post('tvShows/{tvShow}/seasons/{season}/episodes', [TvShowEpisodeController::class, 'store'])->name('tvShows.episodes.store');

// Route pour stocker plusieurs épisodes
Route::post('tvShows/{tvShow}/seasons/{season}/episodes/storeMultiple', [TvShowEpisodeController::class, 'storeMultiple'])->name('tvShows.episodes.storeMultiple');

// Route pour afficher un épisode
// Route::get('tvShows/{tvShow}/seasons/{season}/episodes/{episode}', [TvShowEpisodeController::class, 'show'])->name('tvShows.episodes.show');

// Route pour modifier un épisode
Route::get('tvShows/{tvShow}/seasons/{season}/episodes/{episode}/edit', [TvShowEpisodeController::class, 'edit'])->name('tvShows.episodes.edit');

// Route pour mettre à jour un épisode
Route::put('tvShows/{tvShow}/seasons/{season}/episodes/{episode}', [TvShowEpisodeController::class, 'update'])->name('tvShows.episodes.update');

// Route pour supprimer un épisode
Route::delete('tvShows/{tvShow}/seasons/{season}/episodes/{episode}', [TvShowEpisodeController::class, 'destroy'])->name('tvShows.episodes.destroy');

Route::get('tv-shows/{tvShow}/seasons', [TvShowController::class, 'getSeasons'])->name('tvShows.seasons');

Route::get('tvShows/{tvShow}/seasons/{season}/episodes/{episode}', [TvShowEpisodeController::class, 'show'])->name('tvShows.episodes.show');

Route::delete('tvShows/{tvShow}/seasons/{season}/episodes/{episode}', [TvShowEpisodeController::class, 'destroy'])->name('tvShows.episodes.destroy');




Route::resource('tvShows.seasons.episodes', TvShowEpisodeController::class);


Route::post('tvShows/{tvShow}/seasons/{season}/episodes/{episode}/tags', [TagController::class, 'addEpisodeTag'])->name('episodes.addTag');

Route::delete('/{entityType}/{entityId}/tags/{tagId}', 'TagController@destroyTagFromEntity')->name('tags.remove');

Route::post('pubs/{pub}/tags', [TagController::class, 'addPubTag'])->name('pubs.addTag');

Route::get('/tv-player', [TvPlayerController::class, 'show'])->name('tvPlayer.show');

Route::post('/change-channel', [App\Http\Controllers\ChannelController::class, 'changeChannel'])->name('change.channel');

// Dans routes/web.php
Route::get('programSchedules/{scheduleId}/refresh', [FilmScheduleController::class, 'refreshSelection'])->name('programSchedules.refreshSelection');

Route::get('/films/file/{id}', [FilmController::class, 'getFilm'])->name('films.get');
Route::post('/films/upload', [FilmController::class, 'uploadFilm'])->name('films.upload');




Route::get('/video/{filename}', function (Request $request, $filename) {
    $path = storage_path('app/public/videos/' . $filename);
    $size = filesize($path);
    $file = fopen($path, 'rb');

    $status = 200;
    $headers = [
        'Content-Type' => 'video/mp4',
        'Accept-Ranges' => 'bytes',
        'Content-Length' => $size
    ];

    // Vérifier si une requête de type Range a été faite
    if ($request->headers->has('Range')) {
        $range = $request->headers->get('Range');
        $numbers = substr($range, 6); // Supprimer le préfixe "bytes="
        list($offset, $limit) = explode('-', $numbers);

        if ($limit == '') {
            $limit = $size - 1;
        }

        $length = $limit - $offset + 1;

        fseek($file, $offset); // Positionner le pointeur de fichier à l'offset spécifié
        $status = 206; // Partial Content
        $headers['Content-Length'] = $length;
        $headers['Content-Range'] = "bytes $offset-$limit/$size";
    }

    $response = new StreamedResponse(function () use ($file, $length) {
        echo fread($file, $length);
        fclose($file);
    }, $status, $headers);

    return $response;
});























