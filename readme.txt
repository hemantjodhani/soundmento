=== Soundmento ===
Contributors: hemantjodhani  
Tags: elementor, audio player, music, podcast, audio widget  
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.2  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Modern Elementor widget for music and podcast playlists.

== Description ==

**Soundmento** is a custom Elementor widget plugin designed for musicians, podcasters, and content creators to elegantly showcase and play audio tracks. With a responsive, modern UI and dynamic playlist control, it enhances audio presentation with artist names, album art, and play durations.

**Features include:**
- Elementor widget with drag-and-drop controls.
- Repeater fields to add multiple audio tracks.
- Custom audio player with play/pause, next, and back buttons.
- Artwork, track title, and artist display.
- Dynamic track duration fetched using `getID3`.
- Beautiful responsive design with custom styling.
- Playlist view toggle and track switching.

== Installation ==

1. Upload the plugin folder `soundmento` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Make sure Elementor is installed and activated.
4. Edit any page with Elementor, and search for **Soundmento** widget.
5. Drag the widget into your page and configure audio items via the repeater.

== Frequently Asked Questions ==

= Does this plugin work without Elementor? =  
No. Elementor must be installed and activated for Soundmento to function.

= Can I add multiple tracks? =  
Yes. Use the repeater control in the widget settings to add multiple tracks.

= How does the plugin show audio duration? =  
The plugin uses the `getID3` library to analyze uploaded audio files and extract their playtime.

= What types of audio files are supported? =  
Any audio file supported by modern browsers (e.g., MP3, OGG, WAV) and uploaded via the WordPress media library.

== Screenshots ==

1. Frontend view of the Soundmento audio player with playlist, artwork, and playback controls. 
2. Beautiful open header layout with an audio cover image.
3. Search for "Soundmento," "playlist," or a related keyword to add the Soundmento widget in the Elementor Page Builder.
4. Soundmento widget in Elementor editor with audio tracks added via repeater.

== Changelog ==

= 1.0.0 =
* Initial release of Soundmento.
* Added Elementor widget with repeater control.
* Integrated custom audio player with play/pause and playlist UI.
* Included support for artwork, artist name, and dynamic audio durations using getID3.

== Upgrade Notice ==

= 1.0.0 =
First release – no upgrade needed.

== Credits ==

This plugin uses the [getID3](https://getid3.sourceforge.net/) library to extract audio file metadata.

== License ==

Soundmento is licensed under the GPLv2 or later.
