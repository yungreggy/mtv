-- Directeurs
CREATE TABLE directors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    timestamps TIMESTAMP
);

CREATE TABLE replays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_schedule_id INT NOT NULL,
    schedule_day_id INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_schedule_id) REFERENCES program_schedules(id),
    FOREIGN KEY (schedule_day_id) REFERENCES schedule_days(id)
);



-- Artistes
CREATE TABLE artists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    thumbnail_image VARCHAR(255),
    biography TEXT,
    website VARCHAR(255),
    main_genre VARCHAR(100),
    career_start_year INT,
    country_of_origin VARCHAR(100)
);



-- Labels (Étiquettes)
CREATE TABLE labels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    logo_image VARCHAR(255),
    website VARCHAR(255),
    description TEXT,
    foundation_year INT
);

-- Albums
CREATE TABLE albums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    year INT,
    label_id INT,
    artist_id INT,
    thumbnail_image VARCHAR(255),
    description TEXT,
    track_count INT,
    release_date DATE,
    url VARCHAR(255),
    FOREIGN KEY (label_id) REFERENCES labels(id),
    FOREIGN KEY (artist_id) REFERENCES artists(id),
    UNIQUE (title, artist_id, year)
);



CREATE TABLE music_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    album_id INT,
    year INT,
    release_date DATE,
    director_id INT,
    duration TIME,
    timestamp TIMESTAMP,
    file_path VARCHAR(255),
    thumbnail_image VARCHAR(255),
    video_quality VARCHAR(50),
    age_rating VARCHAR(10),
    language VARCHAR(50),
    status VARCHAR(100),
    tags TEXT,
    play_frequency VARCHAR(50),
    labels VARCHAR(255),
    FOREIGN KEY (album_id) REFERENCES albums(id),
    FOREIGN KEY (director_id) REFERENCES directors(id),
    UNIQUE (title, album_id, year),
    INDEX (album_id),  -- Pour améliorer les performances des jointures avec albums
    INDEX (director_id)  -- Pour améliorer les performances des jointures avec directors
);

-- Cette table peut être créée pour lier les albums aux genres
CREATE TABLE genre_albums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT,
    genre_id INT,
    FOREIGN KEY (album_id) REFERENCES albums(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id)
);

CREATE TABLE playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    channel_id INT,
    name VARCHAR(255),
    description TEXT,
    thumbnail_image VARCHAR(255),
    playlist_type VARCHAR(50),
    visibility VARCHAR(50),
    created_at DATETIME,
    updated_at DATETIME,
    view_count INT,
    FOREIGN KEY (channel_id) REFERENCES channels(id),
    UNIQUE (name, channel_id)

);



CREATE TABLE playlist_musicvideos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_id INT,
    musicvideo_id INT,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id),
    FOREIGN KEY (musicvideo_id) REFERENCES music_videos(id)
);




-- Genres
CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    icon_path VARCHAR(255),
    popularity INT,
    theme_color VARCHAR(7)
);



-- Jonction Genres et Vidéos Musicales
CREATE TABLE genres_musicvideos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    musicvideo_id INT,
    genre_id INT,
    FOREIGN KEY (musicvideo_id) REFERENCES music_videos(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id)
);

-- Marques / Magasins
CREATE TABLE brands_stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    timestamps TIMESTAMP,
    logo_image VARCHAR(255)
);

-- Publicités
CREATE TABLE pubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    brand_store_id INT,
    description TEXT,
    duration TIME,
    year INT,
    timestamp TIMESTAMP,
    file_path VARCHAR(255),
    thumbnail_image VARCHAR(255),
    ad_type VARCHAR(100),
    target_demographic VARCHAR(255),
    frequency INT,
    FOREIGN KEY (brand_store_id) REFERENCES brands_stores(id),
    UNIQUE (name, brand_store_id)
);



-- Blocs de Publicités
CREATE TABLE bloc_pubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    duration TIME,
    timestamp TIMESTAMP,
    scheduled_start_time DATETIME,
    scheduled_end_time DATETIME,
    status VARCHAR(100),
    priority INT,
    description TEXT,
    program_id INT,
    FOREIGN KEY (program_id) REFERENCES programs(id),
    UNIQUE (name, program_id)
);


-- Jonction Blocs-Pubs
CREATE TABLE blocpubs_pubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blocpub_id INT,
    pub_id INT,
    FOREIGN KEY (blocpub_id) REFERENCES bloc_pubs(id),
    FOREIGN KEY (pub_id) REFERENCES pubs(id)
);


CREATE TABLE channels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    thumbnail_image VARCHAR(255),
    logo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



-- Programmes
CREATE TABLE programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    duration TIME,
    genre VARCHAR(255),
    status VARCHAR(100),
    target_audience VARCHAR(255),
    thumbnail_image VARCHAR(255),
    premiere_date DATE,
    timestamps TIMESTAMP
);


CREATE TABLE program_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT,
    name VARCHAR(255),
    description TEXT,
    start_time DATETIME,
    end_time DATETIME,
    repeat_schedule VARCHAR(255),
    status VARCHAR(100),
    special_notes TEXT,
    priority INT,
    FOREIGN KEY (program_id) REFERENCES programs(id)
);


-- Émissions de Télévision
CREATE TABLE tv_shows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    years_active VARCHAR(255),
    genre_id INT,
    description TEXT,
    timestamps TIMESTAMP,
    creator VARCHAR(255),
    season_count INT,
    target_audience VARCHAR(255),
    official_website VARCHAR(255),
    age_rating VARCHAR(10),
    status VARCHAR(100),
    country_of_origin VARCHAR(100),
    FOREIGN KEY (genre_id) REFERENCES genres(id),
    UNIQUE (title, creator, years_active),
    
);



-- Saisons des Émissions de Télévision
CREATE TABLE tv_shows_seasons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tv_show_id INT,
    year INT,
    start_date DATE,
    end_date DATE,
    episode_count INT,
    description TEXT,
    thumbnail_image VARCHAR(255),
    streaming_url VARCHAR(255),
    timestamps TIMESTAMP,
    FOREIGN KEY (tv_show_id) REFERENCES tv_shows(id)
);


-- Épisodes des Émissions de Télévision
CREATE TABLE tv_shows_episodes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tv_show_id INT,
    title VARCHAR(255),
    description TEXT,
    duration TIME,
    file_path VARCHAR(255),
    timestamps TIMESTAMP,
    episode_number INT,
    overall_episode_number INT,
    air_date DATE,
    guest_stars TEXT,
    rating DECIMAL(3, 1),
    director_id INT,
    writer VARCHAR(255),
    streaming_url VARCHAR(255),
    FOREIGN KEY (tv_show_id) REFERENCES tv_shows(id),
    FOREIGN KEY (director_id) REFERENCES directors(id),
    UNIQUE (tv_show_id, episode_number, air_date),  -- Assure l'unicité des épisodes dans le contexte d'une même série et jour de diffusion
    INDEX (director_id)

);



-- Films
CREATE TABLE films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) ,
    year INT,
    director_id INT,
    label_id INT,
    description TEXT,
    duration TIME,
    file_path VARCHAR(255),
    local_image_path VARCHAR(255),
    url_poster VARCHAR(255),
    timestamps TIMESTAMP,
    rating VARCHAR(10),
    primary_language VARCHAR(50),
    country_of_origin VARCHAR(100),
    FOREIGN KEY (director_id) REFERENCES directors(id),
    FOREIGN KEY (label_id) REFERENCES labels(id),
    UNIQUE (title, year, director_id) ,
    INDEX (director_id),  -- Facilite les jointures avec la table des directeurs
    INDEX (label_id),     -- Facilite les jointures avec la table des labels
    UNIQUE (title, year, director_id)  -- Un index composite pour assurer l'unicité des films
);


-- Jonction Films-Genres
CREATE TABLE genre_films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT,
    genre_id INT,
    FOREIGN KEY (film_id) REFERENCES films(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id)
);


-- Acteurs
CREATE TABLE actors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE
);


-- Jonction Acteurs-Films
CREATE TABLE actor_film (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actor_id INT,
    film_id INT,
    role VARCHAR(255),
    FOREIGN KEY (actor_id) REFERENCES actors(id),
    FOREIGN KEY (film_id) REFERENCES films(id)
);

-- Intermèdes
CREATE TABLE interludes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    year INT,
    description TEXT,
    duration TIME,
    thumbnail_image VARCHAR(255),
    file_path VARCHAR(255),
    UNIQUE (name, year)
);

-- Bumpers
CREATE TABLE bumpers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    year INT,
    duration TIME,
    thumbnail_image VARCHAR(255),
    file_path VARCHAR(255),
    UNIQUE (name, year)
);
