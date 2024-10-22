<style><?php include "./css/audio-music.css" ?></style>
<style>
    #audiomusic-button {
        background-color: #FFCF8C;
    }
</style>
<h1 class="settings-headline">Audio and Music</h1>
<div class="settings-audio-container">
    <div class="settings-audio-divider"></div>
    Sound effects volume: <br>
    <input type="range" min="0" max="100" id="volume-control-sfx"> <br>
    Music volume: <br>
    <input type="range" min="0" max="100" id="volume-control-music"> <br>
    User interface sound effects volume: <br>
    <input type="range" min="0" max="100" id="volume-control-ui">
    <button id="audioSaveButton">Save</button>
</div>

