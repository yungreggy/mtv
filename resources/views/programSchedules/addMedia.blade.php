	<!-- Section rétractable pour ajouter des clips aléatoires par genre -->
    <div class="accordion mt-4" id="accordionGenre">
				<div class="card">
					<div class="card-header" id="headingGenre">
						<h2 class="mb-0">
							<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseGenre" aria-expanded="true" aria-controls="collapseGenre">
								Ajouter des Clips Aléatoires par Genre
							</button>
						</h2>
					</div>

					<div id="collapseGenre" class="collapse" aria-labelledby="headingGenre" data-parent="#accordionGenre">
						<div class="card-body">
							<form action="{{ route('programSchedules.addRandomClips', $schedule->id) }}" method="POST">
								@csrf
								<div class="form-group">
									<label for="genres">Genres</label>
									<div>
										@foreach($groupedGenres as $category => $genres)
                                            <div class="genre-block">
                                                <h5>{{ $category }}</h5>
                                                <div class="form-check">
                                                    <input class="form-check-input genre-all" type="checkbox" id="genreAll{{ Str::slug($category) }}">
                                                    <label class="form-check-label" for="genreAll{{ Str::slug($category) }}">
                                                        All
                                                    </label>
                                                </div>
                                                @foreach($genres as $genre)
                                                    <div class="form-check">
                                                        <input class="form-check-input genre-checkbox-{{ Str::slug($category) }}" type="checkbox" name="genres[]" value="{{ $genre->id }}" id="genre{{ $genre->id }}">
                                                        <label
                                                            class="form-check-label" for="genre{{ $genre->id }}">{{ $genre->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
									</div>
								</div>

								<div class="form-group">
									<label for="year">Année</label>
									<input type="number" class="form-control" id="year" name="year" placeholder="1991">
								</div>

								<div class="form-group">
									<label for="year_range_start">Début de la plage d'années</label>
									<input type="number" class="form-control" id="year_range_start" name="year_range_start" placeholder="1990">
								</div>

								<div class="form-group">
									<label for="year_range_end">Fin de la plage d'années</label>
									<input type="number" class="form-control" id="year_range_end" name="year_range_end" placeholder="1994">
								</div>

								<button type="submit" class="btn btn-secondary">Ajouter des Clips Aléatoires</button>
							</form>
						</div>
					</div>
				</div>
			</div>


			<!-- Script JavaScript pour gérer la sélection de 'All' -->
			<script>
				document.addEventListener ( 'DOMContentLoaded', function () {
document.querySelectorAll ( '.genre-all' ).forEach ( function ( allCheckbox ) {
allCheckbox.addEventListener ( 'change', function () {
const categorySlug = this.id.replace ( 'genreAll', '' );
const checkboxes = document.querySelectorAll ( `.genre-checkbox-${ categorySlug }` );
checkboxes.forEach ( function ( checkbox ) {
checkbox.checked = allCheckbox.checked;
} );
} );
} );
} );
			</script>


			<br>


			<!-- Onglets pour les jours de la semaine associés -->
			<ul
				class="nav nav-tabs mt-4" id="myTab" role="tablist">
				@foreach($schedule->days as $index => $day)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($index == 0) active @endif" id="tab-{{ strtolower($day->day_of_week) }}" data-toggle="tab" href="#{{ strtolower($day->day_of_week) }}" role="tab" aria-controls="{{ strtolower($day->day_of_week) }}" aria-selected="true">{{ $day->day_of_week }}</a>
                    </li>
                @endforeach
			</ul>
			<div
				class="tab-content mt-3" id="myTabContent">
				@foreach($schedule->days as $index => $day)
                    <div class="tab-pane fade @if($index == 0) show active @endif" id="{{ strtolower($day->day_of_week) }}" role="tabpanel" aria-labelledby="tab-{{ strtolower($day->day_of_week) }}">
                        <h3 class="mt-4">Ajouter une Vidéo Musicale pour
                            {{ $day->day_of_week }}
                        </h3>
                        <form action="{{ route('programSchedules.addMusicVideo', $schedule->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="music_video_id">Vidéo Musicale</label>
                                <select class="form-control" id="music_video_id" name="music_video_id" required>
                                    <option value="">-- Sélectionnez une vidéo musicale --</option>
                                    @foreach($musicVideos as $musicVideo)
                                        <option value="{{ $musicVideo->id }}">{{ $musicVideo->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="day_of_week" value="{{ $day->day_of_week }}">
                            <button type="submit" class="btn btn-secondary">Ajouter</button>
                        </form>

                        <h3 class="mt-4">Clips Musicaux Programmés pour
                            {{ $day->day_of_week }}
                        </h3>
                        @foreach($schedule->musicVideos->where('pivot.day_of_week', $day->day_of_week) as $musicVideo)
                            <div class="card mb-2 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="far fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($musicVideo->pivot->play_time)->format('g:i A') }}
                                    </h5>
                                    <p class="card-text">
                                        <strong>
                                            <a href="{{ route('musicVideos.show', $musicVideo->id) }}">{{ $musicVideo->title }}</a>
                                        </strong>
                                        -
                                        <a href="{{ route('artists.show', $musicVideo->artist->id) }}">{{ $musicVideo->artist->name }}</a>
                                    </p>
                                    <form action="{{ route('programSchedules.removeMusicVideo', [$schedule->id, $musicVideo->id]) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
</form>

                                    <form action="{{ route('programSchedules.moveMusicVideo', [$schedule->id, $musicVideo->id, 'up']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">⬆️</button>
                                    </form>
                                    <form action="{{ route('programSchedules.moveMusicVideo', [$schedule->id, $musicVideo->id, 'down']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">⬇️</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach



			<!-- Section rétractable pour ajouter une rediffusion -->
			<div class="accordion mt-4" id="accordionExample">
				<div class="card">
					<div class="card-header" id="headingOne">
						<h2 class="mb-0">
							<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Ajouter une Rediffusion
							</button>
						</h2>
					</div>

					<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
						<div class="card-body">
							<form action="{{ route('programSchedules.addReplay', $schedule->id) }}" method="POST">
								@csrf
								<div class="form-group">
									<label for="replay_day_of_week">Jour de la Rediffusion</label>
									<select class="form-control" id="replay_day_of_week" name="replay_day_of_week" required>
										<option value="">-- Sélectionnez un jour --</option>
										@foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                            <option value="{{ $day }}">{{ $day }}</option>
                                        @endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="replay_start_time">Heure de début de la Rediffusion</label>
									<input type="time" class="form-control" id="replay_start_time" name="replay_start_time" required>
								</div>
								<div class="form-group">
									<label for="replay_end_time">Heure de fin de la Rediffusion</label>
									<input type="time" class="form-control" id="replay_end_time" name="replay_end_time" required>
								</div>
								<div class="form-group">
									<label for="description">Description</label>
									<textarea class="form-control" id="description" name="description"></textarea>
								</div>
								<button type="submit" class="btn btn-secondary">Ajouter la Rediffusion</button>
							</form>
						</div>
					</div>
				</div>
			</div>