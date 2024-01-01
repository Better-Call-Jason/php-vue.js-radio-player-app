<body>
    <div id="app">
        <h2>{{ name }} {{ displayAlbum }} Player</h2>

        <article class="media-player-card">
            <video id="audio-player"
                   class="video-js"
                   v-bind:style="{ 'background-image': 'url(\'media/' + album + '/cover.jpg\')',
                                          'background-repeat': 'no-repeat',
                                          'background-position': 'center center',
                                          'background-size': 'cover' }"
                   controls
                   width="auto"
                   height="auto"
                   data-setup="{}"
                   playsinline>
            </video>
        </article>
        <article class="grid">
            <button v-for="file in files"
                    @click="playAudio(file)"
                    class="">
                {{ cleanFileName(file) }}
            </button>
        </article>
    </div>

