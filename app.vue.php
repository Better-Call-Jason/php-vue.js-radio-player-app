<script>

    new Vue({
        el: '#app',
        data: {
            files: [],
            player: null,
            name: '',
            album: '',
            displayAlbum: '',
            currentFileIndex: 0,
            isFirstPlay: true,
        },

        created() {
            let urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('name')) {
                let name = urlParams.get('name');
                name = name.charAt(0).toUpperCase() + name.slice(1);
                this.name = name + "'s";
            }
            if (urlParams.has('album')) {
                let album = urlParams.get('album');
                album = album.replace(/\b\w/g, function(letter) {
                    return letter.toUpperCase();
                });
                let displayAlbum = album.replace(/-/g, ' ').replace(/\b\w/g, function(letter) {
                    return letter.toUpperCase();
                });
                this.album = album;
                this.displayAlbum = displayAlbum;
            }
            this.updateTitle();
        },

        mounted() {
            this.fetchMediaFiles();
            this.$nextTick(() => {
                this.player = videojs('audio-player');
                this.player.on('ended', this.playNext);

            });
        },

        methods: {
            updateTitle() {
                document.title = this.name + ' ' + this.displayAlbum + ' Player';
            },

            fetchMediaFiles() {
                axios.get('ajax.php', {
                    params: {
                        album: this.album
                    }
                }).then(response => {
                    this.files = response.data;
                    if (this.files.length > 0) {
                        this.playAudio(this.files[0]);
                    }
                }).catch(error => {
                    console.error('There was an error fetching the media files:', error);
                });
            },

            cleanFileName(file) {
                return file.replace(/^\d+-/, '').replace(/-/g, ' ').replace(/\.mp3$/, '');
            },

            playAudio(file) {
                this.currentFile = 'media/' + this.album + '/' + file;
                this.currentFileIndex = this.files.indexOf(file);
                this.$nextTick(() => {
                    this.player.src({type: 'audio/mp4', src: this.currentFile});
                    this.player.load();
                    this.player.play();
                    if (this.isFirstPlay) {
                        this.player.volume(0.5);
                        this.isFirstPlay = false;
                    }

                });
            },

            playNext() {
                if (this.currentFileIndex < this.files.length - 1) {
                    this.playAudio(this.files[this.currentFileIndex + 1]);
                }
            },
        },

        beforeDestroy() {
            if (this.player) {
                this.player.dispose();
            }
        }
    });

</script>