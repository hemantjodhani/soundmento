document.addEventListener("DOMContentLoaded", function () {

    const player = document.querySelector(".som-player");
    const playerHeader = player.querySelector(".som-player__header");
    const playerControls = player.querySelector(".som-player__controls");
    const playerPlayList = player.querySelectorAll(".som-player__song");
    const playerSongs = player.querySelectorAll(".som-audio");
    const playButton = player.querySelector(".som-play");
    const nextButton = player.querySelector(".som-next");
    const backButton = player.querySelector(".som-back");
    const playlistButton = player.querySelector(".som-playlist");
    const slider = player.querySelector(".som-slider");
    const sliderContext = player.querySelector(".som-slider__context");
    const sliderName = sliderContext.querySelector(".som-slider__name");
    const sliderTitle = sliderContext.querySelector(".som-slider__title");
    const sliderContent = slider.querySelector(".som-slider__content");
    const sliderContentLength = playerPlayList.length - 1;
    const sliderWidth = 100;
    let left = 0;
    let count = 0;
    let song = playerSongs[count];
    let isPlay = false;
    const pauseIcon = playButton.querySelector("img[alt = 'pause-icon']");
    const playIcon = playButton.querySelector("img[alt = 'play-icon']");
    const progres = player.querySelector(".som-progres");
    const progresFilled = progres.querySelector(".som-progres__filled");
    let isMove = false;
    function openPlayer() {
        playerHeader.classList.add("som-open-header");
        playerControls.classList.add("som-move");
        slider.classList.add("som-open-slider");
    }
    function closePlayer() {
        playerHeader.classList.remove("som-open-header");
        playerControls.classList.remove("som-move");
        slider.classList.remove("som-open-slider");
    }

    function next(index) {
        count = index || count;
        if (count == sliderContentLength) {
            count = 0;
        } else {
            count++;
        }
        left = count * sliderWidth;
        left = Math.min(left, sliderContentLength * sliderWidth);
        sliderContent.style.transform = `translate3d(-${left}%, 0, 0)`;
        run();
    }

    function back(index) {
        count = index || count;
        if (count == 0) {
            count = count;
            return;
        }
        left = (count - 1) * sliderWidth;
        left = Math.max(0, left);
        sliderContent.style.transform = `translate3d(-${left}%, 0, 0)`;
        count--;
        run();
    }
    function changeSliderContext() {
        sliderContext.style.animationName = "opacity";
        sliderName.textContent = playerPlayList[count].querySelector(
            ".som-player__song-name"
        ).textContent;

        sliderTitle.textContent = playerPlayList[count].querySelector(
            ".som-player__title"
        ).textContent;
        if (sliderName.textContent.length > 16) {
            const textWrap = document.createElement("span");
            textWrap.className = "som-text-wrap";
            textWrap.innerHTML = sliderName.textContent + "  " + sliderName.textContent;
            sliderName.innerHTML = "";
            sliderName.append(textWrap);
        }
        if (sliderTitle.textContent.length >= 18) {
            const textWrap = document.createElement("span");
            textWrap.className = "som-text-wrap";
            textWrap.innerHTML =
                sliderTitle.textContent + "  " + sliderTitle.textContent;
            sliderTitle.innerHTML = "";
            sliderTitle.append(textWrap);
        }
    }
    function selectSong() {
        song = playerSongs[count];
        for (const item of playerSongs) {
            if (item != song) {
                item.pause();
                item.currentTime = 0;
            }
        }
        if (isPlay) song.play();
    }
    function run() {
        changeSliderContext();
        selectSong();
    }
    function playSong() {
        if (song.paused) {
            song.play();
            playIcon.style.display = "none";
            pauseIcon.style.display = "block";
        } else {
            song.pause();
            isPlay = false;
            playIcon.style.display = "";
            pauseIcon.style.display = "";
        }
    }
    function progresUpdate() {
        const progresFilledWidth = (this.currentTime / this.duration) * 100 + "%";
        progresFilled.style.width = progresFilledWidth;
        if (isPlay && this.duration == this.currentTime) {
            next();
        }
        if (count == sliderContentLength && song.currentTime == song.duration) {
            playIcon.style.display = "block";
            pauseIcon.style.display = "";
            isPlay = false;
        }
    }
    function scurb(e) {
        const currentTime =
            ((e.clientX - progres.getBoundingClientRect().left) / progres.offsetWidth) *
            song.duration;
        song.currentTime = currentTime;
    }
    changeSliderContext();
    sliderContext.addEventListener("click", openPlayer);
    sliderContext.addEventListener(
        "animationend",
        () => (sliderContext.style.animationName = "")
    );
    playlistButton.addEventListener("click", closePlayer);
    nextButton.addEventListener("click", () => {
        next(0);
    });
    backButton.addEventListener("click", () => {
        back(0);
    });
    playButton.addEventListener("click", () => {
        isPlay = true;
        playSong();
    });
    playerSongs.forEach((song) => {
        // song.addEventListener("loadeddata", durationSongs);
        song.addEventListener("timeupdate", progresUpdate);
    });
    progres.addEventListener("pointerdown", (e) => {
        scurb(e);
        isMove = true;
    });
    document.addEventListener("pointermove", (e) => {
        if (isMove) {
            scurb(e);
            song.muted = true;
        }
    });
    document.addEventListener("pointerup", () => {
        isMove = false;
        song.muted = false;
    });

    playerPlayList.forEach((item, index) => {
        item.addEventListener("click", function () {
            count = index;
            left = count * sliderWidth;
            sliderContent.style.transform = `translate3d(-${left}%, 0, 0)`;
            isPlay = true;
            run();
            playIcon.style.display = "none";
            pauseIcon.style.display = "block";
        });
    });
});
